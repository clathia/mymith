<?php
/*
 * Copyright 2009 MiTH.  All Rights Reserved. 
 *
 * Application: MiTH (Mafia in The House)
 * File: 'mithkeys.php' 
 */
require_once($_SERVER['DOCUMENT_ROOT'] . "/shared/mithkeys.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/sql/database.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/shared/head.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/shared/helper.php");
$game_id = 5;
?>

<head>
<link rel="stylesheet" type="text/css" href="/styles.css?2" />


<script type="text/javascript">


</script>

</head>

<body>
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
   ?>
 
   <button class="prev"><<</button>
   <button class="next">>></button>
   <div class="userSlide">
   <ul>
   <?php 
      for ($i = 0; $i < count($players_alive); $i++) {
         $user = get_user_info($players_alive[$i]['uid'], $facebook);
   ?>
   
   <li>
   <table>
   <tr><td><a target = '_blank' href ="<?php echo $user['profile_url']?>"><img src="<?php echo $user['pic_square']?>" /></a></td></tr>
   <tr><td>
   <div class="buttons">
      <button type="submit" class="simple"> vote </button>
   </div> <!-- End buttons -->
   </td></tr>
   </table>
   </li>

      <?php
      }
      ?>
   </ul>
   </div> <!--  userSlide -->
</body>



