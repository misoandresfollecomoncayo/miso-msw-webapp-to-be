<?php

namespace Cloud\Engine\PHP\Utils;

class CloudEngineStrings {
    
    const RANDOM_STRING_TYPE_ALL = "ALL";
    const RANDOM_STRING_TYPE_CHARS = "CHARS";
    const RANDOM_STRING_TYPE_NUMERIC = "NUMERIC";
    
    public static function timestampToHumanFormat($timestamp) {
        $dt = new \DateTime($timestamp);
        return $dt->format("d M Y - h:i a");
    }
    
    public static function timestampToShortHumanFormat($timestamp) {
        $dt = new \DateTime($timestamp);
        return $dt->format("d M Y");
    }
    
    public static function randomString($length, $type = "ALL") {
        $all = "abcdefghijklmnopqrstuvwxyz0123456789";
        $chars = "abcdefghijklmnopqrstuvwxyz";
        $numbers = "0123456789";
        $string = "";
        
        for ($i=0; $i<$length; $i++) {
            if ($type == CloudEngineStrings::RANDOM_STRING_TYPE_ALL) {
                $string .= $all[rand(0, strlen($all) - 1)];
            } else if ($type == CloudEngineStrings::RANDOM_STRING_TYPE_CHARS) {
                $string .= $chars[rand(0, strlen($chars) - 1)];
            } else {
                $string .= $numbers[rand(0, strlen($numbers) - 1)];
            }
        }
        
        return $string;
    }
    
    public static function stringToBool($value) {
        if ($value == "true") {
            return true;
        } else if ($value == "false") {
            return false;
        } else {
            return null;
        }
    }
    
}
