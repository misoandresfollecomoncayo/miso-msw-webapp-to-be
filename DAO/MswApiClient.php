<?php

/**
 * MswApiClient — Capa anti-corruption hacia los microservicios NUEVOS de
 * Customer e Inventory (Strangler Fig).
 *
 * Encapsula el cliente HTTP y la traducción de contratos (enums y nombres de
 * campos) entre el monolito legado (CloudEngine) y las APIs REST nuevas.
 *
 * SOLO los endpoints de API/Customer y API/Inventory delegan aquí. El resto del
 * monolito sigue intacto contra la base de datos legada, de modo que otros
 * módulos que usan CustomerDAO/InventoryDAO directamente (Bills, Purchases,
 * Shipments) no se ven afectados.
 *
 * Autoload: al ser una clase sin namespace en /DAO, UniexpressAutoload la
 * resuelve por nombre de clase, igual que CustomerDAO/InventoryDAO.
 */
class MswApiClient {

    /**
     * Base de las APIs nuevas, expuestas por el ALB.
     * Se usa HTTP:80 a propósito para evitar el certificado autofirmado del
     * listener 443. Ajustar aquí si cambia el DNS o se habilita un endpoint
     * interno.
     */
    const BASE_URL = "http://mi-alb-1734219042.us-east-1.elb.amazonaws.com";

    /**
     * Llamada HTTP genérica.
     * @return array ["status" => int, "body" => mixed(json decodificado|texto)]
     */
    public static function request($method, $path, $body = null) {
        $ch = curl_init(self::BASE_URL . $path);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
        curl_setopt($ch, CURLOPT_TIMEOUT, 20);

        $headers = array("Accept: application/json");
        if ($body !== null) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($body));
            $headers[] = "Content-Type: application/json";
        }
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $raw    = curl_exec($ch);
        $status = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        $decoded = json_decode($raw, true);
        return array(
            "status" => $status,
            "body"   => ($decoded !== null ? $decoded : $raw)
        );
    }

    /** True si el status HTTP es 2xx. */
    public static function isOk($res) {
        return $res["status"] >= 200 && $res["status"] < 300;
    }

    /** Extrae un mensaje legible del cuerpo de error de NestJS. */
    public static function errorMessage($res, $fallback) {
        $b = isset($res["body"]) ? $res["body"] : null;
        if (is_array($b) && isset($b["message"])) {
            return is_array($b["message"]) ? implode(" ", $b["message"]) : $b["message"];
        }
        return $fallback;
    }

    // ------------------------------------------------------------------
    // Traductores de contrato legado <-> API nueva
    // ------------------------------------------------------------------

    /** Género: "MALE"/"Hombre"/"M" -> "M"; cualquier otro -> "F". */
    public static function genderToApi($value) {
        $v = strtoupper(trim((string) $value));
        return ($v === "M" || $v === "MALE" || $v === "HOMBRE") ? "M" : "F";
    }

    /** Idioma: "ENGLISH"/"EN" -> "en"; resto -> "es". */
    public static function languageToApi($value) {
        $v = strtolower(trim((string) $value));
        return ($v === "en" || $v === "english" || $v === "ingles") ? "en" : "es";
    }

    /**
     * DocumentType: el legado maneja idDocumentType; la API nueva espera un
     * string (1..36). Resolvemos el nombre desde los datos de referencia
     * legados; si no se encuentra, se envía el valor crudo.
     */
    public static function documentTypeToApi($idDocumentType) {
        $dt = DocumentTypeDAO::getDocumentTypeById($idDocumentType);
        if ($dt != null) {
            $name = (string) $dt->getName();
            return (strlen($name) > 36) ? substr($name, 0, 36) : $name;
        }
        return (string) $idDocumentType;
    }

    /** Normaliza una fecha a ISO 8601 (Y-m-d) para el @IsISO8601 del DTO. */
    public static function toIsoDate($value) {
        $ts = strtotime((string) $value);
        return ($ts !== false) ? date("Y-m-d", $ts) : (string) $value;
    }

    /**
     * cityId hacia la API nueva.
     *
     * OJO (mismatch de datos conocido): el legado usa UUID para idCity, mientras
     * la API nueva espera un entero (@IsInt @Min(1)). Mientras no exista un mapeo
     * legado(UUID) -> nuevo(int), enviamos el valor si es numérico; si no, 1
     * (para no romper la validación). Requiere alinear el esquema de ciudades
     * entre ambos sistemas o exponer un mapeo.
     */
    public static function cityIdToApi($idCity) {
        return is_numeric($idCity) ? max(1, intval($idCity)) : 1;
    }

    /**
     * Resuelve país/ciudad (nombres) a partir del cityId que devuelve la API
     * nueva, usando los datos de referencia legados. Si no se encuentra (p. ej.
     * por el mismatch UUID/int de ciudades), devuelve cadenas vacías.
     */
    public static function resolveCountryCity($cityId) {
        $city = CityDAO::getCityById($cityId);
        if ($city != null && $city->getCountry() != null) {
            return array(
                "country" => $city->getCountry()->getName(),
                "city"    => $city->getName()
            );
        }
        return array("country" => "", "city" => "");
    }

    // ------------------------------------------------------------------
    // Reconstrucción de un objeto Customer (modelo legado) desde la API nueva.
    // Se usa para PRECARGAR el formulario de edición server-side sin cambiar el
    // resto de la página: los getters del modelo quedan satisfechos con datos de
    // la API nueva.
    // ------------------------------------------------------------------

    /**
     * Trae GET /api/customers/{id} y arma un objeto Customer legado.
     * @return Customer|null
     */
    public static function customerModelFromApi($id) {
        $res = self::request("GET", "/api/customers/" . rawurlencode($id));
        if (!self::isOk($res) || !is_array($res["body"]) || !isset($res["body"]["id"])) {
            return null;
        }
        $c = $res["body"];

        // Reverse enums a los valores exactos de los <select> legados.
        $gender   = (strtoupper((string)(isset($c["gender"]) ? $c["gender"] : "")) === "M") ? "MALE" : "FEMALE";
        $language = (strtolower((string)(isset($c["language"]) ? $c["language"] : "")) === "en") ? "ENGLISH" : "SPANISH";

        // documentType (string) -> idDocumentType legado (match por nombre). Si no
        // hay match, null (el <select> de tipo de documento está guardado con if).
        $idDocumentType = self::documentTypeIdFromApi(isset($c["documentType"]) ? $c["documentType"] : "");

        // idCity: no hay mapeo UUID<->int (ver punto 1). Se usa una ciudad legada
        // por defecto SOLO para que getCity() resuelva y la página no crashee; la
        // preselección real de ciudad queda pendiente del mapeo de ciudades.
        $idCity = self::defaultLegacyCityId();

        return new Customer(
            $c["id"],
            isset($c["lockerNumber"])   ? $c["lockerNumber"]   : "",
            isset($c["names"])          ? $c["names"]          : "",
            $gender,
            isset($c["birthdate"])      ? self::toIsoDate($c["birthdate"]) : "",
            $idDocumentType,
            isset($c["documentNumber"]) ? $c["documentNumber"] : "",
            $idCity,
            isset($c["address"])        ? $c["address"]        : "",
            isset($c["telephone"])      ? $c["telephone"]      : "",
            isset($c["telephone2"])     ? $c["telephone2"]     : "",
            isset($c["email"])          ? $c["email"]          : "",
            "",                                     // password (no expuesto por la API)
            !empty($c["active"]),
            false,                                  // deleted
            isset($c["createdAt"])      ? $c["createdAt"]      : "",
            null,                                   // idRole
            $language
        );
    }

    /** Resuelve idDocumentType legado a partir del string de la API (match por nombre). */
    private static function documentTypeIdFromApi($name) {
        if ($name === null || $name === "") {
            return null;
        }
        foreach (DocumentTypeDAO::getDocumentTypes() as $dt) {
            if (strcasecmp($dt->getName(), $name) === 0) {
                return $dt->getIdDocumentType();
            }
        }
        return null;
    }

    /** Primera ciudad legada disponible (fallback para que getCity() no sea null). */
    private static function defaultLegacyCityId() {
        $cities = CityDAO::getCities();
        if (is_array($cities) && count($cities) > 0) {
            return $cities[0]->getIdCity();
        }
        return null;
    }
}
