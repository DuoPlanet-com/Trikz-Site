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

/**
 * Class Transferral handles the payment and prompts the money to be transferred.
 *
 * The Transferral class will retrieve the transaction from PayPal using the paymentId and payerId,
 *     both are provided by PayPal and are sent to the site upon finishing the payment on PayPal.
 *     The money wont be transferred until we execute the payment. The method
 *     Execute() prompts the money to be transferred and if it returns true then
 *     the payment was successful, which means we can give a user VIP or whatever.
 *
 * @author Andreas M. Henriksen <AndreasHenriksen@yahoo.dk>
 */
class Transferral {

    private
        $payment,
        $execute,
        $result;

    /**
     * Sets up an instance of 'Transferral' by retrieving the transaction Data from PayPal.
     *
     * It combines the parameters given into an instance of 'Payment' which
     *     we can use to execute a payment and verify whether or not it was
     *     successful. The parameters are defined by PayPal, and are sent via
     *     $_GET to the return URL specified. Ex: www.example.org/pay.php
     *
     * @param $payerId - Defined by PayPal. Grab through $_GET['payerId'].
     * @param $paymentId - Defined by PayPal. Grab through $_GET['paymentId'].
     */
    public function __construct($payerId,$paymentId)
    {
        global $paypal; // Paypal context
        $this->payment = Payment::get($paymentId,$paypal);
        $this->execute = new PaymentExecution();
        $this->execute->setPayerId($payerId);
    }

    /**
     * Executes the payment, transferring the money from the buyer to the seller.
     *
     * @return bool - Returns true if the payment was successful.
     */
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

    /**
     * Queries the database and locates the transaction, then set the status to closed upon successful payments.
     *
     * If query fails nothing will be returned and the script will die()
     *
     * @return mysqli_result - Returns the query result.
     */
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

    /**
     * Queries the database selecting the row of this transaction, returning the product type.
     *
     * @return string - The type of product this is, in string format. Ex: 'VIP'
     */
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

    /**
     * Returns the price of the transaction.
     *
     * @return float - The price of the transaction
     */
    public function Price() {
        return $this->payment->transactions[0]->amount->total;
    }

}