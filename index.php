<?php

error_reporting(E_ALL & ~E_NOTICE); // Many notices are progged by the SteamAuthentication plugin, which exemptions i wont be handling.
require 'steamauth/steamauth.php';
require 'steamauth/userInfo.php';
require 'steamauth/SteamConfig.php';

require_once 'classes/Settings.php';

require_once 'classes/Database.php';
new Database(); // Set up database.

require_once 'classes/Users.php';
require_once 'classes/SteamUser.php';


// Check if logged in
if ($user = Users::CurrentUser()) {
    // If we've been logged in, check if the user is registered
    if (!Users::Registered($user->steamid64)) {
        // If we are not registered, register the user
        Users::Register($user->steamid64);
    }
    // Display the logout link
    echo '<a href="steamauth/logout.php">LOGOUT</a>';
} else {
    // If we are not logged in, display login button
    echo loginbutton("rectangle");
}
