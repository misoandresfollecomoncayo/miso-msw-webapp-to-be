<?php
    require_once $_SERVER["DOCUMENT_ROOT"] . '/UniexpressAutoload.php';
    
    use Cloud\Engine\PHP\HTTP\CloudEngineRequest;
    
    if (CloudEngineRequest::getLanguage() == CloudEngineRequest::LANGUAGE_SPANISH) {
        header("location:ES/");
    } else {
        header("location:EN/");
    }
