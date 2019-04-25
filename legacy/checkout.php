<?php
/**
 * Created by PhpStorm.
 * User: andre
 * Date: 08-11-2018
 * Time: 13:12
 */



use PayPal\Api\Payer;
use PayPal\Api\Item;
use PayPal\Api\ItemList;
use PayPal\Api\Details;
use PayPal\Api\Amount;
use PayPal\Api\Transaction;
use PayPal\Api\RedirectUrls;
use PayPal\Api\Payment;

require 'classes/Settings.php';
require 'classes/Users.php';
require 'steamauth/steamauth.php';
require 'steamauth/userInfo.php';
require 'steamauth/SteamConfig.php';
require_once 'classes/Database.php';
new Database();
require_once 'classes/SteamUser.php';
require_once 'classes/Page.php';
require_once 'classes/Modules.php';
require 'start.php';



$settings = Settings::GetSettings();


if (!$user = Users::CurrentUser()) {
    header("Location:index.php?ps=false");
} else {
    $price = $settings['VIP']['price'];
    $product = $settings['VIP']['name'];
    $payer = new Payer();
    $payer->setPaymentMethod("paypal");

    $item = new Item();
    $item->setName($product)
        ->setCurrency($settings['VIP']['currency'])
        ->setQuantity(1)
        ->setPrice($price);

    $itemList = new ItemList();
    $itemList->setItems([$item]);

    $details = new Details();
    $details->setShipping(0)
        ->setSubtotal($price);

    $amount = new Amount();
    $amount -> setCurrency($settings['VIP']['currency'])
        ->setTotal($price)
        ->setDetails($details);

    $transaction = new Transaction();
    $transaction->setAmount($amount)
        ->setItemList($itemList)
        ->setDescription('test')
        ->setInvoiceNumber(uniqid());

    $redirectUrl = new RedirectUrls();
    $redirectUrl->setReturnUrl(SITE_URL . '/pay.php?success=true')
        ->setCancelUrl(SITE_URL . '/pay.php?success=false');

    $payment = new Payment();
    $payment->setIntent("sale")
        ->setPayer($payer)
        ->setRedirectUrls($redirectUrl)
        ->setTransactions([$transaction]);

    $id = $user->steamid;

    $id = Database::Escaped($id);

    $sql = "SELECT * FROM vips WHERE steamid = '$id'";
    if (Database::NonEscapedQuery($sql)->num_rows == 0) {
        try {
            $payment->create($paypal);
        } catch(Exception $e) {
            die($e);
        }
        header( "Location:". $approvalUrl = $payment->getApprovalLink());
    } else {
        header("Location: index.php?ps=false");
    }
}