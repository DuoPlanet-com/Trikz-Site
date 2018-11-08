<?php
/**
 * Created by PhpStorm.
 * User: andre
 * Date: 08-11-2018
 * Time: 13:27
 */
require_once 'classes/Settings.php';
require  'vendor/autoload.php';

define('SITE_URL','http://localhost/trikz');

$paypal = new \PayPal\Rest\ApiContext(
    new \PayPal\Auth\OAuthTokenCredential(
        Settings::GetSettings()['paypal']['client_id'],
        Settings::GetSettings()['paypal']['secret_id']
    )
);