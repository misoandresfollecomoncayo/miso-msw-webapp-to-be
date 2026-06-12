<?php

class Report {
    
    private $idReport;
    
    private $name;
    
    private $query;
    
    public function __construct($idReport, $name, $query) {
        $this->idReport = $idReport;
        $this->name = $name;
        $this->query = $query;
    }

    public function getIdReport() {
        return $this->idReport;
    }

    public function getName() {
        return $this->name;
    }

    public function getQuery() {
        return $this->query;
    }
    
    public function getFilters() {
        return ReportFilterDAO::getReportFiltersByReport($this);
    }
    
}
