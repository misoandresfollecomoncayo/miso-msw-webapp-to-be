<?php

class Permission {
    
    const CSS_COMPILER = "Compilador CSS";
    const DASHBOARD = "Dashboard";
    const NOTIFICATIONS = "Notificaciones";
    const PROFILE = "Perfil";
    const REQUIREMENTS = "Requerimientos";
    const SECURITY = "Seguridad";
    const PROGRESS_VIEWER = "Visor de progreso";
    
    private $idPermission;
    
    private $name;
    
    private $file;
    
    private $icon;
    
    private $order;
    
    public function __construct($idPermission, $name, $file, $icon, $order) {
        $this->idPermission = $idPermission;
        $this->name = $name;
        $this->file = $file;
        $this->icon = $icon;
        $this->order = $order;
    }
    
    public function getIdPermission() {
        return $this->idPermission;
    }

    public function getName() {
        return $this->name;
    }

    public function getFile() {
        return $this->file;
    }

    public function getIcon() {
        return $this->icon;
    }
    
    public function getOrder() {
        return $this->order;
    }

}
