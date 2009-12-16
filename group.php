<?php
/*
 * Copyright 2009 MiTH.  All Rights Reserved. 
 *
 * Application: MiTH (Mafia in The House)
 * File: 'group.php'
 */
require_once 'mithkeys.php';
$user_id = $facebook->api_client->users_getLoggedInUser();
has_accepted($user_id);
?>


