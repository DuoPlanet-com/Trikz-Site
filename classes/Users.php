<?php
/**
 * Created by PhpStorm.
 * User: andre
 * Date: 04-11-2018
 * Time: 12:18
 */

/**
 * Class 'Users' contains functionality for managing users.
 *
 * Contains a set of static functions, which without setup
 *     can be used. Functions include registering users,
 *     grabbing a list of all registered users,
 *     grabbing a SteamUser, getting the current user online,
 *     checking VIP,donor or admin status, ect.
 *
 * @author Andreas M. Henriksen <AndreasHenriksen@yahoo.dk>
 */
class Users {

    /**
     * Queries the database and generates a list of registered users.
     *
     * @return array - Array of 'SteamUser' objects.
     */
    public static function All() {
        $sql = "SELECT * FROM registered_users";

        $result = Database::Query($sql);

        $array = [];
        $i =0;
        foreach ($result as $item) {
            $array[$i] = new SteamUser($item['steamid']);
            $i++;
        }

        return $array;
    }

    /**
     * Creates a new 'SteamUser' object with specified 64-bit Steam Community ID.
     *
     * @deprecated Use 'new SteamUser($steamId_64)' instead.
     *
     * @param string $steamid64 - The 64-bit Steam Community ID as string.
     * @return SteamUser - The user as SteamUser.
     */
    public static function Get($steamid64){
        return new SteamUser($steamid64);
    }

    /**
     * Queries the database checking whether or not the user is registered.
     *
     * @param string $steamid64 - The 64-bit Steam Community ID as string.
     * @return bool - Returns true if the user is registered.
     */
    public static function Registered($steamid64) {
        $sql = "SELECT * FROM registered_users WHERE steamid = $steamid64";
        if (Database::Query($sql)->num_rows > 0) {
            return true;
        }
        return false;
    }

    /**
     * Converts a 64-bit Steam Community ID to a regular SteamID.
     *
     * @param string $steamid64 - The 64-bit Steam Community ID. ie. '76561198075806077'
     * @return string - The steam id. ie: STEAM_0:1:57770174
     */
    public static function SteamID($steamid64) {
        $accountID = bcsub($steamid64, '76561197960265728');
        return 'STEAM_0:'.bcmod($accountID, '2').':'.bcdiv($accountID, 2);
    }

    /**
     * Converts a Steam ID to a 32-bit Steam ID
     *
     * @param string $steamID_2 - The Steam ID you wish to convert. ie STEAM_0:1:57770174
     * @return string - The 32-bit Steam ID. ie: [U:1:115540349]
     */
    public static function SteamID32($steamID_2) {
        $parts = explode(":",$steamID_2);
        $y = $parts[1];
        $z = $parts[2];
        return "[U:1:" .($z*2+$y) . "]";
    }

    /**
     * Register a user inserting their steam id to the database.
     *
     * @param string $steamid64 - The 64-bit Steam Community ID of the user you wish to register.
     * @return bool|mysqli_result - The MySQLi result. Returns false if user is already registered.
     */
    public static function Register($steamid64) {
        if (!self::Registered($steamid64)) {
            $sql = "INSERT INTO registered_users (steamid) VALUES ($steamid64)";
            return Database::Query($sql);
        }
        return false;
    }

    /**
     * Returns the user that is logged in as 'SteamUser' object. Returns false if no user is logged on.
     *
     * @return bool|SteamUser - The 'SteamUser' currently logged on. Returns false if no user is logged on.
     */
    public static function CurrentUser() {
        global $steamprofile;
        if (isset($steamprofile['steamid'])) {
            return new SteamUser($steamprofile['steamid']);
        }
        return false;
    }

    /**
     * Grabs data directly from the Steam API using the steam key.
     *
     * It is better to use '$user = new SteamUser($steamId_64)'
     *
     * @param string $steamId_64 - The 64-bit Steam Community ID of the user.
     * @return array - An array of steam data. Such as 'PersonaName' or 'avatar'
     */
    public static function GrabSteamData($steamId_64) {
        $apiKey = Database::$steamKey;
        $id = $steamId_64;
        $url = "http://api.steampowered.com/ISteamUser/GetPlayerSummaries/v0002/?key=$apiKey&steamids=$id";

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_URL,$url);

        $result = json_decode(curl_exec($ch));

        curl_close($ch);
        return $result->response->players[0];
    }

    /**
     * Check whether or not a 64-bit Steam Community ID is an admin.
     *
     * Admins are specified in settings.json
     *
     * @param string $steamid_64 - The 64-bit Steam Community ID of the user.
     * @return bool - Returns true if the user in question is admin.
     */
    public static function IsAdmin($steamid_64)
    {
        $admins = Settings::GetSettings()['admins'];
        foreach ($admins as $adminid) {
            if ($adminid == $steamid_64) {
                return true;
            }
        }
        return false;
    }

    /**
     * Check whether or not a 64-bit Steam Community ID is a VIP.
     *
     * A side-effect of this method is if the user's VIP status has expired,
     *     their entry in the 'vips' table will be removed.
     *
     * @param string $steamid_64 - The 64-bit Steam Community ID of the user.
     * @return bool
     */
    public static function IsVIP($steamid_64) {
        $sql = "SELECT * FROM `vips` WHERE `steamid64` = $steamid_64";
        if ($query = Database::Query($sql)) {
            if ($query->num_rows == 1) {
                $row = $query->fetch_assoc();
                if (new DateTime() < new DateTime($row['timestamp_end'])) {
                    return true;
                } else {
                    $sql2 = "DELETE FROM `vips` WHERE `steamid64` = $steamid_64";
                    if (!Database::Query($sql2)) {
                        die("Unable to remove expired VIP");
                    }
                }
            }
        }
        return false;
    }

    /**
     * Returns the expiration date of a VIP user as MySQL timestamp.
     *
     * @param string $steamid_64 - The 64-bit Steam Community ID of the user.
     * @return string - MySQL timestamp. Returns 'expired' if expiration date is exceeded.
     */
    public static function VIPExpirationDate($steamid_64) {
        $sql = "SELECT * FROM `vips` WHERE `steamid64` = $steamid_64";
        if ($query = Database::Query($sql)) {
            if ($query->num_rows == 1) {
                $row = $query->fetch_assoc();
                if (new DateTime() < new DateTime($row['timestamp_end'])) {
                    return $row['timestamp_end'];
                } else {
                    $sql2 = "DELETE FROM `vips` WHERE `steamid64` = $steamid_64";
                    if (!Database::Query($sql2)) {
                        die("Unable to remove expired VIP");
                    }
                }
            }
        }
        return "Expired";
    }

    /**
     * Checks whether or not a user has donated. If so it returns the amount.
     *
     * @param string $steamid_64 - The 64-bit Steam Community ID of the user.
     * @return float|bool - The amount the user has donated. Returns false if the user has never donated.
     */
    public static function IsDonor($steamid_64) {
        $sql = "SELECT * FROM `donors` WHERE `steamid64` = $steamid_64";
        if ($query = Database::Query($sql)) {
            if ($query->num_rows > 0) {
                $row = $query->fetch_assoc();
                if ($row['amount'] > 0) {
                    return $row['amount'];
                }
            }
        } else {
            die("MySQL error! Failed to query donors");
        }
        return false;
    }

    /**
     * Returns the total amount donated.
     *
     * @return float - The total amount donated.
     */
    public static function Donations() {
        $sql = "SELECT * FROM `donors`";
        $amount = 0;
        if ($query = Database::Query($sql)) {
            foreach ($query as $row) {
                $amount+= $row['amount'];
            }
        } else {
            die("Query failed! Unable to retrieve donorlist");
        }
        return $amount;
    }

    /**
     * Returns the total revenue from a particular product.
     *
     * @param string $productString - The product, as specified in settings.json.
     * @return float - The total revenue.
     */
    public static function Revenue($productString) {
        $amount = 0;
        $productString = Database::Escaped($productString);
        $sql = "SELECT * FROM `transactions` WHERE `product` = '$productString'";
        if ($query = Database::NonEscapedQuery($sql)) {
            foreach ($query as $row) {
                if ($row['status'] == "closed") {
                    $amount+= $row['price'];
                }
            }
        } else {
            die("Query failed! Unable to retrieve transactions");
        }
        return $amount;

    }

}