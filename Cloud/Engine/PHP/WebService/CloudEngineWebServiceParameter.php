<?php

namespace Cloud\Engine\PHP\WebService;

use Cloud\Engine\PHP\HTTP\CloudEngineRequest;

class CloudEngineWebServiceParameter {
    
    // TODO: Implement data types validations
    const TYPE_BOOLEAN = "BOOLEAN";
    const TYPE_DATE = "DATE";
    const TYPE_DECIMAL = "DECIMAL";
    const TYPE_ENUM = "ENUM";
    const TYPE_INTEGER = "INTEGER";
    const TYPE_LETTERS = "LETTERS";
    const TYPE_TEXT = "TEXT";
    const TYPE_TIME = "TIME";
    const TYPE_EMAIL = "EMAIL";
    const TYPE_FILE = "FILE";
    
    private $name;
    private $value;
    private $type;
    private $length;
    private $required;
    private $exception;
    
    public function __construct($pName, $pType, $pLength, $pRequired) {
        $this->name = $pName;
        $this->value = isset($_REQUEST[$pName]) ? trim($_REQUEST[$pName]) : null;
        $this->type = $pType;
        $this->length = $pLength;
        $this->required = $pRequired;
    }
    
    public function isValid() {
        // Validate required parameter
        if ($this->required && ($this->value === null || $this->value === "")) {
            if (CloudEngineRequest::getLanguage() == CloudEngineRequest::LANGUAGE_SPANISH) {
                $this->exception = $this->name . " es requerido.";
            } else {
                $this->exception = $this->name . " is required.";
            }
            return false;
        }
        
        // Validate length
        if (strlen($this->value) > $this->length) {
            if (CloudEngineRequest::getLanguage() == CloudEngineRequest::LANGUAGE_SPANISH) {
                $this->exception = $this->name . " debe tener hasta " . $this->length . " caracteres.";
            } else {
                $this->exception = $this->name . " must have up " . $this->length . " characters.";
            }
            return false;
        }
        
        // Validate integer parameter
        if ($this->type == CloudEngineWebServiceParameter::TYPE_INTEGER &&
            !preg_match('/^\d+$/', $this->value)) {
            if (CloudEngineRequest::getLanguage() == CloudEngineRequest::LANGUAGE_SPANISH) {
                $this->exception = $this->name . " debe ser un número entero.";
            } else {
                $this->exception = $this->name . " must be a integer number.";
            }
            return false;
        }
        
        // Validate boolean parameter
        if ($this->type == CloudEngineWebServiceParameter::TYPE_BOOLEAN
            && $this->value != 1 && $this->value != 0 && $this->value != "false" && $this->value != "true") {
            if (CloudEngineRequest::getLanguage() == CloudEngineRequest::LANGUAGE_SPANISH) {
                $this->exception = $this->name . " debe ser un valor booleano";
            } else {
                $this->exception = $this->name . " must be a boolean value.";
            }
            return false;
        }
        
        // Validate date parameter
        $year = substr($this->value, 0, 4);
        $month = substr($this->value, 5, 2);
        $day = substr($this->value, 8, 2);
        if ($this->type == CloudEngineWebServiceParameter::TYPE_DATE &&
            (!is_numeric($year) || !is_numeric($month) || !is_numeric($day))) {
            if (CloudEngineRequest::getLanguage() == CloudEngineRequest::LANGUAGE_SPANISH) {
                $this->exception = $this->name . " debe ser una fecha en formato aaaa-mm-dd";
            } else {
                $this->exception = $this->name . " must be a date value in yyyy-mm-dd format.";
            }
            return false;
        }
        
        // Validate email parameter
        if ($this->type == CloudEngineWebServiceParameter::TYPE_EMAIL &&
            !filter_var($this->value, FILTER_VALIDATE_EMAIL)) {
            if (CloudEngineRequest::getLanguage() == CloudEngineRequest::LANGUAGE_SPANISH) {
                $this->exception = $this->name . " debe ser un correo electrónico válido";
            } else {
                $this->exception = $this->name . " must be a valid email address.";
            }
            return false;
        }
        
        return true;
    }
    
    public function getException() {
        return $this->exception;
    }
    
    public function getName() {
        return $this->name;
    }
    
    public function getValue() {
        return $this->value;
    }
    
}
