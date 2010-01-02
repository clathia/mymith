<?php
/*
 * Copyright 2009 MiTH.  All Rights Reserved. 
 *
 * Application: MiTH (Mafia in The House)
 * File: 'mithkeys.php' 
 */
require_once($_SERVER['DOCUMENT_ROOT'] . "/shared/mithkeys.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/sql/database.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/shared/helper.php");
$game_id = 5;

$myuid = $facebook->api_client->users_GetLoggedInUser();
$role = $database->get_player_role($game_id, $myuid);
$state = $database->get_player_state($game_id, $myuid);

$game = $database->get_game_details($game_id);
$round_state = $game['round_state'] == ROUND_STATE_NIGHT ? "Night" : "Day";
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
$img = "images/".$img;

/* TODO: Remove god from players list */
$players_alive = $database->get_players_by_state($game_id, PLAYER_STATE_ALIVE);
$players_alive_num = count($players_alive);
/* TODO: Remove god from players list and -1 should be replace with PLAYER_STATE_ALL */
$total_players_num = count($database->get_players_by_state($game_id, -1)) - 1;

$mafias_alive_num = count($database->get_players_by_state_role($game_id, PLAYER_STATE_ALIVE, PLAYER_ROLE_MAFIA));
$total_mafias_num = count($database->get_players_by_role($game_id, PLAYER_ROLE_MAFIA));
?>

<div class="statusTab">

   <div class="notMafia">
      <div class="textForNotMafia">
      I am not a mafia because...
      </div> <!-- End div.textForNotMafia -->

      <div class="editStatus" id="div_2">
      I have written come crap, ok?.
      </div> <!-- End div.editStatus -->
   </div> <!-- End div.notMafia -->


   <div class="setDeadline">
      <div class="textForSetDeadline">
      </div> <!-- End div.textForSetDeadline -->

      <div class="editDeadline">
         <input id="deadline" type="datetime-local" min="2009-08-01T12:15" max="2009-09-08T13:25">
         </input> <!-- End input.deadline -->
      </div> <!-- End div.editDeadline -->
   </div>


   <div class="yourRole">
      <div class="textForYourRole">
      You Are...
      </div> <!-- End div.textForYourRole -->

      <div class="picForYourRole">
         <img src=<?php echo $img ?> />
      </div> <!-- End div.picForYourRole -->
   </div> <!-- End div.yourRole -->


   <div class="gameStatus">
      <div class="gameState">
      Game State: <?php echo $round_state ?> <?php echo $game['curr_round'] ?>
      </div> <!-- End div.gameState -->

      <div class="playersAlive">
      Players Alive: <?php echo $players_alive_num ?>/<?php echo $total_players_num ?>
      </div> <!-- End div.playersAlive -->

      <div class="mafiasAlive">
      Mafias Alive: <?php echo $mafias_alive_num ?>/<?php echo $total_mafias_num ?>
      </div> <!-- End div.mafiasAlive -->
   </div> <!-- End div.gameState -->

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
         <tr><td><a target='_blank' href="<?php echo $user['profile_url'] ?>"><img src="<?php echo $user['pic_square'] ?>" /></a></td></tr>
         <tr><td>
         <div class="buttons">
            <button type="submit" class="simple" onclick = 'mithStatusRegisterVote("<?php echo $uid ?>", "<?php echo $myuid?>")';><?php echo $button_name ?> </button>
         </div> <!-- End div.buttons -->
         </td></tr>
         <tr><td>
         <div id="<?php echo $uid ?>"> 
            <?php echo count($database->get_votes_against($game_id, 1 ,$uid)) ?>
         </div> <!-- End div#uid -->
         </td></tr>
         </table>
         </li>
      <?php
      }
      ?>
      </ul>
   </div> <!--  End div.userSlide -->

</div> <!-- End div.statusTab -->
