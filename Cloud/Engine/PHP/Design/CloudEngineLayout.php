<?php

namespace Cloud\Engine\PHP\Design;

class MetaTag {
    
    private $name;
    
    private $content;
    
    public function __construct($name, $content) {
        $this->name = $name;
        $this->content = $content;
    }

    public function getName() {
        return $this->name;
    }

    public function getContent() {
        return $this->content;
    }
    
}

class CloudEngineLayout {
    
    private $CSSFiles;
    private $JSFiles;
    private $themeColor;
    private $title;
    private $icon;
    private $appleTouchIcon;
    private $metaTags;
    
    public function __construct() {
        $this->JSFiles = array();
        $this->CSSFiles = array();
        $this->metaTags = array();
    }
    
    public function setTitle($pTitle) {
        $this->title = $pTitle;
    }
    
    public function setThemeColor($pColor) {
        $this->themeColor = $pColor;
    }
    
    public function setIcon($filePath) {
        $this->icon = $filePath;
    }
    
    public function setAppleTouchIcon($filePath) {
        $this->appleTouchIcon = $filePath;
    }
    
    public function addJSFile($pURL) {
        array_push($this->JSFiles, $pURL);
    }
    
    public function addCSSFile($pURL) {
        array_push($this->CSSFiles, $pURL);
    }
    
    public function addMetaTag($name, $content) {
        array_push($this->metaTags, new MetaTag($name, $content));
    }
    
    public function printHead() {
        $code = "";
        $code .=
        "<!DOCTYPE html>
        <html>
        <head>
        <meta charset=\"UTF-8\">
        <meta name=\"theme-color\" content=\"" . $this->themeColor . "\" />
        <meta http-equiv=\"X-UA-Compatible\" content=\"IE=edge\" />
        <meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />
        <link rel=\"icon\" type=\"image/x-icon\" href=\"" . $this->icon . "\" />";
        
        if ($this->appleTouchIcon != null && $this->appleTouchIcon != "") {
            $code .= "<link rel=\"apple-touch-icon\" href=\"" . $this->appleTouchIcon . "\" />";
        }
        
        foreach ($this->CSSFiles as $url) {
            $separator = strstr($url, "?") > -1 ? "&" : "?";
            $code .= "<link href=\"" . $url . $separator . date("ymdHis") . "\" rel=\"stylesheet\">";
        }
        
        if (count($this->metaTags) == 0) {
            $code .= "<meta name=\"Viewport\" content=\"width=device-width, initial-scale=1 user-scalable=no\" />";
        } else {
            foreach ($this->metaTags as $m) {
                $code .= "<meta name='" . $m->getName() . "' content='" . $m->getContent() . "' >";
            }
        }
        
        $code .= "<title>" . $this->title . "</title></head>";
        echo $code;
    }
    
    public function printJSScripts() {
        foreach ($this->JSFiles as $url) {
            $separator = strstr($url, "?") > -1 ? "&" : "?";
            echo "<script src=\"" . $url . $separator . date("ymdHis") . "\"></script>";
        }
    }
    
}