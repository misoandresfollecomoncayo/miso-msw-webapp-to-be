<?php

namespace Cloud\Engine\PHP\WebService;

class CloudEngineWebServiceParameterBoolean extends CloudEngineWebServiceParameter {
    
    public function __construct($name, $required) {
        parent::__construct($name, CloudEngineWebServiceParameter::TYPE_BOOLEAN, 5, $required);
    }
    
}