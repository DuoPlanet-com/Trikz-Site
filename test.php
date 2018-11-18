<?php

error_reporting(E_ALL & ~E_NOTICE);
require_once 'classes/paypal/Checkout.php';
require_once 'classes/Settings.php';
require_once 'classes/Users.php';
require_once 'steamauth/steamauth.php';
require_once 'steamauth/userInfo.php';
require_once 'steamauth/SteamConfig.php';
require_once 'classes/Database.php';
new Database();
require_once 'classes/SteamUser.php';
require_once 'classes/Page.php';
require_once 'classes/Modules.php';
require_once 'start.php';


/////////////////////
/// Code Examples ///
///////******////////

/** Want to query the database? */
/* $query = Database::Query("SELECT * FROM registered_users"); // Returns mysqli result
 * foreach($query as  $item) {
 *     var_dump($item)
 * }
 */


/** Need to utilize settings? */
$settings = Settings::GetSettings();// Grab the settings from settings.json as multi-dimensional array.
var_dump($settings);                // Display available settings
$settings['database']['host'];      // Returns the database host. I.e:'localhost'


/** Grab user data from steamid64 */
$user = new SteamUser("76561198075806077");   // Input is steamid 64 bit. Must be a string since php operates on 32 bit
$user->personaName;                                     // Returns 'Mr. Somebody' as it is my personaname on steam
/** List of data on the SteamUser */
var_dump($user);
/** Grab the user that is currently online */
$user = Users::CurrentUser();   // The user that has been logged in. Returns false if nobody is logged in.
$user->avatar;                  // Returns the profile image in thumbnail size
$user->avatarMedium;            // Returns the profile image in medium size
$user->avatarFull;              // Returns the profile image in full size


/** Converting steamids */
Users::SteamID("76561198075806077");    // Returns STEAM_0:1:57770174. Note php is 32 bit and thus the id must be a string, otherwise php wont be able to process it!
Users::SteamID32("STEAM_0:1:57770174"); // Returns [U:1:115540349]


/** Grabbing registered users */
Users::All();                                     // Grabs all users as an array of 'SteamUser' objects.
Users::Registered("76561198075806077"); // Returns true if user is registered and false if it does not exist
Users::Get("76561198075806077");        // Grabs a user from the database and returns as SteamUser object.


/** Registering new users */
//     Users::Register("76561198075806077"); // Inserts the steamid into the database a long with a TIMESTAMP provided by mysql.
//     Or $user->Register();


/** Grabbing steam data */
Users::GrabSteamData("76561198075806077"); // Returns an object. Example: Users::GrabSteamData("76561198075806077")->personaname yields 'Mr.Somebody'


/** What page are we on? */
// $page->PageString() // Returns 'home' if none are specified.


/** Is a user also a donor? */
Users::IsDonor("76561198075806077");    // Returns false if not. Returns int if true. Integer represent the amount the user has donated
// $user->IsDonor();                              // Returns false if not. Returns int if true. Integer represent the amount the user has donated


/** Is a user also VIP? */
Users::IsVIP("76561198075806077");    // returns false if not. Returns true if the user is VIP.
// $user->IsVIP()                                // returns false if not. Returns true if the user is VIP.

/** Total amount donated */
Users::Donations(); // Returns float representing the amount donated.


/** Total product revenue */
Users::Revenue("VIP"); // Returns float representing the revenue earned from the 'VIP' product.
// || For testing ||
// vv             vv


?>

