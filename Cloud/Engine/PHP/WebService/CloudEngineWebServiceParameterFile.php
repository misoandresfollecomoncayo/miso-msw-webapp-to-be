<?php

namespace Cloud\Engine\PHP\WebService;

class CloudEngineWebServiceParameterFile extends CloudEngineWebServiceParameter {
    
    public function __construct($pName, $pLength, $pRequired) {
        parent::__construct($pName, CloudEngineWebServiceParameter::TYPE_FILE, $pLength, $pRequired);
    }
    
}
