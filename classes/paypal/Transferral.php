<?php
/**
 * Created by PhpStorm.
 * User: andre
 * Date: 10-11-2018
 * Time: 13:03
 */

use PayPal\Api\Payment;
use PayPal\Api\PaymentExecution;

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

class Transferral {
    private
        $payment,
        $execute,
        $result;

    public function __construct($payerId,$paymentId)
    {
        global $paypal; // Paypal context
        $this->payment = Payment::get($paymentId,$paypal);
        $this->execute = new PaymentExecution();
        $this->execute->setPayerId($payerId);
    }

    public function Execute() {
        global $paypal;
        try {
            $this->result = $this->payment->execute($this->execute,$paypal);
        } catch (Exception $e) {
            die($e);
        }
        $this->CloseTransaction();
        return true;
    }

    function CloseTransaction() {
        $transactionId = $this->payment->transactions[0]->invoice_number;
        $sql = "SELECT * FROM `transactions` WHERE `id` = '$transactionId'";
        if ($query = Database::Query($sql)) {
            if ($query->num_rows == 1) {
                if ($query->fetch_assoc()['status'] == "open") {
                    $sql2 = "UPDATE `transactions` SET `status` = 'closed' WHERE `id` = '$transactionId'";
                    return Database::Query($sql2);
                }
            }
        }
        die("Failed to close transaction : Transaction not found!");
    }

    function Product() {
        $transactionId = $this->payment->transactions[0]->invoice_number;
        $sql = "SELECT * FROM `transactions` WHERE `id` = '$transactionId'";
        if ($query = Database::Query($sql)) {
            if ($query->num_rows == 1) {
                return $query->fetch_assoc()['product'];
            }
        }
        die("Error! transaction not found");
    }


}