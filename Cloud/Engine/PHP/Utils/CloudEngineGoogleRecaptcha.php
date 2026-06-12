<?php

namespace Cloud\Engine\PHP\Utils;

class CloudEngineGoogleRecaptcha {
    
    public static function isValid($response, $privateKey) {
        $url = 'https://www.google.com/recaptcha/api/siteverify';
        $data = array(
            'secret' => $privateKey,
            'response' => $response);
        $options = array(
            'http' => array(
                'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
                'method'  => 'POST',
                'content' => http_build_query($data)
            )
        );
        $context  = stream_context_create($options);
        $response = json_decode(file_get_contents($url, false, $context), true);
        if (!$response["success"]) {
            return false;
        }

        return true;
    }
    
}
