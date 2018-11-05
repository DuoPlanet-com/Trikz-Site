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

/////////////////////
/// Code Examples ///
///////******////////


// Grab user data from steamid64
$user = new User("76561198075806077"); // Input is steamid 64 bit. Must be a string since php operates on 32 bit
$user->personaName; // Returns 'Mr. Somebody' as it is my personaname on steam

// List of data on the user
var_dump($user);

// Grab the user that is currently online
// A user must be online for this to work. Returns false if no user is online
$user = Users::CurrentUser();   // The user that has been logged in
$user->avatar;                  // Returns the profile image in thumbnail size
$user->avatarMedium;            // Returns the profile image in medium size
$user->avatarFull;              // Returns the profile image in full size

// Converting steamids
Users::SteamID("76561198075806077"); // Returns STEAM_0:1:57770174. Note php is 32 bit and thus the id must be a string, otherwise php wont be able to process it!
Users::SteamID32("STEAM_0:1:57770174"); // Returns [U:1:115540349]

// Grabbing registered users
Users::All(); // Grabs all users as 'User' objects.
Users::Exists("76561198075806077"); // Returns true if user is registered and false if it does not exist
Users::Get("76561198075806077"); // Grabs a user from the database and returns as User object.

// Registering new users
//     Users::Register("76561198075806077"); // Inserts the steamid into the database a long with a TIMESTAMP provided by mysql.

// Grabbing steam data
Users::GrabSteamData("76561198075806077"); // Returns an object. Example: Users::GrabSteamData("76561198075806077")->personaname yields 'Mr.Somebody'

// Fooling around

