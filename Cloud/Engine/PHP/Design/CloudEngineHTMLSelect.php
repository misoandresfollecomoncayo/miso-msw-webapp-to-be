<?php

namespace Cloud\Engine\PHP\Design;

class CloudEngineHTMLSelect extends CloudEngineHTMLObject {

    private $options;
    
    private $selected;
    
    public function __construct() {
        parent::__construct();
        $this->options = array();
    }
    
    public function addOption($text, $value) {
        array_push($this->options, new Option($text, $value));
    }
    
    public function setSelected($value) {
        $this->selected = $value;
    }
    
    public function render() {
        echo $this->getCode();
    }
    
    public function getCode() {
        $code = "<select " . parent::toString() . ">";
        
        foreach ($this->options as $o) {
            if ($o->getValue() == $this->selected) {
                $code .= "<option selected value='" . $o->getValue() . "'>" . $o->getText() . "</option>";
            } else {
                $code .= "<option value='" . $o->getValue() . "'>" . $o->getText() . "</option>";
            }
        }
        
        $code .= "</select>";
        
        return $code;
    }
    
}

class Option {
    
    private $text;
    
    private $value;
    
    public function __construct($text, $value) {
        $this->text = $text;
        $this->value = $value;
    }
    
    public function getText() {
        return $this->text;
    }

    public function getValue() {
        return $this->value;
    }
    
}