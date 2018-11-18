<?php
/**
 * Created by PhpStorm.
 * User: andre
 * Date: 07-11-2018
 * Time: 12:25
 */

/**
 * Class 'Page' loads the correct HTML code from '/templates/'
 *
 * Upon constructing it will load decide what page we are on using
 *     $_GET['p'] and loads the appropriate template for said page.
 *
 * @author Andreas M. Henriksen <AndreasHenriksen>
 */
class Page {

    private $pageString;

    /**
     * Constructs the page, loading the template.
     *
     * First it grabs the page string using $_GET['p']. Then
     *     loads the template.
     */
    function __construct()
    {
        // Set internal variables
        $this->pageString = $this->SetPageString();

        // Load template
        $this->LoadTemplate();
    }

    /**
     * Includes the template from '/templates/'
     */
    function LoadTemplate() {
        $loc = $this->TemplateLocation();
        if (file_exists($loc)) {
            include "$loc";
        } else {
            die("Template failed to load: Location not found");
        }
    }

    /**
     * The location of the template for this page.
     *
     * @return string - Returns the location of the template.
     */
    function TemplateLocation() {
        $str = $this->pageString;
        return "templates/$str.php";
    }

    /**
     * Uses $_GET['p'] to grab the page index. If none are set return 'home'.
     *
     * @return string - The page index.
     */
    function SetPageString() {
        if (!isset($_GET['p'])) {
            return "home";
        }
        return $_GET['p'];
    }

    /**
     * Returns the current page index set by the constructor.
     *
     * @return string - The page index.
     */
    public function PageString() {
        return $this->pageString;
    }
}