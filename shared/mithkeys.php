<?php
/*
 * Copyright 2009 MiTH.  All Rights Reserved. 
 *
 * Application: MiTH (Mafia in The House)
 * File: 'mithkeys.php' 
 */
require_once($_SERVER['DOCUMENT_ROOT'] . "/facebook.php");

if (php_uname("n") == "CHIRAG-RVBD") {

    $appapikey = '33014e7146e7a9eeb1492444ecb14c2a';
    $appsecret = '480dd8ef0bf2b8745d44a61554f58195';
    $appid = '202231853621';

} else {

    $appapikey = '10d020f9dcb70b3d5aeebc0124ddd387';
    $appsecret = 'd78b6c6367e274a9c55cdfd81f7f3b87';
    $appid = '201321733598';
}

$facebook = new Facebook($appapikey, $appsecret);
$user_id = $facebook->require_login();
error_reporting(E_ALL);
?>
