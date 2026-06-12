<?php

namespace Cloud\Engine\PHP\HTTP;

use Exception;

class CloudEngineHTTP {
    
    public static function getPostVar($pName, $pThrowException = false) {
        if (isset($_POST[$pName])) {
            return $_POST[$pName];
        } else {
            if ($pThrowException) {
                throw new Exception("POST '" . $pName . "' is not set");
            } else {
                return null;
            }
        }
    }
    
    public static function getGetVar($name, $throwException = false) {
        if (isset($_GET[$name])) {
            return $_GET[$name];
        } else {
            if ($throwException) {
                throw new Exception("GET '" . $name . "' is not set");
            } else {
                return null;
            }
        }
    }
    
}