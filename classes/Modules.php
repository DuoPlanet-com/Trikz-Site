<?php
/**
 * Created by PhpStorm.
 * User: andre
 * Date: 07-11-2018
 * Time: 14:01
 */

class Modules {
    public static function Load($module) {
        if (self::Exists($module)) {
            include "modules/$module/index.php";
        } else {
            die("Module $module failed to load: Not found or not labeled index.php");
        }
    }

    public static function Exists($module) {
        if (file_exists("modules/$module/index.php")) {
            return true;
        }
        return false;
    }
}