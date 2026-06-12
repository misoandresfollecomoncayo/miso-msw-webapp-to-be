<?php

namespace Cloud\Engine\PHP\Design;

class CloudEngineHTMLObject {
   
    private $properties;
    
    public function __construct() {
        $this->properties = array();
    }
    
    public function addPropertie($key, $value) {
        array_push($this->properties, new CloudEngineHTMLObjectPropertie($key, $value));
    }
    
    public function getProperties() {
        return $this->properties;
    }
    
    public function toString() {
        $code = "";
        
        foreach ($this->properties as $p) {
            $code .= $p->getKey() . '="' . $p->getValue() . '" ';
        }
        
        return $code;
    }
    
}

class CloudEngineHTMLObjectPropertie {
    
    private $key;
    
    private $value;
    
    public function __construct($key, $value) {
        $this->key = $key;
        $this->value = $value;
    }
    
    public function getKey() {
        return $this->key;
    }

    public function getValue() {
        return $this->value;
    }

}