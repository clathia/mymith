<?php header('Content-type: text/plain');require_once($_SERVER['DOCUMENT_ROOT'] . "/shared/mithkeys.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/sql/database.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/shared/helper.php");

if ($_POST) {
	/*	 * Really I cannot trust user to give the correct UID. Otherwise,	 * faster alternative would have been to let client send the UID.	 * I hate to affect everybody for those malicious users. Sorry.	 */
   $uid = $facebook->api_client->users_GetLoggedInUser();
   $publish_date = display_date(date( 'Y-m-d H:i:s', time()));
   $text = $_POST["commentText"];//rteSafe($_POST["commentText"]);
   $type = $_POST["type"];
   $game_id = 5;
   /*    * Adding user stuff directly to database. Risky!    *     * */
   if ($type == 0) {
      $ret = $database->add_comment($game_id, $uid, $text, COMMENT_TYPE_CITY);
      if ($ret == FALSE) {
         echo "Error inserting into database";
      }
   } else if ($type == 1) {
      $database->add_comment($game_id, $uid, $text, COMMENT_TYPE_MAFIA);
   } else if ($type == 2) {
      $ret = $database->add_comment($game_id, $uid, $text, COMMENT_TYPE_GOD);
      if ($ret == FALSE) {
         echo "Error inserting into database";
      }
   }      $result = array (             'ret'  => 0,             'date' => $publish_date,             'uid' => $uid,             );
   echo json_encode($result);}
   $date = display_date($publish_date);   return <<<HTML      <div class="commentTable">      <table width='100%' cellspacing='0' align='center'>      <tr>      <td width=55px valign="top">      <fb:profile-pic uid='$uid' facebook-logo="false" size="square" linked="true">      </fb:profile-pic>      </td>      <td valign="top"><div class="fullName"><fb:name uid='$uid' linked="true" useyou="false"></fb:name>      <span class="date">$date</span></div>      <div class="commentTextOld">$text</div>      </td>      </tr>      </table>      </div>HTML;
echo display_comment($uid, $publish_date, $text);
?>
