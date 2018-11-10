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

$productName = $_GET['product'];

if (!$user = Users::CurrentUser()) {
    header("Location:index.php?ps=false");
} else {
    $steamId64 = $user->steamid64;
    $steamId = $user->steamid;

    if ($productName != "donation") {
        if (isset($settings['products'][$productName])) {
            $checkout = new Checkout(
                uniqid(),
                $productName,
                "",
                $settings['products'][$productName]['price'],
                $settings['products'][$productName]['currency'],
                $steamId64);
            header( "Location:". $checkout->Create());
        } else {
            header( "Location: index.php?ps=false");
        }
    } else {
        $price = $_POST['donationAmount'];
        if ($price >= 1) {
            if (isset($settings['products'][$productName])) {
                $checkout = new Checkout(
                    uniqid(),
                    $productName,
                    "",
                    $price,
                    $settings['products'][$productName]['currency'],
                    $steamId64);
                header( "Location:". $checkout->Create());
            } else {
                header( "Location: index.php?ps=false");
            }
        } else {
            header( "Location: index.php?ps=false");
        }
    }
}