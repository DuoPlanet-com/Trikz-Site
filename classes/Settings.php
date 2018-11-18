<?php
/**
 * Created by PhpStorm.
 * User: andre
 * Date: 04-11-2018
 * Time: 11:23
 */

/**
 * Class 'Settings' decodes json file 'settings.json'.
 *
 * @author Andreas M. Henriksen <AndreasHenriksen@yahoo.dk>
 */
class Settings {

    /**
     * Loads settings from 'settings.json' and decodes it.
     *
     * @return array - Settings
     */
    public static function GetSettings() {
        // Read JSON file
        $json = file_get_contents('settings.json');

        // Decode JSON
        $json_data = json_decode($json,true);

        // Return data
        return $json_data;
    }

    /**
     * Loads settings from 'settings.json' and decodes it.
     *
     * Also takes the directory location into account.
     *
     * @return array - Settings
     */
    public static function GetSettingsDir($dir) {
        // Read JSON file
        $json = file_get_contents($dir.'settings.json');

        // Decode JSON
        $json_data = json_decode($json,true);

        // Return data
        return $json_data;
    }

}