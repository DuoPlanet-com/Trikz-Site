<?php
/**
 * Created by PhpStorm.
 * User: andre
 * Date: 10-11-2018
 * Time: 11:56
 */

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

use PayPal\Api\Payer;
use PayPal\Api\Item;
use PayPal\Api\ItemList;
use PayPal\Api\Details;
use PayPal\Api\Amount;
use PayPal\Api\Transaction;
use PayPal\Api\RedirectUrls;
use PayPal\Api\Payment;

/**
 * Class Checkout sets up transactions and handles them before they are paid.
 *
 * The class logs transactions as 'open', which means not yet paid,
 *     assign it with a unique transaction id, which will be used as invoice number
 *     and sends it, along with the price, description and currency used, to paypal.
 *
 * @author Andreas M. Henriksen <AndreasHenriksen@yahoo.dk>
 */
class Checkout {

    private
        $payer,
        $item,
        $itemList,
        $details,
        $amount,
        $transaction,
        $redirectUrl,
        $payment,
        $transactionId;

    /**
     * Sets up an instance of 'Checkout' for payment.
     *
     * Upon creating an instance of 'Checkout', the class will prepare the data
     *     that we will be sending off to PayPal for processing. It includes data such as
     *     the name, price, description, transaction ID, and pairs the transaction to a
     *     'SteamUser'. This is where the transaction first will be logged.
     *     However the data wont be sent off to PayPal until $this->Create() has been run.
     *
     * @param string $productName - The name of the product. (Required by PayPal)
     * @param string $productDescription - The description of the product. (Required by PayPal)
     * @param float $amountPrice - The price of the product.
     * @param string $currency - The currency that the payment would deal in. Ex: "USD", "DKK", etc.
     * @param string $steamId64 - The 64-bit steam community id as a string. Ex: "76561198075806077"
     */
    public function __construct($productName, $productDescription, $amountPrice, $currency,$steamId64)
    {
        $id = $this->CreateID();
        while (!$this->CheckID($id)) {
            $id = $this->CreateID();
        }
        $this->transactionId = $id;
        $settings = Settings::GetSettings();
        $sUrl = $settings['products'][$productName]['successUrl'];
        $cUrl = $settings['products'][$productName]['cancelUrl'];

        $price = $amountPrice;
        $product = $productName;
        $this->payer = new Payer();
        $this->payer->setPaymentMethod("paypal");

        $this->item = new Item();
        $this->item->setName($product)
            ->setCurrency($currency)
            ->setQuantity(1)
            ->setPrice($price);

        $this->itemList = new ItemList();
        $this->itemList->setItems([$this->item]);

        $this->details = new Details();
        $this->details->setShipping(0)
            ->setSubtotal($price);

        $this->amount = new Amount();
        $this->amount->setCurrency($currency)
            ->setTotal($price)
            ->setDetails($this->details);

        $this->transaction = new Transaction();
        $this->transaction->setAmount($this->amount)
            ->setItemList($this->itemList)
            ->setDescription($productDescription)
            ->setInvoiceNumber($this->transactionId);

        $this->redirectUrl = new RedirectUrls();
        $this->redirectUrl->setReturnUrl(SITE_URL . $sUrl)
            ->setCancelUrl(SITE_URL . $cUrl);

        $this->payment = new Payment();
        $this->payment->setIntent("sale")
            ->setPayer($this->payer)
            ->setRedirectUrls($this->redirectUrl)
            ->setTransactions([$this->transaction]);

        if (!$this->LogTransaction($steamId64,$productName,$amountPrice)) {
            die("Could not log transaction!");
        }

    }

    /**
     * Creates a transaction for PayPal payment processing.
     *
     * Sends off the data set by the constructor. A transaction will be registered on PayPal's server
     *     which then will be sent back to us, with a confirmation, upon paying.
     *
     * @return string - PayPal approval link. Will be the link paying users will be sent to.
     */
    public function Create() {
        global $paypal;
        try {
            $this->payment->create($paypal);
        } catch(Exception $e) {
            die($e);
        }
        return $this->payment->getApprovalLink();
    }

    /**
     * Logs the transaction and set its status to open.
     *
     * Inserts a row into the 'transactions' table with the Steam Community ID
     *     of the buyer. The status of the transaction will be set to 'open', which means the
     *     payment has not been processed or confirmed by PayPal.
     *
     * @param $steamId64 - The 64-bit Steam community id as a string. Ex: "76561198075806077"
     * @param $product - The name of the product. (Required by PayPal)
     * @param $price - The price of the product.
     * @return mysqli_result
     */
    function LogTransaction($steamId64,$product,$price) {
        $id = $this->transactionId;
        $sql = "INSERT INTO `transactions` (`id`,`steamid64`,`product`,`price`,`status`) VALUES ('$id','$steamId64','$product',$price,'open')";
        return Database::Query($sql);
    }

    /**
     * Pseudo-randomly generates a 16-character long hexadecimal ID.
     *
     * @return string - 16-character long hexadecimal ID.
     */
    function CreateID() {
        $result = "";
        $length = 16;
        for ($i=0; $i < $length; $i++) {
            $result.= dechex(mt_rand(0,$length-1));
        }
        return $result;
    }

    /**
     * Check if ID is already occupied by another transaction in the database.
     *
     * @param $id - 16-character long hexadecimal ID
     * @return bool - Returns true if the ID has not yet been used.
     */
    function CheckID($id) {
        $sql = "SELECT * FROM `transactions` WHERE `id` = '$id'";
        if ($query = Database::Query($sql)) {
            if ($query->num_rows == 0) {
                return true;
            }
        }
        return false;
    }
}