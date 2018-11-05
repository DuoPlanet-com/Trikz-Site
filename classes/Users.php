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

        $result = Database::Query($sql);

        $array = [];
        $i =0;
        foreach ($result as $item) {
            $array[$i] = new User($item['steamid']);
            $i++;
        }

        return $array;
    }

    public static function Get($steamid64){
        $sql = "SELECT * FROM registered_users WHERE steamid = '$steamid64'";
        return new User(mysqli_fetch_assoc( Database::Query($sql) )['steamid']);
    }

    public static function Exists($steamid64) {
        $sql = "SELECT * FROM registered_users WHERE steamid = '$steamid64'";
        if (Database::Query($sql)->num_rows > 0) {
            return true;
        }
        return false;
    }

    public static function SteamID($steamid64) {
        $accountID = bcsub($steamid64, '76561197960265728');
        return 'STEAM_0:'.bcmod($accountID, '2').':'.bcdiv($accountID, 2);
    }

    public static function SteamID32($steamID_2) {
        $parts = explode(":",$steamID_2);
        $y = $parts[1];
        $z = $parts[2];
        return "[U:1:" .($z*2+$y) . "]";
    }

    public static function Register($steamid64) {
        if (!self::Exists($steamid64)) {
            $sql = "INSERT INTO registered_users (steamid) VALUES ('$steamid64')";
            return Database::Query($sql);
        }
        die("User already exists");
    }

    public static function CurrentUser() {
        global $steamprofile;
        if (isset($steamprofile['steamid'])) {
            return new User($steamprofile['steamid']);
        }
        return false;
    }

    public static function GrabSteamData($steamid_64) {
        $apiKey = Database::$steamKey;
        $id = $steamid_64;
        $url = "http://api.steampowered.com/ISteamUser/GetPlayerSummaries/v0002/?key=$apiKey&steamids=$id";

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_URL,$url);

        $result = json_decode(curl_exec($ch));

        curl_close($ch);
        return $result->response->players[0];
    }

}