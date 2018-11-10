<?php
/**
 * Created by PhpStorm.
 * User: andre
 * Date: 08-11-2018
 * Time: 14:06
 */

use PayPal\Api\Payment;
use PayPal\Api\PaymentExecution;

require_once 'classes/paypal/Transferral.php';
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


if (!isset($_GET['success'],$_GET['paymentId'],$_GET['PayerID'])) {
    header("Location:index.php?ps=".$_GET['success']);
}

if ((bool)$_GET['success'] === false) {
    header("Location:index.php?ps=".$_GET['success']);
}

$paymentId = $_GET['paymentId'];
$payerId = $_GET['PayerID'];

$transferral = new Transferral($payerId,$paymentId);

include 'paymentactions/'.$transferral->Product().'.php';
