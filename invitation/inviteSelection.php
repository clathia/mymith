<?php
/*
 * Copyright 2009 MiTH.  All Rights Reserved. 
 *
 * Application: MiTH (Mafia in The House)
 * File: 'inviteSelection.php'
 */
require_once 'mithkeys.php';

$ids = $_GET["ids"];

if(isset($ids)) {

   // Create new game, set admin, set game status as not started
   // Make new entries into <gameid, fbid, accepted/not>
   $admin_id = $facebook->api_client->users_getLoggedInUser();
   create_new_game($admin_id, $ids);
   echo "Congratulations on inviting " . sizeof($ids) . "friends";
   echo "You invited ". print_r($ids);
} else {
   echo "Too bad, you won't be able to play game.";
}

?>


