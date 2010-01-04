<?php
/*
 * Copyright 2009 MiTH.  All Rights Reserved. 
 *
 * Application: MiTH (Mafia in The House)
 * File: 'inviteSelection.php'
 */
require_once $_SERVER['DOCUMENT_ROOT'] . "/shared/mithkeys.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/sql/database.php";

$ids = $_GET["ids"];

if(isset($ids)) {

   // Create new game, set admin, set game status as not started
   // Make new entries into <gameid, fbid, accepted/not>
   $adminId = $facebook->api_client->users_getLoggedInUser();
   $database->create_new_game($adminId, $ids);
   echo "Congratulations on inviting " . sizeof($ids) . "friends";
   echo "You invited ". print_r($ids);
} else {
   echo "Too bad, you won't be able to play game.";
}

?>


