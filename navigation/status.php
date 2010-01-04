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

$mithStGameId = 5;

$mithStMyUid = $facebook->api_client->users_GetLoggedInUser();
$mithStRole = $database->get_player_role($mithStGameId, $mithStMyUid);
$mithStState = $database->get_player_state($mithStGameId, $mithStMyUid);

$mithStGame = $database->get_game_details($mithStGameId);
$mithStRoundState = $mithStGame['round_state'] == ROUND_STATE_NIGHT ? "Night" : "Day";
switch ($mithStRole) {
 case PLAYER_ROLE_CIVILIAN:
    $mithStRoleImg = "Civilian.jpg";
    $mithStButtonName = $mithStRoundState == "Night" ? "none" : "Vote";
    break;
 case PLAYER_ROLE_MAFIA:
    $mithStRoleImg = "Mafia.jpg";
    $mithStButtonName = $mithStRoundState == "Night" ? "Kill" : "Vote";
    break;
 case PLAYER_ROLE_DOCTOR:
    $mithStRoleImg = "Doctor.jpg";
    $mithStButtonName = $mithStRoundState == "Night" ? "Heal" : "Vote";
    break;
 case PLAYER_ROLE_INSPECTOR:
    $mithStRoleImg = "Inspector.jpg";
    $mithStButtonName = $mithStRoundState == "Night" ? "Ask" : "Vote";
    break;
 case PLAYER_ROLE_GOD:
    $mithStRoleImg = "God.jpg";
    $mithStButtonName = "none";
    break;
}
$mithStRoleImg = "images/".$mithStRoleImg;

/* TODO: Remove god from players list */
$mithStPlayersAlive = $database->get_players_by_state($mithStGameId, PLAYER_STATE_ALIVE);
$mithStPlayersAliveNum = count($mithStPlayersAlive);
/* TODO: Remove god from players list and -1 should be replace with PLAYER_STATE_ALL */
$mithStTotalPlayersNum = count($database->get_players_by_state($mithStGameId, -1)) - 1;

$mithStMafiasAliveNum = count($database->get_players_by_state_role($mithStGameId, PLAYER_STATE_ALIVE, PLAYER_ROLE_MAFIA));
$mithStTotalMafiasNum = count($database->get_players_by_role($mithStGameId, PLAYER_ROLE_MAFIA));
?>

<div class="mithDefense">
   <div class="mithTextForDefense">
   I am not a mafia because...
   </div> <!-- End div.mithTextForDefense -->

   <div class="mithEditDefense" id="div_2">
   I have written come crap, ok?.
   </div> <!-- End div.mithEditDefense -->
</div> <!-- End div.mithDefense -->


<div class="mithSetDeadline">
   <div class="mithTextForDeadline">
      Set Deadline
   </div> <!-- End div.mithTextForDeadline -->

   <div class="mithDeadlineBox">
      <input id="deadline" type="datetime-local" min="2009-08-01T12:15" max="2009-09-08T13:25">
   </div> <!-- End div.mithDeadlineBox -->
</div>


<div class="mithYourRole">
   <div class="mithTextForYourRole">
      You Are...
   </div> <!-- End div.mithTextForYourRole -->

   <div class="mithPicForYourRole">
      <img src=<?php echo $mithStRoleImg ?> />
   </div> <!-- End div.mithPicForYourRole -->
</div> <!-- End div.mithYourRole -->


<div class="mithGameStatus">
   <div class="mithGameState">
      Game State: <?php echo $mithStRoundState ?> <?php echo $mithStGame['curr_round'] ?>
   </div> <!-- End div.mithGameState -->

   <div class="mithPlayersAlive">
      Players Alive: <?php echo $mithStPlayersAliveNum ?>/<?php echo $mithStTotalPlayersNum ?>
   </div> <!-- End div.mithPlayersAlive -->

   <div class="mithMafiasAlive">
      Mafias Alive: <?php echo $mithStMafiasAliveNum ?>/<?php echo $mithStTotalMafiasNum ?>
   </div> <!-- End div.mithMafiasAlive -->
</div> <!-- End div.mithGameState -->


<div class="mithCarouselBlob">
   <div class="mithCarouselBorder">
      <button class="mithCarouselNext">
         &gt;&gt;
      </button>
      <button class="mithCarouselPrev">
         &lt;&lt;
      </button>
   </div>

   <div class="mithCarousel">
      <ul>
         <?php
         for ($i = 0; $i < count($mithStPlayersAlive); $i++) {
            $mithStUid = $mithStPlayersAlive[$i]['uid'];
            $mithStQuery = "SELECT name, pic_big, profile_url FROM user WHERE uid = $mithStUid";
            $mithStResult = $facebook->api_client->fql_query($mithStQuery);
            if ($mithStResult != NULL) {
               $mithStFullName = $mithStResult[0]['name'];
               $mithStProfileUrl = $mithStResult[0]['profile_url'];
               $mithStPicUrl = $mithStResult[0]['pic_big'];
               if(!$mithStPicUrl) {
                  $mithStPicUrl = "/images/nullImage.gif";
               }
            } else {
               echo "User does not exist";
            }
            ?>
         <li>
            <table>
               <tr>
                  <td>
                     <div class="mithCarouselPic">
	                     <a target='_blank' href="<?php echo $mithStProfileUrl ?>">
   	                     <img src="<?php echo $mithStPicUrl ?>" />
      	               </a>
      	            </div>
                  </td>
               </tr>
               <tr>
                  <td>
                     <div class="mithCarouselName">
                        <a target='_blank' href="<?php echo $mithStProfileUrl ?>">
                           <?php echo $mithStFullName ?>
                        </a>
                     </div> <!-- End div.mithCarouselButtons -->
                  </td>
               </tr>
               <tr>
                  <td>
                     <div class="mithButtons">
                        <button type="submit" class="mithCarouselButtons" onclick = 'mithStatusRegisterVote("<?php echo $mithStUid ?>", "<?php echo $mithStMyUid?>")';>
                           <?php echo $mithStButtonName ?>
                        </button>
                     </div> <!-- End div.mithCarouselButtons -->
                  </td>
               </tr>
               <tr>
                  <td>
                     <div id="<?php echo $mithStUid ?>" class="mithNumVotes">
                        <?php echo count($database->get_votes_against($mithStGameId, 1, $mithStUid)) ?>
                     </div> <!-- End div.mithNumVotes -->
                  </td>
               </tr>
            </table>
         </li>
      <?php
      }
      ?>
      </ul>
   </div> <!--  End div.mithCarousel -->

   <div class="mithCarouselBorder">
   </div>
</div> <!-- End div.mithCarouselBlob -->

