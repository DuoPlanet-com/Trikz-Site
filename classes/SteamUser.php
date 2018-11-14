<?php
/**
 * Created by PhpStorm.
 * User: andre
 * Date: 04-11-2018
 * Time: 21:14
 */

/**
 * Class SteamUser
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

    function __construct($steamid_64) {
        $this->steamid64 = $steamid_64;
        $this->steamid = Users::SteamID($steamid_64);
        $this->steamid32 = Users::SteamID32($this->steamid);

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

    public function Register() {
        if (!Users::Registered($this->steamid64)) {
            Users::Register($this->steamid64);
        }
    }

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




}