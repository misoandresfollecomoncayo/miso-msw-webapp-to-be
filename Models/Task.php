<?php

class Task {
    
    const HIGH_PRIORITY = 1;
    const NORMAL_PRIORITY = 0;
    
    const STATUS_PENDING = "PENDING";
    const STATUS_PROCESS = "PROCESS";
    const STATUS_FINISHED = "FINISHED";
    
    private $idTask;
    private $consecutive;
    private $title;
    private $description;
    private $highPriority;
    private $idCountry;
    private $date;
    private $status;
    private $idCreator;
    private $idProcessor;
    private $idCompleted;
    private $idWarehouse;
    
    public function __construct($idTask, $consecutive, $title, $description, $highPriority, $idCountry, $date, $status, $idCreator, $idProcessor, $idCompleted, $idWarehouse) {
        $this->idTask = $idTask;
        $this->consecutive = $consecutive;
        $this->title = $title;
        $this->description = $description;
        $this->highPriority = $highPriority;
        $this->idCountry = $idCountry;
        $this->date = $date;
        $this->status = $status;
        $this->idCreator = $idCreator;
        $this->idProcessor = $idProcessor;
        $this->idCompleted = $idCompleted;
        $this->idWarehouse = $idWarehouse;
    }
    
    public function getIdTask() {
        return $this->idTask;
    }
    
    public function getConsecutive() {
        return $this->consecutive;
    }

    public function getTitle() {
        return $this->title;
    }

    public function getDescription() {
        return $this->description;
    }

    public function getHighPriority() {
        return $this->highPriority;
    }

    public function getIdCountry() {
        return $this->idCountry;
    }

    public function getDate() {
        return $this->date;
    }

    public function getStatus() {
        return $this->status;
    }
    
    public function getCreatorSystemUser() {
        return SystemUserDAO::getSystemUserById($this->idCreator);
    }
    
    public function getProcessorSystemUser() {
        return SystemUserDAO::getSystemUserById($this->idProcessor);
    }
    
    public function getCompletedSystemUser() {
        return SystemUserDAO::getSystemUserById($this->idCompleted);
    }
    
    public function getWarehouse() {
        return WarehouseDAO::getWarehouseById($this->idWarehouse);
    }
    
}
