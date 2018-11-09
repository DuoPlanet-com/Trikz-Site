<?php
/**
 * Created by PhpStorm.
 * User: andre
 * Date: 09-11-2018
 * Time: 00:43
 */


$validate = true;
if (isset($_GET['log'])) {
    $validate = false;
}

if (isset($_GET['ps'])) {
    $validate = false;
}


if ($validate) {
    if ($user = Users::CurrentUser()) {
        $steamid64 = $user->steamid64;
        if (Users::IsAdmin($user->steamid64)) {
            $type = "admin";
        } else {
            $type = "user";
        }
    } else {
        $type = "guest";
        $steamid64 = "N/A";
    }
    global $page;
    $pageName = $page->PageString();
    $ip = $_SERVER['REMOTE_ADDR'];

    $sql = "INSERT INTO `visits`(`steamid`, `type`, `page`, `address`) VALUES ('$steamid64','$type','$pageName','$ip')";

    if (!Database::Query($sql)) {

        die("Unable to log! Query failed");
    }
}