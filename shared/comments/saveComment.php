<?php header('Content-type: text/plain');require_once($_SERVER['DOCUMENT_ROOT'] . "/shared/mithkeys.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/sql/database.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/shared/helper.php");

if ($_POST) {
	/*	 * Really I cannot trust user to give the correct UID. Otherwise,	 * faster alternative would have been to let the client send the UID.	 * I hate to affect everybody for those malicious users. Sorry.	 */
   $uid = $facebook->api_client->users_GetLoggedInUser();      /* Remove add slashes when chirag adds mysql escaping to it */
   //$text = get_magic_quotes_gpc() ? $_POST["commentText"] : addslashes($_POST["commentText"]);//rteSafe($_POST["commentText"]);   $text = $_POST["commentText"];   /* Remove \n before storing into the database */   $text = preg_replace("/\\n/"," ", $text);   $type = $_POST["type"];
   $game_id = 5;
   /*    * Adding user stuff directly to database. Risky!     */
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
   }   $result = array (             'ret'  => 0,             'uid' => $uid             );
   echo json_encode($result);}?>
