<?php

namespace Cloud\Engine\PHP\MySQL;

class CloudEngineMySQLObject {
    
    var $attributes;

    public function __construct($attributes) {
        $this->attributes = $attributes;
    }
    
    public function getAttribute($name) {
        return $this->attributes[$name];
    }
    
    public function setAttribute($name, $value) {
        $this->attributes[$name] = $value;
    }
    
}
