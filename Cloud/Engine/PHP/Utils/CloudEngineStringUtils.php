<?php

namespace Cloud\Engine\PHP\Utils;

class CloudEngineStringUtils {
    
    public static function timestampToHumanFormat($timestamp) {
        $dt = new \DateTime($timestamp);
        return $dt->format("d M Y - h:i a");
    }
    
    public static function timestampToShortHumanFormat($timestamp) {
        $dt = new \DateTime($timestamp);
        return $dt->format("d M Y");
    }
    
    public static function randomString($length) {
        $chars = "abcdefghijklmnopqrstuvwxyz0123456789";
        $string = "";
        
        for ($i=0; $i<$length; $i++) {
            $string .= $chars[rand(0, strlen($chars) - 1)];
        }
        
        return $string;
    }
    
}
