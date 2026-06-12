<?php

namespace Cloud\Engine\PHP\WebService;

class CloudEngineWebServiceParameterDate extends CloudEngineWebServiceParameter {
    
    public function __construct($pName, $pRequired) {
        parent::__construct($pName, CloudEngineWebServiceParameter::TYPE_DATE, 10, $pRequired);
    }
    
}