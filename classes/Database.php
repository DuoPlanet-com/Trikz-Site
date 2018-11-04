<?php
require_once 'Settings.php';

class Database {

    public static $connection;
    public static $settings;


    function __construct()
    {
        // Fetch and set database settings locally
        self::$settings = $this->Settings();
        // Establish connection
        self::$connection = $this->Connection();
    }

    public static function Query($query) {
        return mysqli_query(self::$connection,$query);
    }

    function Settings(){
        // Return settings from 'Settings' class
        return Settings::GetSettings()['database'];
    }

    function Connection() {
        // Connect with settings fetched in the constructor
        return new mysqli(
            self::$settings['host'],
            self::$settings['user'],
            self::$settings['password'],
            self::$settings['name'],
            self::$settings['port']);
    }
}