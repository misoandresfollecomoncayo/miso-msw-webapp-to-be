<?php

class ReportFilter {
    
    const DATA_TYPE_DATE = "DATE";
    
    const DATA_TYPE_CUSTOMER = "CUSTOMER";
    
    const DATA_TYPE_COUNTRY = "COUNTRY";
    
    const DATA_TYPE_CITY = "CITY";
    
    private $idReportFilter;
    
    private $name;
    
    private $dataType;
    
    private $required;
    
    private $idReport;
    
    public function __construct($idReportFilter, $name, $dataType, $required, $idReport) {
        $this->idReportFilter = $idReportFilter;
        $this->name = $name;
        $this->dataType = $dataType;
        $this->required = $required;
        $this->idReport = $idReport;
    }

    public function getIdReportFilter() {
        return $this->idReportFilter;
    }

    public function getName() {
        return $this->name;
    }

    public function getDataType() {
        return $this->dataType;
    }

    public function isRequired() {
        return $this->required;
    }

    public function getIdReport() {
        return $this->idReport;
    }
    
}
