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

class Checkout {

    private
        $payer,
        $item,
        $itemList,
        $details,
        $amount,
        $transaction,
        $redirectUrl,
        $payment;


    public function __construct($transactionId, $productName, $productDescription, $amountPrice, $currency,$steamId64)
    {
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
            ->setInvoiceNumber($transactionId);

        $this->redirectUrl = new RedirectUrls();
        $this->redirectUrl->setReturnUrl(SITE_URL . $sUrl)
            ->setCancelUrl(SITE_URL . $cUrl);

        $this->payment = new Payment();
        $this->payment->setIntent("sale")
            ->setPayer($this->payer)
            ->setRedirectUrls($this->redirectUrl)
            ->setTransactions([$this->transaction]);

        if (!$this->LogTransaction($transactionId,$steamId64,$productName,$amountPrice)) {
            die("Could not log transaction!");
        }

    }

    function LogTransaction($transactionId,$steamId64,$product,$price) {
        $sql = "INSERT INTO `transactions` (`id`,`steamid64`,`product`,`price`,`status`) VALUES ('$transactionId','$steamId64','$product',$price,'open')";
        var_dump($sql);
        return Database::Query($sql);
    }

    public function Create() {
        global $paypal;
        try {
            $this->payment->create($paypal);
        } catch(Exception $e) {
            die($e);
        }
        return $this->payment->getApprovalLink();
    }
}