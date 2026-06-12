<?php

require_once $_SERVER["DOCUMENT_ROOT"] . '/UniexpressAutoload.php';
require_once "../../Libs/PHPMailer/PHPMailerAutoload.php";

class EmailEngine {
    
    public static function getInstance() {
        $mail = new PHPMailer(true);
        $mail->CharSet = "UTF-8";
        $mail->isHTML(true);
        $mail->isSMTP();
        $mail->Host = SMTP_HOST;
        $mail->SMTPAuth = true;
        $mail->Username = SMTP_USER;
        $mail->Password = SMTP_PASSWORD;
        $mail->Port = SMTP_PORT;
        $mail->SMTPSecure = "tls";
        $mail->From = "no.reply.uniexpresssolutions@gmail.com";
        $mail->FromName = "Uniexpress Solutions Inc.";
        return $mail;
    }
    
    public static function getSign($lang = "ES") {
        switch ($lang) {
            case "ES":
                return "<br/><br/><br/>Atentamente,<br/><br/>Equipo Uniexpress Solutions.";
            default:
                return "<br/><br/><br/>Warm regards,<br/><br/>Uniexpress Solutions Team.";
        }
    }
    
    public static function customerWelcome($customerId, $token) {
        $customer = CustomerDAO::getCustomerById($customerId);
        
        $instance = EmailEngine::getInstance();
        $instance->addAddress($customer->getEmail());
        
        if ($customer->getLanguage() == Customer::LANGUAGE_SPANISH) {
            $instance->Subject = "Bienvenid@ a Uniexpress Solutions";
            $instance->Body = "Hola " . $customer->getNames() . ",<br/><br/>";
            $instance->Body .= "Recibimos exitosamente tu solicitud de registro en nuestra plataforma y creamos para ti el casillero <b>número " . $customer->getLockerNumber() . "</b>, solo debes hacer <a href='" . PUBLIC_PATH_PLATFORM . "Customer/Active.php?Token=" . $token . "'>clic aquí</a> para activar tu cuenta.<br/><br/>";
            $instance->Body .= "Si el link anterior no funciona, por favor copia y pega en tu navegador la siguiente dirección: " . PUBLIC_PATH_PLATFORM . "Customer/Active.php?Token=" . $token . "<br/><br/>";
            $instance->Body .= "Gracias por elegir nuestros servicios.";
            $instance->Body .= EmailEngine::getSign();
        } else {
            $instance->Subject = "Welcome to Uniexpress Solutions";
            $instance->Body = "Hi " . $customer->getNames() . ",<br/><br/>";
            $instance->Body .= "We have successfully received your registration request in our platform and we have created the account number <b>" . $customer->getLockerNumber() . "</b> for you.<br/><br/> To activate your account, <a href='" . PUBLIC_PATH_PLATFORM . "Customer/Active.php?Token=" . $token . "'>please click here.</a><br/><br/>";
            $instance->Body .= "If the link above does not work, please copy and paste the following address on your preferred browser: " . PUBLIC_PATH_PLATFORM . "Customer/Active.php?Token=" . $token . "<br/><br/>";
            $instance->Body .= "Thank you for choosing us!<br/>We look forward to assisting you.";
            $instance->Body .= EmailEngine::getSign("EN");
        }
        
        $instance2 = EmailEngine::getInstance();
        $instance2->addAddress("santiago@uniexpresssolutions.com");
        $instance2->Subject = "Nuevo casillero";
        $instance2->Body = "Hola Santiago,<br/><br/>";
        $instance2->Body .= "Se creó un nuevo casillero en la plataforma.<br/><br/>";
        $instance2->Body .= "Nombres: " . $customer->getNames() . "<br/>";
        $instance2->Body .= "Correo electrónico: " . $customer->getEmail() . "<br/>";
        $instance2->Body .= "País: " . $customer->getCity()->getCountry()->getName() . "<br/>";
        
        if ($instance->send() && $instance2->send()) {
            return "Email delivery ok.";
        } else {
            throw new Exception("Email delivery error.");
        }
    }
    
    public static function customerFromAdministratorWelcome($customerId, $password) {
        $customer = CustomerDAO::getCustomerById($customerId);
        
        $instance = EmailEngine::getInstance();
        $instance->addAddress($customer->getEmail());
        if ($customer->getLanguage() == Customer::LANGUAGE_SPANISH) {
            $instance->Subject = "Bienvenid@ a Uniexpress Solutions";
            $instance->Body = "Hola " . $customer->getNames() . ",<br/><br/>";
            $instance->Body .= "Hemos creamos para ti el casillero <b>número " . $customer->getLockerNumber() . "</b>.<br/><br/>";
            $instance->Body .= "Puedes ingresar a nuestra plataforma con los siguientes datos:<br/><br/>";
            $instance->Body .= "<b>Acceso: </b>" . PUBLIC_PATH_PLATFORM . "<br/>";
            $instance->Body .= "<b>Usuario: </b>" . $customer->getEmail() . "<br/>";
            $instance->Body .= "<b>Clave: </b>" . $password . "<br/><br/>";
            $instance->Body .= "Gracias por elegir nuestros servicios.";
            $instance->Body .= EmailEngine::getSign();
        } else {
            $instance->Subject = "Welcome to Uniexpress Solutions";
            $instance->Body = "Hi " . $customer->getNames() . ",<br/><br/>";
            $instance->Body .= "We have created for you the <b>locker number " . $customer->getLockerNumber() . "</b>.<br/><br/>";
            $instance->Body .= "You can login your profile with the following credentials:<br/><br/>";
            $instance->Body .= "<b>URL: </b>" . PUBLIC_PATH_PLATFORM . "<br/>";
            $instance->Body .= "<b>User: </b>" . $customer->getEmail() . "<br/>";
            $instance->Body .= "<b>Password: </b>" . $password . "<br/><br/>";
            $instance->Body .= "Thank you for choosing our services.";
            $instance->Body .= EmailEngine::getSign("EN");
        }
        
        if ($instance->send()) {
            return "Email delivery ok.";
        } else {
            throw new Exception("Email delivery error.");
        }
    }
    
    public static function passwordRecovery($idToken) {
        $token = TokenDAO::getTokenById($idToken);
	
        $instance = EmailEngine::getInstance();
        $instance->addAddress($token->getObject()->getEmail());
        
        if ($token->getType() == Token::TYPE_CUSTOMER &&
            $token->getObject()->getLanguage() == Customer::LANGUAGE_ENGLISH) {
            $instance->Subject = "Password recovery";
            $instance->Body = "Hi,<br/><br/>";
            $instance->Body .= "We received a request to reset your password, <a href='" . PUBLIC_PATH_PLATFORM . "Customer/PasswordRecovery.php?Token=" . $idToken . "'>click here</a> to enter a new one.<br/><br/>";
            $instance->Body .= "If the above link does not work, please copy and paste in your browser the following address: " . PUBLIC_PATH_PLATFORM . "Customer/PasswordRecovery.php?Token=" . $idToken;
            $instance->Body .= EmailEngine::getSign("EN");
        } else {
            $instance->Subject = "Recuperar clave";
            $instance->Body = "Hola,<br/><br/>";
            $instance->Body .= "Recibimos una solicitud para restablecer tu clave, haz <a href='" . PUBLIC_PATH_PLATFORM . "Customer/PasswordRecovery.php?Token=" . $idToken . "'>clic aquí</a> para ingresar una nueva.<br/><br/>";
            $instance->Body .= "Si el link anterior no funciona, por favor copia y pega en tu navegador la siguiente dirección: " . PUBLIC_PATH_PLATFORM . "Customer/PasswordRecovery.php?Token=" . $idToken;
            $instance->Body .= EmailEngine::getSign();
        }
        
        if ($instance->send()) {
            return "Email delivery ok.";
        } else {
            throw new Exception("Email delivery error.");
        }

	
    }
    
    public static function customerContactMessage($customer, $message) {
        $instance = EmailEngine::getInstance();
        $instance->addAddress("info@uniexpresssolutions.com");
        
        $instance->Subject = "Contacto casillero No. " . $customer->getLockerNumber();
        $instance->Body = "<b>Cliente:</b> " . $customer->getNames() . "<br/>";
        $instance->Body .= "<b>Casillero:</b> " . $customer->getLockerNumber() . "<br/>";
        $instance->Body .= "<b>Mensaje:</b> " . $message . "<br/>";
                
        if ($instance->send()) {
            return "Email delivery ok.";
        } else {
            throw new Exception("Email delivery error.");
        }
    }
    
    public static function requestShipment($systemUser, $body) {
        $instance = EmailEngine::getInstance();
        //$instance->addAddress("andresfollecomoncayo@gmail.com");
	$instance->addAddress("info@uniexpresssolutions.com");
        
        $instance->Subject = "Solicitud de envío";
        $instance->Body = $body;
                
        if ($instance->send()) {
            return "Email delivery ok.";
        } else {
            throw new Exception("Email delivery error.");
        }
    }
    
    public static function arrivalAlert($systemUser, $body) {
        $instance = EmailEngine::getInstance();
        $instance->addAddress($systemUser->getEmail());
        
        $instance->Subject = "Alerta de llegada";
        $instance->Body = $body;
                
        if ($instance->send()) {
            return "Email delivery ok.";
        } else {
            throw new Exception("Email delivery error.");
        }
    }
    
    public static function electronicPayment($entityType, $idEntity, $amount) {
        $instance = EmailEngine::getInstance();
        
        //$instance->addAddress("andresfolleco@quantumsoft.co");
        $instance->addAddress("maria@uniexpresssolutions.com");
        $instance->addAddress("santiago@uniexpresssolutions.com");
        
        if ($entityType == "BILL") {
            $bill = BillDAO::getBillById($idEntity);
            
            $instance->Subject = "Pago electrónico: " . $bill->getFrom() . " (" . $bill->getBillNumber() . ")";
            
            $body = "Se registró un pago electrónico.<br/><br/>";
            $body .= "<b>Cliente:</b> " . $bill->getFrom() . "<br/>";
            $body .= "<b>Factura:</b> " . $bill->getBillNumber() . "<br/>";
            $body .= "<b>Monto:</b> $ " . number_format($amount,2) . " " . $bill->getCurrency() . "<br/>";
        } else {
            $shipment = ShippingDAO::getShippingById($idEntity);
            
            $instance->Subject = "Pago electrónico: " . $shipment->getPurchases()[0]->getCustomer()->getNames() . " (" . $shipment->getShippingNumber() . ")";
            
            $body = "Se registró un pago electrónico.<br/><br/>";
            $body .= "<b>Cliente:</b> " . $shipment->getPurchases()[0]->getCustomer()->getNames() . "<br/>";
            $body .= "<b>Factura:</b> " . $shipment->getShippingNumber() . "<br/>";
            $body .= "<b>Monto:</b> $ " . number_format($amount,2) . " " . $shipment->getCurrency() . "<br/>";
        }
        
        $instance->Body = $body;
        
        if ($instance->send()) {
            return "Email delivery ok.";
        } else {
            throw new Exception("Email delivery error.");
        }
    }
    
}
