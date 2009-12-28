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

<script language="javascript" type="text/javascript" src="/shared/calendar/datetimepicker_css.js">

//Date Time Picker script- by TengYong Ng of http://www.rainforestnet.com
//Script featured on JavaScript Kit (http://www.javascriptkit.com)
//For this script, visit http://www.javascriptkit.com
</script>

<script>

var globalId;
function commentPost(id, myid, type)
{
   
   xmlHttp = CreateXMLHttpRequest();
   globalId = id;
   var url = 'navigation/registerVote.php?id='+id+'&myid='+myid;
   xmlHttp.onreadystatechange = callback;
   xmlHttp.open("GET", url, true);
   xmlHttp.send(null);
}

function callback()
{
  if (xmlHttp.readyState == 4)
  {
    if (xmlHttp.status == 200)
    {
        var response = xmlHttp.responseText;
        document.getElementById(globalId).innerHTML = response;
    }
  }
}

</script>

<body>

   <?php 
   $myuid = $facebook->api_client->users_GetLoggedInUser();
   $role = $database->get_player_role($game_id, $myuid);
   $state = $database->get_player_state($game_id, $myuid);
   
   $game = $database->get_game_details($game_id);
   $round_state = $game['round_state'] == ROUND_STATE_NIGHT ? "Night" : "Day";
 ?>  
   I am not a mafia because ...
   <div class="edit_area" id="div_2"> I have written come crap, ok?.</div>
   <!--  Display calendar  -->
   Deadline for Round 1:
   <input id="deadline" type="text" size="25">
   <a href="javascript:NewCssCal('deadline', 'MMddyyyy', 'Arrow', 'true', '12', 'true')">
   <img src="/calendar/images/cal.gif" alt="Pick a date" /></a>
<?php

   
   
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
 
 
<script type="text/javascript">
    $("#userSlide").jCarouselLite({
    btnNext: ".next",
    btnPrev: ".prev",
    mouseWheel: true,
    visible: 3,
    circular:false
    });
 </script>
 
   <button class="prev">left</button>
   <button class="next">right</button>  
   <div id="userSlide">
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
      <button type="submit" class="simple" OnClick = 'commentPost("<?php echo $uid ?>", "<?php echo $myuid?>")';><?php echo $button_name ?> </button>
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
</body>



