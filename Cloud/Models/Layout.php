<?php

use Cloud\Engine\PHP\Design;
use Cloud\Engine\PHP\HTTP\CloudEngineSession;

class Layout extends Design\CloudEngineLayout {
    
    public function __construct() {
        parent::__construct();
        parent::setThemeColor("#0049b0");
        
        parent::addCSSFile("https://fonts.googleapis.com/css?family=Open+Sans:400,600,700");
        parent::addCSSFile(PUBLIC_PATH_STATIC_CLOUD_ENGINE . "CSS/base.css");
        parent::addCSSFile(PUBLIC_PATH_STATIC . "CSS/theme.css");
        parent::addCSSFile(PUBLIC_PATH_STATIC . "CSS/font-awesome.min.css");
        
        parent::addJSFile(PUBLIC_PATH_STATIC . "JS/jquery-3.2.1.min.js");
        parent::addJSFile(PUBLIC_PATH_STATIC_CLOUD_ENGINE . "JS/cloudengine.js");
        parent::addJSFile(PUBLIC_PATH_STATIC . "JS/jquery.redirect.js");
        parent::addJSFile(PUBLIC_PATH_STATIC . "JS/core.js");
        parent::addJSFile(PUBLIC_PATH_STATIC . "JS/Libs/konva.min.js");
    }
    
    public function printMainBar() {
        $userPermissions = CloudEngineSession::getSessionObject()->getRole()->getPermissions();
        
        $code = '<div id="divMobileMenuOverlay" class="black-overlay"></div>';
        $code .= '<div id="divMainBar" class="background-color-studio float-left main-bar overflow-auto">';
        $code .= '<div class="logotype background-darken cursor-default">Quantumsoft Cloud</div>';
        
        foreach ($userPermissions as $p) {
            if (strstr($_SERVER["SCRIPT_NAME"], $p->getFile()) !== false ) {
                $code .= '<div class="background-darken item selected"><i class="' . $p->getIcon() . '"></i>' . $p->getName() . '</div>';
            } else {
                $code .= '<a href="' . PUBLIC_PATH_PLATFORM . $p->getFile() . '" class="item on-hover-darken"><i class="' . $p->getIcon() . '"></i>' . $p->getName() . '</a>';
            }
        }
        
        $code .= '<a href="' . PUBLIC_PATH_PLATFORM . 'Logout.php" class="on-hover-darken item"><i class="fa fa-sign-out"></i>Salir</a>';
        $code .= '</div>';
        echo $code;
    }
    
    public function printSessionBar($title, $back = null) {
        $code = '<div class="display-table width-100 background-color-quantumsoft session-bar padding-right-5x padding-left-5x mobile-padding-left-3x mobile-padding-right-3x">';
        
        if ($back != null) {
            $code .= '<a href="' . PUBLIC_PATH_PLATFORM . $back . '" class="mobile-hide float-left on-hover-darken padding-left-3x padding-right-3x"><i class="fa fa-angle-left text-color-white"></i></a>';
        }
        
        $code .= '<div class="text-size-m float-left text-color-white cursor-default">' . $title . '</div>';
        $code .= '<div id="btnMobileMenu" class="mobile-show float-right on-hover-darken padding-left-3x padding-right-3x"><i class="fa fa-bars text-color-white"></i></div>';
        $code .= '<a href="Profile.php" class="mobile-hide float-right on-hover-darken padding-left-3x padding-right-3x"><i class="fa fa-user text-color-white"></i></a>';
        $code .= '<a href="Notifications.php" class="mobile-hide float-right on-hover-darken padding-left-3x padding-right-3x"><i class="fa fa-bell text-color-white"></i></a>';
        $code .= '</div>';
        echo $code;
    }
    
}
