<?php

namespace Cloud\Engine\PHP\WebService;

class CloudEngineWebServiceParameterText extends CloudEngineWebServiceParameter {
    
    public function __construct($pName, $length, $pRequired) {
        parent::__construct($pName, CloudEngineWebServiceParameter::TYPE_TEXT, $length, $pRequired);
    }
    
}
