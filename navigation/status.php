<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<head>
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

<link rel="stylesheet" type="text/css" href="styles.css?16" />

<script type="text/javascript">
var globalId;
function statusRegisterVote(id, myid, type)
{  
   xmlHttp = CreateXMLHttpRequest();
   globalId = id;
   var url = 'navigation/registerVote.php?id='+id+'&myid='+myid;
   xmlHttp.onreadystatechange = statusCallback;  
   xmlHttp.open("GET", url, true);
   xmlHttp.send(null);
}

function statusCallback()
{
  if (xmlHttp.readyState == 4)
  {
    if (xmlHttp.status == 200)
    {
        var response = xmlHttp.responseText;
        alert(globalId);
        document.getElementById(globalId).innerHTML = response;
    }
  }
}
</script>
</head>

<body>

   <?php 
   $myuid = $facebook->api_client->users_GetLoggedInUser();
   $role = $database->get_player_role($game_id, $myuid);
   $state = $database->get_player_state($game_id, $myuid);
   
   $game = $database->get_game_details($game_id);
   $round_state = $game['round_state'] == ROUND_STATE_NIGHT ? "Night" : "Day";
   ?>  
   I am not a mafia 'coz ...
   <div class="edit_area" id="div_2"> I have written come crap, ok?.</div>
   <br /><br />
   
   <input id="deadline" type="datetime-local" min="2009-08-01T12:15" max="2009-09-08T13:25"></input> 

   <?php

   
   echo "<br /><br />";
   echo "You are !<br />";
   
   switch ($role) {
    case PLAYER_ROLE_CIVILIAN:
       $img = "Civilian.jpg";
       $button_name = $round_state == "Night" ? "none" : "Vote";
       break;
    case PLAYER_ROLE_MAFIA:
       $img = "Mafia.jpg";
       $button_name = $round_state == "Night" ? "Kill" : "Vote";
       break;
    case PLAYER_ROLE_DOCTOR:
       $img = "Doctor.jpg";
       $button_name = $round_state == "Night" ? "Heal" : "Vote"; 
       break;
    case PLAYER_ROLE_INSPECTOR:
       $img = "Inspector.jpg";
       $button_name = $round_state == "Night" ? "Ask" : "Vote";
       break;
    case PLAYER_ROLE_GOD:
       $img = "God.jpg";
       $button_name = "none";
       break;
   }
   
   echo "<img src=\"images/". $img . "\" />";
   
   echo "<br /><br />";
   echo "Game State: ".$round_state." ".$game['curr_round'];
   echo "<br /><br />";
   
   /* TODO: Remove god from players list */
   $players_alive = $database->get_players_by_state($game_id, PLAYER_STATE_ALIVE);
   $alive = count($players_alive);
   
   /* TODO: Remove god from players list and -1 should be replace with PLAYER_STATE_ALL */
   $total = count($database->get_players_by_state($game_id, -1)) - 1;
   
   echo "Players Alive: ".$alive."/".$total."<br />";

   $alive = count($database->get_players_by_state_role($game_id, PLAYER_STATE_ALIVE, PLAYER_ROLE_MAFIA));
   $total = count($database->get_players_by_role($game_id, PLAYER_ROLE_MAFIA));
   echo "Mafias Alive: ".$alive."/".$total."<br />";
   ?>
 
   <button class="prev">left</button>
   <button class="next">right</button>  
   <div class="userSlide">
   <ul>

   <?php 
      for ($i = 0; $i < count($players_alive); $i++) {
      	$uid = $players_alive[$i]['uid'];
         $user = get_user_info($uid, $facebook);
   ?>
   <li>
   <table>
   <tr><td><a target = '_blank' href ="<?php echo $user['profile_url']?>"><img src="<?php echo $user['pic_square']?>" /></a></td></tr>
   <tr><td>
   <div class="buttons">
      <button type="submit" class="simple" onclick = 'statusRegisterVote("<?php echo $uid ?>", "<?php echo $myuid?>")';><?php echo $button_name ?> </button>
   </div> <!-- End buttons -->
   </td></tr>
   <tr><td>
   <div id="<?php echo $uid ?>"> 
   <?php echo count($database->get_votes_against($game_id, 1 ,$uid)) ?>
   </div>
   </td></tr>
   </table>
   </li>

      <?php
      }
      ?>
   </ul>
   </div> <!--  userSlide -->
   <br /><br />
</body>



