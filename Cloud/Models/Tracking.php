<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Tracking
 *
 * @author root
 */
class Tracking {
    
    const ACTION_CREATED = "CREATED";
    
    const ACTION_UPDATED = "UPDATED";
    
    const ACTION_DELETED = "DELETED";
    
    private $idTracking;
    
    private $timestamp;
    
    private $action;
    
    private $beforeValue;
    
    private $idUser;
    
    private $idRegistry;
    
    public function __construct($idTracking, $timestamp, $action, $beforeValue, $idUser, $idRegistry) {
        $this->idTracking = $idTracking;
        $this->timestamp = $timestamp;
        $this->action = $action;
        $this->beforeValue = $beforeValue;
        $this->idUser = $idUser;
        $this->idRegistry = $idRegistry;
    }
    
    public function getIdTracking() {
        return $this->idTracking;
    }
    
    public function getTimestamp() {
        return $this->timestamp;
    }
    
    public function getAction() {
        return $this->action;
    }
    
    public function getBeforeValue() {
        return $this->beforeValue;
    }
    
    public function getUser() {
        return UserDAO::getUserById($this->idUser);
    }
    
    public function getIdRegistry() {
        return $this->idRegistry;
    }
    
}
