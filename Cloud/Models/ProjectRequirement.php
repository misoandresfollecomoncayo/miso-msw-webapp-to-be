<?php


class ProjectRequirement {
    
    /*
     * Constants
     */
    
    const STATE_COMPLETED = "COMPLETED";
    
    const STATE_DELETED = "DELETED";
    
    const STATE_PENDING = "PENDING";
    
    const STATE_PROGRESS = "PROGRESS";
    
    const PRIORITY_HIGH = "HIGH";
    
    const PRIORITY_MEDIUM = "MEDIUM";
    
    const PRIORITY_LOW = "LOW";
    
    const COMPLEXITY_HIGH = "HIGH";
    
    const COMPLEXITY_MEDIUM = "MEDIUM";
    
    const COMPLEXITY_LOW = "LOW";
    
    /*
     * Attributes
     */
    
    private $idProjectRequirement;
    
    private $description;
    
    private $idProjectModule;
    
    private $state;
    
    private $completedTimestamp;
    
    private $completedIdUser;
    
    private $idProjectActor;
    
    private $priority;
    
    private $complexity;
    
    private $startDate;
    
    private $endDate;
    
    /*
     * Methods
     */
    
    public function __construct($idProjectRequirement, $description, $idProjectModule, $state, $completedTimestamp, $completedIdUser, $idProjectActor, $priority, $complexity, $startDate, $endDate) {
        $this->idProjectRequirement = $idProjectRequirement;
        $this->description = $description;
        $this->idProjectModule = $idProjectModule;
        $this->state = $state;
        $this->completedTimestamp = $completedTimestamp;
        $this->completedIdUser = $completedIdUser;
        $this->idProjectActor = $idProjectActor;
        $this->priority = $priority;
        $this->complexity = $complexity;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }
    
    public function getIdProjectRequirement() {
        return $this->idProjectRequirement;
    }
    
    public function getDescription() {
        return $this->description;
    }
    
    public function getIdProjectModule() {
        return $this->idProjectModule;
    }
    
    public function getProjectModule() {
        return ProjectModuleDAO::getProjectModuleById($this->idProjectModule);
    }
    
    public function getState() {
        return $this->state;
    }
    
    public function getStateColor() {
        switch ($this->state) {
            case ProjectRequirement::STATE_COMPLETED:
                return "background-color-green";
            case ProjectRequirement::STATE_PROGRESS:
                return "background-color-yellow";
            case ProjectRequirement::STATE_PENDING:
                return "background-color-gray";
        }
    }
    
    public function getStateColorHex() {
        switch ($this->state) {
            case ProjectRequirement::STATE_COMPLETED:
                return "#00C851";
            case ProjectRequirement::STATE_PROGRESS:
                return "#FFC107";
            case ProjectRequirement::STATE_PENDING:
                return "#0049b0";
        }
    }
    
    public function getCompletedTimestamp() {
        return $this->completedTimestamp;
    }
    
    public function getCompletedUser() {
        return UserDAO::getUserById($this->completedIdUser);
    }
    
    public function getProjectActor() {
        return ProjectActorDAO::getActorById($this->idProjectActor);
    }
    
    public function getTracking() {
        return TrackingDAO::getTrackingByIdRegistry($this->idProjectRequirement);
    }
    
    public function getCreatedTracking() {
        return TrackingDAO::getCreatedTrackingByIdRegistry($this->idProjectRequirement);
    }
    
    public function getPriority() {
        return $this->priority;
    }
    
    public function getPriorityColor() {
        switch ($this->priority) {
            case ProjectRequirement::PRIORITY_HIGH:
                return "background-color-red";
            case ProjectRequirement::PRIORITY_MEDIUM:
                return "background-color-orange";
            case ProjectRequirement::PRIORITY_LOW:
                return "background-color-yellow";
        }
    }
    
    public function getComplexity() {
        return $this->complexity;
    }
    
    public function getComplexityColor() {
        switch ($this->complexity) {
            case ProjectRequirement::COMPLEXITY_HIGH:
                return "background-color-red";
            case ProjectRequirement::COMPLEXITY_MEDIUM:
                return "background-color-orange";
            case ProjectRequirement::COMPLEXITY_LOW:
                return "background-color-yellow";
        }
    }
    
    public function getStartDate() {
        return new DateTime($this->startDate);
    }

    public function getEndDate() {
        return new DateTime($this->endDate);
    }

}
