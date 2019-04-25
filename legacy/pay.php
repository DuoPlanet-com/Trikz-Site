<?php
/**
 * Created by PhpStorm.
 * User: andre
 * Date: 08-11-2018
 * Time: 14:06
 */

use PayPal\Api\Payment;
use PayPal\Api\PaymentExecution;

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


if (!isset($_GET['success'],$_GET['paymentId'],$_GET['PayerID'])) {
    header("Location:index.php?ps=".$_GET['success']);
}

if ((bool)$_GET['success'] === false) {
    header("Location:index.php?ps=".$_GET['success']);
}

$paymentId = $_GET['paymentId'];
$payerId = $_GET['PayerID'];

$payment = Payment::get($paymentId,$paypal);

$execute = new PaymentExecution();
$execute->setPayerId($payerId);

if (!$user = Users::CurrentUser()) {
    header("Location:index.php?ps=false");
} else {

    $id = $user->steamid;

    $id = Database::Escaped($id);

    $sql = "SELECT * FROM vips WHERE steamid = '$id'";

    if (($query = Database::NonEscapedQuery($sql))->num_rows == 0) {
        try {
            $result = $payment->execute($execute,$paypal);
        } catch (Exception $e) {
            die($e);
        }
        if ($_GET['success'] == "true") {
            $dateTime = new DateTime();
            $days = (int) Settings::GetSettings()['VIP']['days'];
            $nextTime = new DateTime();
            $nextTime->add(new DateInterval('P' . $days . 'D'));
            $dateTimeStamp = $dateTime->format("Y-m-d H:m:s") . ".000000";
            $nextTimeStamp = $nextTime->format("Y-m-d H:m:s") . ".000000";
            $sid64 = $user->steamid64;

            $sid64 = Database::Escaped($sid64);

            $sql = "INSERT INTO vips (steamid64, steamid,timestamp_start,timestamp_end) VALUES ('$sid64','$id','$dateTimeStamp','$nextTimeStamp')";
            if (!DataBase::NonEscapedQuery($sql)) {
                $_GET['success'] = "false";
            }
            header("Location:index.php?ps=".$_GET['success']);
        }
    } else {
        var_dump($query);
        header("Location:index.php?ps=false");
    }

}