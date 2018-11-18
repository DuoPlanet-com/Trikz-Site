<?php
/**
 * Created by PhpStorm.
 * User: andre
 * Date: 04-11-2018
 * Time: 21:14
 */

/**
 * Class 'SteamUser' contains Steam data on an individual user.
 *
 * By constructing this class with a 64-bit Steam Community ID,
 *     a range of variables are set. These are set by the Steam API.
 *     see 'https://developer.valvesoftware.com/wiki/Steam_Web_API#GetPlayerSummaries_.28v0002.29'
 *     It also checks whether or not this user has been registered
 *     in the local database.
 *
 * @author Andreas M. Henriksen <AndreasHenriksen@yahoo.dk>
 */
class SteamUser {

    public $steamid64;
    public $steamid32;
    public $steamid;
    public $personaName;
    public $lastLogOff;
    public $commentPermission;
    public $profileUrl;
    public $avatar;
    public $avatarMedium;
    public $avatarFull;
    public $personaState;
    public $primaryClanId;
    public $timeCreated;
    public $personaStateFlags;
    public $communityVisibilityState;
    public $profileState;
    public $registered;

    /**
     * Constructs a SteamUser object.
     *
     * Using the Steam Community ID to grab data from Steam API
     *     and sets them internally for ease of use. Also queries
     *     the database to check whether or not the user has been
     *     registered on the website. The user does not have to be
     *     registered in local database for this to work.
     *
     * @param string $steamid_64 - The 64-bit Steam Community ID. MUST BE STRING!
     */
    function __construct($steamid_64) {
        $this->steamid64    = $steamid_64;
        $this->steamid      = Users::SteamID($steamid_64);
        $this->steamid32    = Users::SteamID32($this->steamid);

        $steamApiData = Users::GrabSteamData($steamid_64);

        $this->personaName              = $steamApiData->personaname;
        $this->communityVisibilityState = $steamApiData->communityvisibilitystate;
        $this->profileState             = $steamApiData->profilestate;
        $this->lastLogOff               = $steamApiData->lastlogoff;
        $this->commentPermission        = $steamApiData->commentpermission;
        $this->profileUrl               = $steamApiData->profileurl;
        $this->avatar                   = $steamApiData->avatar;
        $this->avatarMedium             = $steamApiData->avatarmedium;
        $this->avatarFull               = $steamApiData->avatarfull;
        $this->personaState             = $steamApiData->personastate;
        $this->primaryClanId            = $steamApiData->primaryclanid;
        $this->timeCreated              = $steamApiData->timecreated;
        $this->personaStateFlags        = $steamApiData->personastateflags;

        if (Users::Registered($this->steamid64)) {
            $this->registered = true;
        } else {
            $this->registered = false;
        }

    }

    /**
     * Registers this user to our own database of users.
     */
    public function Register() {
        if (!Users::Registered($this->steamid64)) {
            Users::Register($this->steamid64);
        }
    }

    /**
     * Check whether or not this SteamUser has donated and, if so, how much.
     *
     * @return float|bool - Returns float if the user has donated before.
     *     float represents how much user has donated. Returns false if none.
     */
    public function IsDonor() {

        // Set SQL statement
        $steamid_64 = $this->steamid64;
        $sql = "SELECT * FROM `donors` WHERE `steamid64` = '$steamid_64'";

        // Query database
        if ($query = Database::Query($sql)) {
            // Check if any entry was found
            if ($query->num_rows > 0) {
                // Fetch row of data as array
                $row = $query->fetch_assoc();
                // Check amount pledged and return
                if ($row['amount'] > 0) {
                    return $row['amount'];
                }
            }
        } else {
            die("MySQL error! Failed to query donors");
        }
        // Return false if no entry was found or if the amount pledged is 0
        return false;
    }

    /**
     * Check whether or not this user has VIP status.
     *
     * @return bool - Returns true if this user has VIP status.
     */
    public function IsVIP() {
        if (Users::IsVIP($this->steamid64)){
            return true;
        }
        return false;
    }
}