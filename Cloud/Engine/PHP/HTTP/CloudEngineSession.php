<?php

namespace Cloud\Engine\PHP\HTTP;

class CloudEngineSession {
    
    public static function start($object) {
        if (!isset($_SESSION)) {
            session_start();
        }
        $_SESSION["CLOUD_ENGINE_SESSION_OBJECT"] = serialize($object);
    }

    public static function getSessionObject() {
        if (!isset($_SESSION)) {
            session_start();
        }
        return isset($_SESSION["CLOUD_ENGINE_SESSION_OBJECT"]) ? unserialize($_SESSION["CLOUD_ENGINE_SESSION_OBJECT"]) : null;
    }

    public static function destroy() {
        if (!isset($_SESSION)) {
            session_start();
        }
        session_destroy();
    }
    
}
