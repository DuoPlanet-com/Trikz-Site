<?php
/**
 * Created by PhpStorm.
 * User: andre
 * Date: 04-11-2018
 * Time: 12:18
 */

class Users {
    public static function All() {
        $sql = "SELECT * FROM registered_users";
        return Database::Query($sql);
    }

    public static function Get($steamID){
        $sql = "SELECT * FROM registered_users WHERE steamid = '$steamID'";
        return mysqli_fetch_assoc( Database::Query($sql) );
    }

    public static function Exists($steamID) {
        $sql = "SELECT * FROM registered_users WHERE steamid = '$steamID'";
        if (Database::Query($sql)->num_rows > 0) {
            return true;
        }
        return false;
    }
}