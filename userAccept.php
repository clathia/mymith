<?php
/*
 * Copyright 2009 MiTH.  All Rights Reserved. 
 *
 * Application: MiTH (Mafia in The House)
 * File: 'inviteSelection.php'
 */
require_once 'mithkeys.php';

$has_authorized = $_POST['fb_sig_authorize'];
$user_accept_timestamp = $_POST['fb_sig_time'];
$uid = $_POST['fb_sig_user'];
$user_last_profile_update = $_POST['fb_sig_profile_update_time'];
$session_key = $_POST['fb_sig_session_key'];
$expiration = $_POST['fb_sig_expires'];
$api_key = $_POST['fb_sig_api_key'];
$linked_ids = $_POST['fb_sig_linked_account_ids'];
$sig = $_POST['fb_sig'];


?>
