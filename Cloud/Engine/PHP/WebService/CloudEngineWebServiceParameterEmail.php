<?php

namespace Cloud\Engine\PHP\WebService;

class CloudEngineWebServiceParameterEmail extends CloudEngineWebServiceParameter {
    
    public function __construct($name, $required) {
        parent::__construct($name, CloudEngineWebServiceParameter::TYPE_EMAIL, 320, $required);
    }
    
}