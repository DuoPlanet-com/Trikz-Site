<?php

require_once '../classes/Settings.php';

$returnUrl = Settings::GetSettingsDir("../")['steam']['logout_url'];

session_start();
unset($_SESSION['steamid']);
unset($_SESSION['steam_uptodate']);
header("Location: ../$returnUrl");