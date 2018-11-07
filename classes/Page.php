<?php
/**
 * Created by PhpStorm.
 * User: andre
 * Date: 07-11-2018
 * Time: 12:25
 */


class Page {

    private $pageString;

    function __construct()
    {
        // Set internal variables
        $this->pageString = $this->SetPageString();

        // Load template
        $this->LoadTemplate();
    }

    function LoadTemplate() {
        $loc = $this->TemplateLocation();
        if (file_exists($loc)) {
            include "$loc";
        } else {
            die("Template failed to load: Location not found");
        }
    }

    function TemplateLocation() {
        $str = $this->pageString;
        return "templates/$str.php";
    }

    function SetPageString() {
        if (!isset($_GET['p'])) {
            return "home";
        }
        return $_GET['p'];
    }

    public function PageString() {
        return $this->pageString;
    }


}