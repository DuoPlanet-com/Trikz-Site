<?php
/**
 * Created by PhpStorm.
 * User: andre
 * Date: 07-11-2018
 * Time: 14:01
 */

/**
 * Class 'Modules' handles snippets of HTML.
 *
 * Handles loading of HTML snippets that are located in
 *     '/modules/'. Using Modules::Load(string $module)
 *     you can load 'index.php' file in '/modules/$module/'
 *
 * @author Andreas M. Henriksen <AndreasHenriksen@yahoo.dk>
 */
class Modules {

    /**
     * Includes file 'index.php' in '/modules/$module/'
     *
     * @param string $module - The name of the module.
     */
    public static function Load($module) {
        if (self::Exists($module)) {
            include "modules/$module/index.php";
        } else {
            die("Module $module failed to load: Not found or not labeled index.php");
        }
    }

    /**
     * Checks whether or not the module specified exists in '/modules/'
     *
     * @param string $module - The name of the module.
     * @return bool - Returns true if the module in question exists.
     */
    public static function Exists($module) {
        if (file_exists("modules/$module/index.php")) {
            return true;
        }
        return false;
    }
}