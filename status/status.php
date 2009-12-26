<?php
/*
 * Copyright 2009 MiTH.  All Rights Reserved. 
 *
 * Application: MiTH (Mafia in The House)
 * File: 'mithkeys.php' 
 */
require_once($_SERVER['DOCUMENT_ROOT'] . "/mithkeys.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/sql/database.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/core/head.php");;
$game_id = 5;
?>

<head>
<link rel="stylesheet" type="text/css" href="/styles.css?2" />
</head>

<body>
<div id="container">   <?php include($_SERVER['DOCUMENT_ROOT'] . "/core/top.layout.php"); ?>
  
   <div id="wrapper">
    <div id="content">
      <?php 
      $uid = $facebook->api_client->users_GetLoggedInUser();
      $role = $database->get_player_role($game_id, $uid);
      echo "You are!<br />";
      if ($role == PLAYER_ROLE_GOD) {
         echo "<img src=\"/images/God.jpg\" />";
      } else if ($role == PLAYER_ROLE_MAFIA) {
         echo "<img src=\"/images/Mafia.jpg\" />";
      } else if ($role == PLAYER_ROLE_CIVILIAN) {
         echo "<img src=\"/images/Civilian.jpg\" />";
      } else if ($role == PLAYER_ROLE_DOCTOR) {
         echo "<img src=\"/images/Doctor.jpg\" />";
      } else if ($role == PLAYER_ROLE_INSPECTOR) {
         echo "<img src=\"/images/Police.jpg\" />";
      }

      $game = $database->get_game_details($game_id);
      if ($game['round_state'] == ROUND_STATE_NIGHT)
         $round_state = "Night";
      else
         $round_state = "Day";
      echo "<br /><br />";
      echo "Game State: ".$round_state." ".$game['curr_round'];
      echo "<br /><br />";

      $alive = count($database->get_players_by_state($game_id, PLAYER_STATE_ALIVE));
      $total = count($database->get_players_by_state($game_id, PLAYER_STATE_MAX + 1)) - 1;
      echo "Players Alive: ".$alive."/".$total."<br />";

      $alive = count($database->get_players_by_state_role($game_id, PLAYER_STATE_ALIVE, PLAYER_ROLE_MAFIA));
      $total = count($database->get_players_by_role($game_id, PLAYER_ROLE_MAFIA));
      echo "Mafias Alive: ".$alive."/".$total."<br />";

      //Get alive players list
      $players_alive = $database->get_players_by_state($game_id, PLAYER_STATE_ALIVE);
      if ($players_alive == FALSE) {
         echo "No players alive";
         return FALSE;
      }
      for ($i = 0; $i < count($players_alive); $i++) {
         $user_details = $facebook->api_client->users_getInfo($players_alive[$i]['uid'], 'last_name, first_name, profile_url, pic_square');
         if ($user_details) { 
            $first_name = $user_details[0]['first_name']; 
            $last_name = $user_details[0]['last_name'];
            $full_name = $first_name." ".$last_name;
            $profile_url = $user_details[0]['profile_url'];
            $pic_square = $user_details[0]['pic_square'];
            if (! $pic_square) {
               $pic_square = "/images/nullImage.gif";
            }
         }
      ?>
     <tr>
       <th><a target = '_blank' href ="<?php echo $profile_url?>"><?php echo $full_name?></a></th>
       <tr width='10%'><th rowspan="2"><a target = '_blank' href ="<?php echo $profile_url?>"><img src="<?php echo $pic_square?>" /></a></th> </tr>
    </tr>
      <?php
      
      } 
      ?>
    </div>
   </div>
  
   <?php include($_SERVER['DOCUMENT_ROOT'] . "/core/bottom.layout.php"); ?>
  
</div>
</body>



