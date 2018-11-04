<?php


require_once 'classes/Settings.php';
require_once 'classes/Database.php';
require_once 'classes/Users.php';

new Database();

$sql = "SELECT * FROM registered_users";

if (Users::Exists("tesst")) {
    echo "yes";
} else{
    echo "No";
}