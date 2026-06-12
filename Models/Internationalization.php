<?php

use Cloud\Engine\PHP\HTTP\CloudEngineRequest;

class Internationalization {
    
    public static function loginTitle() {
        switch (CloudEngineRequest::getLanguage()) {
            case CloudEngineRequest::LANGUAGE_SPANISH:
                return "Iniciar sesión";
            default:
                return "Sign In";
        }
    }
    
    public static function loginButton() {
        switch (CloudEngineRequest::getLanguage()) {
            case CloudEngineRequest::LANGUAGE_SPANISH:
                return "ENTRAR";
            default:
                return "LOG IN";
        }
    }
    
    public static function username() {
        switch (CloudEngineRequest::getLanguage()) {
            case CloudEngineRequest::LANGUAGE_SPANISH:
                return "Usuario";
            default:
                return "User";
        }
    }
    
    public static function trackingNumber() {
        switch (CloudEngineRequest::getLanguage()) {
            case CloudEngineRequest::LANGUAGE_SPANISH:
                return "Número de rastreo";
            default:
                return "Tracking No.";
        }
    }
    
    public static function lockerNumber() {
        switch (CloudEngineRequest::getLanguage()) {
            case CloudEngineRequest::LANGUAGE_SPANISH:
                return "Número de casillero";
            default:
                return "Locker No.";
        }
    }
    
    public static function usernamePlaceholder() {
        switch (CloudEngineRequest::getLanguage()) {
            case CloudEngineRequest::LANGUAGE_SPANISH:
                return "Digite su usuario";
            default:
                return "Type your user";
        }
    }
    
    public static function password() {
        switch (CloudEngineRequest::getLanguage()) {
            case CloudEngineRequest::LANGUAGE_SPANISH:
                return "Clave";
            default:
                return "Password";
        }
    }
    
    public static function passwordPlaceholder() {
        switch (CloudEngineRequest::getLanguage()) {
            case CloudEngineRequest::LANGUAGE_SPANISH:
                return "Digite su clave";
            default:
                return "Type your password";
        }
    }
    
    public static function passwordRecovery() {
        switch (CloudEngineRequest::getLanguage()) {
            case CloudEngineRequest::LANGUAGE_SPANISH:
                return "¿Olvidó su clave?";
            default:
                return "¿Forgot your password?";
        }
    }
    
    public static function newCustomer() {
        switch (CloudEngineRequest::getLanguage()) {
            case CloudEngineRequest::LANGUAGE_SPANISH:
                return "Crear casillero";
            default:
                return "Create P.O. Box";
        }
    }
    
    public static function passwordRecoveryTitle() {
        switch (CloudEngineRequest::getLanguage()) {
            case CloudEngineRequest::LANGUAGE_SPANISH:
                return "Recuperar clave";
            default:
                return "Password recovery";
        }
    }
    
    public static function sendButton() {
        switch (CloudEngineRequest::getLanguage()) {
            case CloudEngineRequest::LANGUAGE_SPANISH:
                return "ENVIAR";
            default:
                return "SEND";
        }
    }
    
    public static function backButton() {
        switch (CloudEngineRequest::getLanguage()) {
            case CloudEngineRequest::LANGUAGE_SPANISH:
                return "VOLVER";
            default:
                return "BACK";
        }
    }
    
    public static function nextButton() {
        switch (CloudEngineRequest::getLanguage()) {
            case CloudEngineRequest::LANGUAGE_SPANISH:
                return "SIGUIENTE";
            default:
                return "NEXT";
        }
    }
    
    public static function saveButton() {
        switch (CloudEngineRequest::getLanguage()) {
            case CloudEngineRequest::LANGUAGE_SPANISH:
                return "GUARDAR";
            default:
                return "SAVE";
        }
    }
    
    public static function newPassword() {
        switch (CloudEngineRequest::getLanguage()) {
            case CloudEngineRequest::LANGUAGE_SPANISH:
                return "Nueva clave";
            default:
                return "New password";
        }
    }
    
    public static function newPasswordPlaceholder() {
        switch (CloudEngineRequest::getLanguage()) {
            case CloudEngineRequest::LANGUAGE_SPANISH:
                return "Digite nueva clave";
            default:
                return "Type new password";
        }
    }
    
    public static function confirmNewPassword() {
        switch (CloudEngineRequest::getLanguage()) {
            case CloudEngineRequest::LANGUAGE_SPANISH:
                return "Confirmar nueva clave";
            default:
                return "Confirm new password";
        }
    }
    
    public static function personalInfo() {
        switch (CloudEngineRequest::getLanguage()) {
            case CloudEngineRequest::LANGUAGE_SPANISH:
                return "1. Información personal";
            default:
                return "1. Personal";
        }
    }
    
    public static function contactInfo() {
        switch (CloudEngineRequest::getLanguage()) {
            case CloudEngineRequest::LANGUAGE_SPANISH:
                return "2. Información de contacto";
            default:
                return "2. Contact";
        }
    }
    
    public static function accessInfo() {
        switch (CloudEngineRequest::getLanguage()) {
            case CloudEngineRequest::LANGUAGE_SPANISH:
                return "3. Información de acceso";
            default:
                return "3. Access";
        }
    }
    
    public static function legalInfo() {
        switch (CloudEngineRequest::getLanguage()) {
            case CloudEngineRequest::LANGUAGE_SPANISH:
                return "4. Términos y condiciones";
            default:
                return "4. Terms and conditions";
        }
    }
    
    public static function newCustomerNamesLabel() {
        switch (CloudEngineRequest::getLanguage()) {
            case CloudEngineRequest::LANGUAGE_SPANISH:
                return "* Nombres";
            default:
                return "* Names";
        }
    }
    
    public static function newCustomerGenderLabel() {
        switch (CloudEngineRequest::getLanguage()) {
            case CloudEngineRequest::LANGUAGE_SPANISH:
                return "* Género";
            default:
                return "* Gender";
        }
    }
    
    public static function newCustomerBirthdateLabel() {
        switch (CloudEngineRequest::getLanguage()) {
            case CloudEngineRequest::LANGUAGE_SPANISH:
                return "* Fecha de nacimiento";
            default:
                return "* Birthdate";
        }
    }
    
    public static function newCustomerLanguageLabel() {
        switch (CloudEngineRequest::getLanguage()) {
            case CloudEngineRequest::LANGUAGE_SPANISH:
                return "* Idioma";
            default:
                return "* Language";
        }
    }
    
    public static function newCustomerDocumentTypeLabel() {
        switch (CloudEngineRequest::getLanguage()) {
            case CloudEngineRequest::LANGUAGE_SPANISH:
                return "Tipo de documento (opcional)";
            default:
                return "Document type (optional)";
        }
    }
    
    public static function newCustomerDocumentNumberLabel() {
        switch (CloudEngineRequest::getLanguage()) {
            case CloudEngineRequest::LANGUAGE_SPANISH:
                return "Número de documento (opcional)";
            default:
                return "Document number (optional)";
        }
    }
    
    public static function newCustomerCountryLabel() {
        switch (CloudEngineRequest::getLanguage()) {
            case CloudEngineRequest::LANGUAGE_SPANISH:
                return "* País";
            default:
                return "* Country";
        }
    }
    
    public static function newCustomerCityLabel() {
        switch (CloudEngineRequest::getLanguage()) {
            case CloudEngineRequest::LANGUAGE_SPANISH:
                return "* Ciudad";
            default:
                return "* City";
        }
    }
    
    public static function newCustomerAddressLabel() {
        switch (CloudEngineRequest::getLanguage()) {
            case CloudEngineRequest::LANGUAGE_SPANISH:
                return "* Dirección";
            default:
                return "* Address";
        }
    }
    
    public static function newCustomerPhoneLabel() {
        switch (CloudEngineRequest::getLanguage()) {
            case CloudEngineRequest::LANGUAGE_SPANISH:
                return "* Teléfono principal";
            default:
                return "* Primary phone number";
        }
    }
    
    public static function newCustomerPhone2Label() {
        switch (CloudEngineRequest::getLanguage()) {
            case CloudEngineRequest::LANGUAGE_SPANISH:
                return "Teléfono opcional";
            default:
                return "Optional phone number";
        }
    }
    
    public static function newCustomerEmailLabel() {
        switch (CloudEngineRequest::getLanguage()) {
            case CloudEngineRequest::LANGUAGE_SPANISH:
                return "* Correo electrónico";
            default:
                return "* Email";
        }
    }
    
    public static function newCustomerPasswordLabel() {
        switch (CloudEngineRequest::getLanguage()) {
            case CloudEngineRequest::LANGUAGE_SPANISH:
                return "* Clave";
            default:
                return "* Password";
        }
    }
    
    public static function newCustomerConfirmPasswordLabel() {
        switch (CloudEngineRequest::getLanguage()) {
            case CloudEngineRequest::LANGUAGE_SPANISH:
                return "* Confirmar clave";
            default:
                return "* Confirm password";
        }
    }
    
    public static function newCustomerTermsAndConditions() {
        switch (CloudEngineRequest::getLanguage()) {
            case CloudEngineRequest::LANGUAGE_SPANISH:
                return "Uniexpress Solutions es una empresa de carga internacional basada en los Estados Unidos, cuyo propósito y obligación es cumplir con las leyes de dicho país y los países hacia donde se realizan los envíos ordinarios y de tipo import/export.<br/><br/>Por la presente autorizo a Uniexpress Solutions INC. a que revise el contenido de mi envío  a su plena discreción conforme a lo dispuesto en el código de regulaciones federales CFR 49 y debido a que  Uniexpress Solutions INC es un IAC (Indirect Air Carrier) regulado por el Departamento de Seguridad de Transporte (TSA). Estoy de acuerdo en que Uniexpress Solutions INC puede rechazar mi envío a su discreción al momento o después de su aceptación si encuentra elementos tales como: artículos médicos, joyería, ropa y/o zapatos militares, pistolas o juguetes bélicos, pornografía, dinero o valores negociables,  repuestos usados, cualquier elemento contaminado con combustible, substancias estupefacientes, gases comprimidos, explosivos, materiales incendiarios, substancias inflamables y/o otra sustancia destructiva, sistemas de cámaras de seguridad, semillas, embutidos, quesos y otros productos animales no enlatados, comida para animales, artículos de hechicería.<br/><br/>Mediante la presente declaro bajo la gravedad de juramento que la descripción y los valores declarados de los artículos a transportar por  Uniexpress Solutions INC corresponden a la verdad y son objetos de comercio lícito.  Uniexpress Solutions INC. En calidad de mi agente Transportador es responsable por los artículos descritos aceptados y reconocerá  el valor declarado, flete e impuestos si hubiese pérdida total. En caso de daño del contenido acepto que se realice una investigación que puede durar hasta 30 días y cuyo resultado inapelable determinará si se reconoce el daño. Acepto que no se acepta reclamaciones en caso de pérdida parcial. ACEPTO QUE TODO RECLAMO DEBE SER PRESENTADO POR ESCRITO DENTRO DE 12 HORAS DESPUÉS DE RECIBIDA LA CARGA  Y QUE NO SE ACEPTARÁ RECLAMOS LUEGO DE ESE TIEMPO.<br/><br/>Reconozco que soy responsable del correcto empaque de los artículos que estoy enviando. Estoy de acuerdo en que  Uniexpress Solutions INC no reconocerá daños ocasionados en artículos frágiles que no vayan en su empaque original de fábrica apto para transporte, además  Uniexpress Solutions INC   no asegura vidrios, cristales, porcelanas u obras de arte. Acepto que  Uniexpress Solutions INC NO RESPONDERA POR MERCANCÍA NO DECLARADA O POR CUALQUIER ARTÍCULO DE PROHIBIDA TRANSPORTACION. Uniexpress no se responsabiliza por los tiempos de entrega extensos que puedan ocurrir debido a cierres en la vía, inspecciones aduaneras, y cualquier situación ajena al transportador. Acepto que  La Aduana  del país de destino puede revaluar los impuestos a pagar o retener mi envío si encuentra discrepancias entre lo declarado y el contenido físico o diferencias entre el valor declarado y el valor del mercado. Acepto que los envíos en cantidades comerciales o mal declaradas pueden dar retención aduanera sin derecho a devolución o con cambio de modalidad. En caso de retención autorizo a  Uniexpress Solutions INC  a que presente ante la Aduana los documentos de exportación.  Uniexpress Solutions INC   no es responsable por el tiempo que tome este proceso ante la Aduana ni por la decisión final emitida. Libero de toda responsabilidad a  Uniexpress Solutions INC   en caso de demora en la entrega de mi envío debido a ATRASOS EN ADUANAS, DIAS FESTIVOS, HUELGAS, DAÑOS EN CARRETERAS Y  EVENTOS DE FUERZA MAYOR.<br/></br><b>RESTRICCIONES:</b> Ley 1369/2.009 Resolución 3095/11 CRC Código de comercio (artículo 981 – contrato de transporte y siguientes) POR SU NATURALEZA Objetos que por su contenido puedan ocasionar daños a los colaboradores o deteriorar los demás envíos. Materiales peligrosos, contaminantes, inflamables o explosivos, combustibles. Bienes que deban conservarse bajo refrigeración, congelación o calefacción y su conservación sea superior a 24 horas. Gases comprimidos o venenosos,cilindros de gas,refrigerantes, material radioactivo, sustancias infecciosas, corrosivas o venenosas, materias grasas, polvos colorantes y otras materias similares, desechos orgánicos y hospitalarios, oxidantes, peróxidos orgánicos e industriales, catalizadores, pigmentos químicos, plásticos,pinturas. POR SU VALOR Metales preciosos en barra o en polvo, dinero en efectivo y otros objetos de valor, tales como monedas, platino, oro y plata, manufacturadas o no, billetes representativos de moneda o cualquier otro valor al portador, joyas, piedras finas o cualquier otro objetos precioso, objetos constitutivos del patrimonio histórico o cultural de Colombia. Antigüedades, obras de arte, objetos artísticos. POR PROHIBICIONES LEGAL Material orgánico, plantas, opio, marihuana, cocaína, morfina, heroína o cualquier otro tipo de narcóticos o alucinógenos, excepto los envíos con fines médicos o científicos, químicos peligrosos, materiales industriales óxidos (imo), inflamables y combustibles, Animales, Armas, municiones y elementos bélicos de toda especie, o cualquier otro objeto de comercio ilícito. Máquinas para acuñar moneda, o esqueletos para billetes de bancos. POR CONDICIONES DE EMPAQUE Y EMBALAJE Artículos y elementos frágiles que no estén convenientemente protegidos y empacados. No deben recibirse envíos que tengan abierto su empaque o que vengan en mal estado. POR CONDICIONES DE MANIPULACIÓN Perfiles en aluminio, madera, hierro o plástico, tuberías en cualquier tipo de material. Maquinaria industrial cuyo peso sea superior a 150 kg. Lozas de mármol, pedernales, porcelanas, baldosas y sanitarios.<br/><br/>Envios a Ecuador, Colombia, Panamá y Venezuela:<br/><br/>Uniexpress Solutions INC no se responsabiliza por cualquier pérdida o daño que la mercancía pueda llegar a sufrir. En caso de pérdida total de la caja, Uniexpress Solutions tendrá la obligación de hacer una devolución de $100 dolares en efectivo o en carga.<br/><br/>Uniexpress Solutions no se responsabiliza por cualquier inconveniente aduanero que la caja tenga desde el momento en que es Embarcada a Colombia, Ecuador, Panama y/o Venezuela. Bajo ninguna circunstancia Uniexpress es responsable por la pérdida o daño de cualquier tipo de electrónicos; esto incluye computadores, cámaras de seguridad, televisores, monitores, entre otros.";
            default:
                return "I here by authorize  Uniexpress Solutions to review the contents of my shipment at its full discretion in accordance with the provisions of the Code of Federal Regulations CFR 49 and because  Uniexpress Solutions is an IAC (Indirect Air Carrier) regulated by the Department. of Transportation Security (TSA). I agree that Uniexpress Solutions may reject my shipment at its discretion at the time or after its acceptance if it finds items such as: medical items, jewelry, clothing and / or military shoes, guns or toys, pornography, money or negotiable securities, used parts, any element contaminated with fuel, narcotic substances, compressed gases, explosives, incendiary materials, flammable substances and / or other destructive substance, security camera systems, seeds, sausages, cheeses and other non-canned animal products, animal feed , items of sorcery. By means of the present I declare under the seriousness of oath that the description and the declared values of the articles to be transported by Uniexpress Solutions   correspond to the truth and are objects of licit commerce.  Uniexpress Solutions  . acting as my agent Transporter is responsible for the articles described and will accept the declared value, freight and taxes if there is total loss. In case of damage to the content, I accept that an investigation is carried out that can last up to 30 days and whose unappealable result will determine if the damage is recognized. I accept that no claims are accepted in case of partial loss. I ACCEPT THAT EVERY CLAIM SHOULD BE SUBMITTED IN WRITING WITHIN 72 HOURS AFTER RECEIVING THE LOAD AND THAT CLAIMS WILL NOT BE ACCEPTED AFTER THAT TIME. I acknowledge that I am responsible for the correct packaging of the items I am sending. I agree that  Uniexpress Solutions  will not recognize damages caused by fragile items that do not go in their original factory packaging suitable for transport, and  Uniexpress Solutions does not insure glass, glass, porcelain or works of art. I accept that  Uniexpress Solutions   WILL NOT ANSWER FOR UNCONSCIOUS MERCHANDISE OR FOR ANY ARTICLE OF PROHIBITED TRANSPORTATION. Uniexpress is not responsible for any delays in delivery times due to road closures, customs inspections and any other situation beyond the control of the shipper. I accept that Customs of the country of destination can revalue the taxes to pay or retain my shipment if it finds discrepancies between the declaration and the physical content or differences between the declared value and the market value. I accept that the shipments in commercial quantities or badly declared can give customs retention without right of return or with change of modality. In case of retention, I authorize Uniexpress Solutions   to present the export documents to Customs.  Uniexpress Solutions   is not responsible for the time that this process takes before Customs or for the final decision issued. I release  Uniexpress Solutions   from any responsibility in case of delay in the delivery of my shipment due to CUSTOMS ARRANGEMENTS, HOLIDAYS, STRIKES, ROAD DAMAGE AND FORCE MAJEURE EVENTS.<br/><br/><b>RESTRICTIONS:</b> Law 1369 / 2.009 Resolution 3095/11 CRC Commercial Code (article 981 - contract of transport and following) BY HIS NATURE Objects that by their content can cause damages to the collaborators or deteriorate the other shipments. Hazardous, polluting, flammable or explosive, combustible materials. Goods that must be kept under refrigeration, freezing or heating and their conservation is greater than 24 hours. Compressed or poisonous gases, gas cylinders, refrigerants, radioactive material, infectious, corrosive or poisonous substances, fats, dyes and other similar materials, organic and hospital waste, oxidants, organic and industrial peroxides, catalysts, chemical pigments, plastics, paintings FOR ITS VALUE Precious metals in bar or powder, cash and other valuables, such as coins, platinum, gold and silver, manufactured or not, bills representing currency or any other bearer value, jewelry, fine stones or any other precious objects, constituent objects of the historical or cultural heritage of Colombia. Antiques, works of art, artistic objects. FOR PROHIBITIONS LEGAL Organic material, plants, opium, marijuana, cocaine, morphine, heroin or any other type of narcotic or hallucinogenic, except shipments for medical or scientific purposes, hazardous chemicals, industrial materials oxides (imo), flammable and combustible, Animals , Weapons, ammunition and warlike elements of any kind, or any other object of illicit trade. Machines for coining money, or skeletons for banknotes. BY CONDITIONS OF PACKAGING AND PACKAGING Fragile items and items that are not properly protected and packaged. You should not receive shipments that have their packaging open or that come in bad condition. BY CONDITIONS OF HANDLING Profiles in aluminum, wood, iron or plastic, pipes in any type of material. Industrial machinery whose weight exceeds 150 kg. Marble slabs, flints, porcelain, tiles and toilets.<br/><br/>Shipments to Colombia, Ecuador, Panama And/Or Venezuela:<br/><br/>Uniexpress Solutions INC is not responsible to any lost or damage that any shipped box may suffer. If package is lost or stolen, Uniexpress Solutions will immediately reimburse $100 USD. Uniexpress Solutions is not responsible if anything happens with customs directly. We will make sure we assist you in the most professional and effective way to retrieve merchandise. Under no circumstances is Uniexpress responsible for the loss or damage of any type of electronics; this includes computers, security cameras, televisions, monitors and any type of electronics.";
        }
    }
    
    public static function newCustomerTermsAndConditionsLabel() {
        switch (CloudEngineRequest::getLanguage()) {
            case CloudEngineRequest::LANGUAGE_SPANISH:
                return "* ¿Acepta nuestros términos y condiciones de uso?";
            default:
                return "* ¿Do you accept our terms and conditions?";
        }
    }
    
    public static function lockerSuccessfullyCreatedLabel() {
        switch (CloudEngineRequest::getLanguage()) {
            case CloudEngineRequest::LANGUAGE_SPANISH:
                return "CUENTA CREADA EXITOSAMENTE";
            default:
                return "ACCOUNT CREATED SUCCESSFULLY";
        }
    }
    
    public static function lockerSuccessfullyCreatedBodyLabel() {
        switch (CloudEngineRequest::getLanguage()) {
            case CloudEngineRequest::LANGUAGE_SPANISH:
                return "Hemos enviado las instrucciones de activación a tu correo electrónico.";
            default:
                return "An email confirmation has been sent to your email address. Please check your email to activate your account.";
        }
    }
    
    public static function homeButton() {
        switch (CloudEngineRequest::getLanguage()) {
            case CloudEngineRequest::LANGUAGE_SPANISH:
                return "IR A INICIO";
            default:
                return "GO TO HOMEPAGE";
        }
    }
    
    public static function accountSuccessfullyActivatedLabel() {
        switch (CloudEngineRequest::getLanguage()) {
            case CloudEngineRequest::LANGUAGE_SPANISH:
                return "CUENTA ACTIVADA EXITOSAMENTE";
            default:
                    return "ACCOUNT ACTIVATED SUCCESSFULLY";
        }
    }
    
    public static function accountSuccessfullyActivatedBodyLabel() {
        switch (CloudEngineRequest::getLanguage()) {
            case CloudEngineRequest::LANGUAGE_SPANISH:
                return "Tu cuenta fue activada exitosamente, ya puedes disfrutar de todos nuestros servicios.";
            default:
                return "Your account was activated successfully, you can now enjoy all our services.";
        }
    }
    
    public static function trackingTitle() {
        switch (CloudEngineRequest::getLanguage()) {
            case CloudEngineRequest::LANGUAGE_SPANISH:
                return "Rastreo de envíos";
            default:
                return "Shipment tracking";
        }
    }
    
    public static function createdTimestamp() {
        switch (CloudEngineRequest::getLanguage()) {
            case CloudEngineRequest::LANGUAGE_SPANISH:
                return "Fecha registrado";
            default:
                return "Created date";
        }
    }
    
    public static function weight() {
        switch (CloudEngineRequest::getLanguage()) {
            case CloudEngineRequest::LANGUAGE_SPANISH:
                return "Peso";
            default:
                return "Weight";
        }
    }
    
    public static function customer() {
        switch (CloudEngineRequest::getLanguage()) {
            case CloudEngineRequest::LANGUAGE_SPANISH:
                return "Cliente";
            default:
                return "Customer";
        }
    }
    
    public static function country() {
        switch (CloudEngineRequest::getLanguage()) {
            case CloudEngineRequest::LANGUAGE_SPANISH:
                return "País";
            default:
                return "Country";
        }
    }
    
    public static function city() {
        switch (CloudEngineRequest::getLanguage()) {
            case CloudEngineRequest::LANGUAGE_SPANISH:
                return "Ciudad";
            default:
                return "City";
        }
    }
    
    public static function movement() {
        switch (CloudEngineRequest::getLanguage()) {
            case CloudEngineRequest::LANGUAGE_SPANISH:
                return "Movimiento";
            default:
                return "Movement";
        }
    }
    
    public static function timestamp() {
        switch (CloudEngineRequest::getLanguage()) {
            case CloudEngineRequest::LANGUAGE_SPANISH:
                return "Marca de tiempo";
            default:
                return "Timestamp";
        }
    }
    
    public static function boxNumber() {
        switch (CloudEngineRequest::getLanguage()) {
            case CloudEngineRequest::LANGUAGE_SPANISH:
                return "Caja No.";
            default:
                return "Box No.";
        }
    }

}
