<?php

error_reporting(E_ALL & ~E_NOTICE);
require 'steamauth/steamauth.php';
require 'steamauth/userInfo.php';
require 'steamauth/SteamConfig.php';

require_once 'classes/Settings.php';
require_once 'classes/Database.php';
new Database();
require_once 'classes/Users.php';
require_once 'classes/User.php';



if ($user = Users::CurrentUser()) {
    if (!Users::Exists($user->steamid64)) {
        Users::Register($user->steamid64);
    }
    echo '<a href="steamauth/logout.php">LOGOUT</a>';
} else {
    echo loginbutton("rectangle");
}
