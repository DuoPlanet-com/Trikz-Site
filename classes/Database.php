<?php
require_once 'Settings.php';

/**
 * Class Database handles SQL queries and the database connection.
 *
 * Upon running the constructor, settings from settings.json will be
 *     read and used to establish a connection to the database.
 *     Please ensure the settings are correct in settings.json.
 *     This condenses regular SQL queries into a single static
 *     function called Query( $sql )
 *
 * @author Andreas M. Henriksen <AndreasHenriksen@yahoo.dk>
 */
class Database {

    public static
        $connection,
        $settings,
        $steamKey;

    /**
     * Grabs the settings from settings.json and establishes a connection to the database.
     */
    function __construct()
    {
        global $steamauth;
        self::$steamKey = $steamauth['apikey'];
        // Fetch and set database settings locally
        self::$settings = $this->Settings();
        // Establish connection
        self::$connection = $this->Connection();
    }

    /**
     * Queries the database returning the result.
     *
     * Uses the previously established connection to query the database.
     *     Note that you must have run the constructor, in order for
     *     this to work.
     *
     * @param string $query - The SQL statement.
     * @return mysqli_result
     */
    public static function Query($query) {

        $query = self::Escaped($query);

        $result = mysqli_query(self::$connection,$query);
        if (!$result) {
            die("MySQL error! Failed to query ; " . $query);
        }
        return $result;
    }

    public static function NonEscapedQuery($query) {
        $result = mysqli_query(self::$connection,$query);
        if (!$result) {
            die("MySQL error! Failed to query ; " . $query);
        }
        return $result;
    }

    public static function Escaped($string) {
        $string = self::$connection->real_escape_string($string);
        return $string;
    }

    /**
     * Returns the database part of the settings in settings.json as array.
     *
     * @return array - Database settings.
     */
    function Settings(){
        // Return settings from 'Settings' class
        return Settings::GetSettings()['database'];
    }

    /**
     * Establishes a MySQL connection.
     *
     * @return mysqli - MySQL connection.
     */
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