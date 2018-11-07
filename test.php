<?php

error_reporting(E_ALL & ~E_NOTICE);
require 'steamauth/steamauth.php';
require 'steamauth/userInfo.php';
require 'steamauth/SteamConfig.php';

require_once 'classes/Settings.php';
require_once 'classes/Database.php';
new Database();
require_once 'classes/Users.php';
require_once 'classes/SteamUser.php';
require_once 'classes/Page.php';

/////////////////////
/// Code Examples ///
///////******////////

/** Want to query the database? */
/* $query = Database::Query("SELECT * FROM registered_users"); // Returns mysqli result
 * foreach($query as  $item) {
 *     var_dump($item)
 * }
 */


/** Grab user data from steamid64 */
$user = new SteamUser("76561198075806077"); // Input is steamid 64 bit. Must be a string since php operates on 32 bit
$user->personaName; // Returns 'Mr. Somebody' as it is my personaname on steam
/** List of data on the SteamUser */
var_dump($user);
/** Grab the user that is currently online */
$user = Users::CurrentUser();   // The user that has been logged in. Returns false if nobody is logged in.
$user->avatar;                  // Returns the profile image in thumbnail size
$user->avatarMedium;            // Returns the profile image in medium size
$user->avatarFull;              // Returns the profile image in full size


/** Converting steamids */
Users::SteamID("76561198075806077"); // Returns STEAM_0:1:57770174. Note php is 32 bit and thus the id must be a string, otherwise php wont be able to process it!
Users::SteamID32("STEAM_0:1:57770174"); // Returns [U:1:115540349]


/** Grabbing registered users */
Users::All(); // Grabs all users as an array of 'SteamUser' objects.
Users::Registered("76561198075806077"); // Returns true if user is registered and false if it does not exist
Users::Get("76561198075806077"); // Grabs a user from the database and returns as SteamUser object.


/** Registering new users */
//     Users::Register("76561198075806077"); // Inserts the steamid into the database a long with a TIMESTAMP provided by mysql.
//     Or $user->Register();


/** Grabbing steam data */
Users::GrabSteamData("76561198075806077"); // Returns an object. Example: Users::GrabSteamData("76561198075806077")->personaname yields 'Mr.Somebody'


/** What page are we on? */
// $page->PageString() // Returns 'home' if none are specified.

// || For testing ||
// vv             vv

?>

<!doctype html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/css/bootstrap.min.css" integrity="sha384-9gVQ4dYFwwWSjIDZnLEWnxCjeSWFphJiwGPXr1jddIhOegiu1FwO5qRGvFXOdJZ4" crossorigin="anonymous">

    <title>Hello, world!</title>
</head>
<body>
<?php $page = new Page(); ?>
<!-- Optional JavaScript -->
<!-- jQuery first, then Popper.js, then Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.0/umd/popper.min.js" integrity="sha384-cs/chFZiN24E4KMATLdqdvsezGxaGsi4hLGOzlXwp5UZB1LY//20VyM2taTB4QvJ" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/js/bootstrap.min.js" integrity="sha384-uefMccjFJAIv6A+rW+L4AHf99KvxDjWSu1z9VI8SKNVmz4sk7buKt/6v9KI65qnm" crossorigin="anonymous"></script>
</body>
</html>
