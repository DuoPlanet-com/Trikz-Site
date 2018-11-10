<?php
/**
 * Created by PhpStorm.
 * User: andre
 * Date: 10-11-2018
 * Time: 12:22
 */


use PayPal\Api\Payer;
use PayPal\Api\Item;
use PayPal\Api\ItemList;
use PayPal\Api\Details;
use PayPal\Api\Amount;
use PayPal\Api\Transaction;
use PayPal\Api\RedirectUrls;
use PayPal\Api\Payment;


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



$settings = Settings::GetSettings();


if (!$user = Users::CurrentUser()) {
    header("Location:index.php?ps=false");
} else {
   $checkout = new Checkout(uniqid(),"VIP","VIP",10,"USD");

    $id = $user->steamid;

    $sql = "SELECT * FROM vips WHERE steamid = '$id'";
    if (Database::Query($sql)->num_rows == 0) {
            header( "Location:". $checkout->Create());
    } else {
        header("Location: index.php?ps=false");
    }
}