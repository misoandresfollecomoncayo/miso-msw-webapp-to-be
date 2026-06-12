<?php

use Cloud\Engine\PHP\Design;
use Cloud\Engine\PHP\HTTP\CloudEngineSession;

class Layout extends Design\CloudEngineLayout {
    
    public function __construct() {
        parent::__construct();
        parent::setThemeColor("#c2000b");
        parent::setIcon(PUBLIC_PATH_STATIC . "Images/favicon.ico");
        parent::setAppleTouchIcon(PUBLIC_PATH_STATIC . "Images/ios.png");
        
        parent::addCSSFile("https://fonts.googleapis.com/css?family=Open+Sans:400,600,700");
        parent::addCSSFile(PUBLIC_PATH_STATIC . "CSS/cloud-engine.css");
        parent::addCSSFile(PUBLIC_PATH_STATIC . "CSS/theme.css");
        parent::addCSSFile(PUBLIC_PATH_STATIC . "CSS/font-awesome.min.css");
        parent::addCSSFile(PUBLIC_PATH_STATIC . "CSS/jquery.dataTables.min.css");
        
        parent::addJSFile(PUBLIC_PATH_STATIC . "JS/jquery-3.2.1.min.js");
        parent::addJSFile(PUBLIC_PATH_STATIC . "JS/jquery.redirect.js");
        parent::addJSFile(PUBLIC_PATH_STATIC . "JS/jquery.dataTables.min.js");
        parent::addJSFile(PUBLIC_PATH_STATIC . "JS/chart.min.js");
        parent::addJSFile(PUBLIC_PATH_STATIC . "JS/vue.min.js");
        parent::addJSFile(PUBLIC_PATH_STATIC . "JS/vue-resource.min.js");
        parent::addJSFile(PUBLIC_PATH_STATIC . "JS/cloudengine.js");
        parent::addJSFile(PUBLIC_PATH_STATIC . "JS/core.js");
        
        if (strstr($_SERVER["HTTP_USER_AGENT"], "Macintosh") > -1) {
            parent::addMetaTag("Viewport", "width=1024, initial-scale=1 user-scalable=1");
        }
    }
    
    public function printMainBar() {
        $permissions = CloudEngineSession::getSessionObject()->getObject()->getRole()->getPermissions();
        $pendingNotifications = count(CloudEngineSession::getSessionObject()->getObject()->getUnreadNotifications());
        
        $code = '<div id="divMobileMenuOverlay" class="black-overlay"></div>';
        $code .= '<div id="divMainBar" class="background-color-white float-left main-bar overflow-auto">';
        $code .= '<div class="padding-4x"><div class="logotype"></div></div>';
        
        if (CloudEngineSession::getSessionObject()->getType() == Access::TYPE_CUSTOMER) {
            if (CloudEngineSession::getSessionObject()->getObject()->getLanguage() == Customer::LANGUAGE_SPANISH) {
                $code .= "<div class='locker padding-3x text-align-center text-weight-bold'>Bienvenido, " . CloudEngineSession::getSessionObject()->getObject()->getNames() . "!<br/>Casillero No. " . CloudEngineSession::getSessionObject()->getObject()->getLockerNumber();
                $code .= "<div class='margin-top-2x display-table width-100'>";
                $code .= "<div class='width-50 float-left background-darken padding text-size-xs cursor-default' style='border-radius:3px 0 0 3px'>ESPAÑOL</div>";
                $code .= "<div data-lang='ENGLISH' name='btnLang' class='width-50 float-left padding text-size-xs cursor-pointer' style='border-radius:0 3px 3px 0; box-shadow:inset 0 0 0 1px #ccc'>ENGLISH</div>";
                $code .= "</div>";
                $code .= "</div>";
            } else {
                $code .= "<div class='locker padding-3x text-align-center text-weight-bold'>Welcome, " . CloudEngineSession::getSessionObject()->getObject()->getNames() . "!<br/>Locker No. " . CloudEngineSession::getSessionObject()->getObject()->getLockerNumber();
                $code .= "<div class='margin-top-2x display-table width-100'>";
                $code .= "<div data-lang='SPANISH' name='btnLang' class='width-50 float-left text-size-xs cursor-pointer padding' style='border-radius:3px 0 0 3px; box-shadow:inset 0 0 0 1px #ccc'>ESPAÑOL</div>";
                $code .= "<div class='width-50 float-left padding text-size-xs cursor-default background-darken' style='border-radius:0 3px 3px 0;'>ENGLISH</div>";
                $code .= "</div>";
                $code .= "</div>";
            }
        } else {
            $code .= "<div class='locker padding-3x text-align-center text-weight-bold'>Bienvenido, " . CloudEngineSession::getSessionObject()->getObject()->getNames() . "!</div>";
        }
        
        foreach ($permissions as $p) {
            $name = CloudEngineSession::getSessionObject()->getType() == Access::TYPE_CUSTOMER && CloudEngineSession::getSessionObject()->getObject()->getLanguage() == Customer::LANGUAGE_ENGLISH ? $p->getNameEnglish() : $p->getName();
            
            $target = $p->getIdPermission() == "7b0ab531-9f0f-463c-9036-3ae554702b97" ? "_blank" : "_self";
            
            if (strstr($_SERVER["SCRIPT_NAME"], $p->getFile()) !== false) {
                if (($name == "Notifications" || $name == "Notificaciones") && $pendingNotifications > 0) {
                    $code .= '<div class="item selected"><i class="' . $p->getIcon() . '"></i>' . $name . "<div class='counter'>" . $pendingNotifications . '</div></div>';
                } else {
                    $code .= '<div class="item selected"><i class="' . $p->getIcon() . '"></i>' . $name . '</div>';
                }
            } else {
                if (($name == "Notifications" || $name == "Notificaciones") && $pendingNotifications > 0) {
                    $code .= '<a target="' . $target . '" href="' . PUBLIC_PATH_PLATFORM . $p->getFile() . '" class="item on-hover-darken"><i class="' . $p->getIcon() . '"></i>' . $name . "<div class='counter'>" . $pendingNotifications . '</div></a>';
                } else {
                    $code .= '<a target="' . $target . '" href="' . PUBLIC_PATH_PLATFORM . $p->getFile() . '" class="item on-hover-darken"><i class="' . $p->getIcon() . '"></i>' . $name . '</a>';
                }
            }
        }
        
        $code .= '</div>';
        echo $code;
    }
    
    public function printSessionBar($title, $back = null) {
        $code = '<div class="display-table width-100 background-color-red-uniexpress session-bar padding-right-5x padding-left-5x mobile-padding-left-3x mobile-padding-right-3x">';
        
        if ($back != null) {
            $code .= '<a href="' . $back . '" class="float-left on-hover-darken padding-left-3x padding-right-3x"><i class="fa fa-angle-left text-color-white"></i></a>';
        }
        
        $code .= '<div class="text-size-m float-left text-color-white cursor-default">' . $title . '</div>';
        $code .= '<div id="btnMobileMenu" class="mobile-show float-right on-hover-darken padding-left-3x padding-right-3x"><i class="fa fa-bars text-color-white"></i></div>';
        
        if (CloudEngineSession::getSessionObject()->getType() == Access::TYPE_CUSTOMER) {
            $code .= '<a href="' . PUBLIC_PATH_PLATFORM . 'Customer/Profile.php" class="mobile-hide float-right on-hover-darken padding-left-3x padding-right-3x"><i class="fa fa-user text-color-white"></i></a>';
        }
        
        $code .= '<a href="' . PUBLIC_PATH_PLATFORM . 'Transversal/Notifications.php" class="mobile-hide float-right on-hover-darken padding-left-3x padding-right-3x"><i class="fa fa-bell text-color-white"></i></a>';
        $code .= '</div>';
        echo $code;
    }
    
}
