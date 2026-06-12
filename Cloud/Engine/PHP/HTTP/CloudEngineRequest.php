<?php

namespace Cloud\Engine\PHP\HTTP;

class CloudEngineRequest {
    
    const LANGUAGE_SPANISH = "es";
    
    const LANGUAGE_ENGLISH = "en";
    
    public static function getLanguage() {
        $language = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
        return $language;
    }
    
}
