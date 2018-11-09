<?php
/**
 * Created by PhpStorm.
 * User: andre
 * Date: 04-11-2018
 * Time: 11:23
 */


class Settings {

    public static function GetSettings() {
        // Read JSON file
        $json = file_get_contents('settings.json');

        // Decode JSON
        $json_data = json_decode($json,true);

        // Return data
        return $json_data;
    }

    public static function GetSettingsDir($dir) {
        // Read JSON file
        $json = file_get_contents($dir.'settings.json');

        // Decode JSON
        $json_data = json_decode($json,true);

        // Return data
        return $json_data;
    }

}