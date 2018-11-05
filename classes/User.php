<?php
/**
 * Created by PhpStorm.
 * User: andre
 * Date: 04-11-2018
 * Time: 21:14
 */
class User {

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

    function __construct($steamid_64)
    {
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
    }



}