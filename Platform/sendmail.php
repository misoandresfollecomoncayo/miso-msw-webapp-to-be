<?php

require_once $_SERVER["DOCUMENT_ROOT"] . '/UniexpressAutoload.php';
require_once "../Libs/PHPMailer/PHPMailerAutoload.php";

try {
	$instance = EmailEngine::getInstance();
	$instance->addAddress("andresfollecomoncayo@gmail.com");
	$instance->Subject = "Nuevo casillero";
	$instance->Body .= "Mensaje de prueba.";
	$instance->send();
} catch (Exception $e) {
	print_r($e);
}
