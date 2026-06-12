<?php

namespace Cloud\Engine\PHP\WebService;

class CloudEngineWebService {
    
    const METHOD_GET = "GET";
    const METHOD_POST = "POST";
    const METHOD_REQUEST = "REQUEST";
    
    const TYPE_JSON = "JSON";
    const TYPE_RAW = "RAW";
    
    private $type;
    private $method;
    private $response;
    private $callbackFunction;
    private $parameters;
    
    public function __construct() {
        $this->method = CloudEngineWebService::METHOD_POST;
        $this->parameters = array();
        $this->response = array();
        $this->type = CloudEngineWebService::TYPE_JSON;
    }
    
    public function addParameter($pName, $pType, $pLength, $pRequired) {
        $p = new CloudEngineWebServiceParameter($pName, $pType, $pLength, $pRequired);
        array_push($this->parameters, $p);
    }
    
    public function addParameterObj($parameter) {
        array_push($this->parameters, $parameter);
    }
    
    public function getParameter($pName) {
        foreach ($this->parameters as $p) {
            if ($p->getName() == $pName) {
                return $p;
            }
        }
        
        return null;
    }
    
    public function setMethod($pMethod) {
        $this->method = $pMethod;
    }
    
    public function setCallback($pFunction) {
        $this->callbackFunction = $pFunction;
    }
    
    public function setType($pType) {
        $this->type = $pType;
    }
    
    public function publish() {
        if ($this->method == CloudEngineWebService::METHOD_REQUEST ||
            $_SERVER["REQUEST_METHOD"] == $this->method) {
            foreach ($this->parameters as $p) {
                if (!$p->isValid()) {
                    $this->setException($p->getException());
                }
            }
            call_user_func($this->callbackFunction);
        } else {
            $this->setException("Request method not allowed");
        }
    }
    
    public function setResponse($pBody) {
        $this->response = [
            "type" => "Response",
            "body" => $pBody
        ];
        
        if ($this->type == CloudEngineWebService::TYPE_JSON) {
            echo json_encode($this->response);
        } else {
            echo $pBody;
        }
        
        exit();
    }
    
    public function setException($pMessage) {
        $this->response = [
            "type" => "Exception",
            "message" => $pMessage
        ];
        
        if ($this->type == CloudEngineWebService::TYPE_JSON) {
            echo json_encode($this->response);
        } else {
            echo $pMessage;
        }
        
        exit();
    }
    
}