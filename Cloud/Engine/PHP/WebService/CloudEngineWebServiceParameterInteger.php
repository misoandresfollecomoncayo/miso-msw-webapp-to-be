<?php

namespace Cloud\Engine\PHP\WebService;

class CloudEngineWebServiceParameterInteger extends CloudEngineWebServiceParameter {
    
    public function __construct($name, $length, $required) {
        parent::__construct($name, CloudEngineWebServiceParameter::TYPE_INTEGER, $length, $required);
    }
    
}