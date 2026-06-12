<?php

ini_set("memory_limit","2048M");
set_time_limit(600);

// Constants

define("DATABASE_SERVER", "127.0.0.1:3306");

define("DATABASE_NAME", "");

define("DATABASE_USER", "");

define("DATABASE_PASSWORD", '');

define("PUBLIC_PATH_STATIC", "");

define("PUBLIC_PATH_PLATFORM", "");

define("PRIVATE_PATH_UPLOADS", "");

define("SMTP_HOST", "smtp.sendgrid.net");

define("SMTP_PORT", 587);

define("SMTP_USER", "apikey");

define("SMTP_PASSWORD", "");

define("GOOGLE_RECAPTCHA_PRIVATE_KEY", "");

// Dependencies paths

$paths = array(
    "CLOUD_ENGINE_PHP" => "/home/Sites/quantumsoft.co/",
    "MODELS" => "/home/Sites/uniexpresssolutions.com/Models/",
    "DAO" => "/home/Sites/uniexpresssolutions.com/DAO/"
);

// Show all errors

error_reporting(E_ERROR);

spl_autoload_register(function ($file) {
    global $paths;
    $fileNormalized = str_replace("\\", "/", $file);
    
    foreach ($paths as $key => $path) {
        $fullPath = $path . $fileNormalized . ".php";
        
        if (file_exists($fullPath)) {
            require_once $fullPath;
        }
    }
});
