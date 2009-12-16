<?php
/*
 * Copyright 2009 MiTH.  All Rights Reserved. 
 *
 * Application: MiTH (Mafia in The House)
 * File: 'mithkeys.php' 
 */

require_once 'facebook.php';

$appapikey = '10d020f9dcb70b3d5aeebc0124ddd387';
$appsecret = 'd78b6c6367e274a9c55cdfd81f7f3b87';
$appid = '201321733598';
$facebook = new Facebook($appapikey, $appsecret);
$user_id = $facebook->require_login();

?>
