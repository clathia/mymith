<?php require_once($_SERVER['DOCUMENT_ROOT'] . "/shared/mithkeys.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/sql/database.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/shared/helper.php");

if ($_POST) {

   $uid = $facebook->api_client->users_GetLoggedInUser();
   $publish_date = $mysqldate = date( 'Y-m-d H:i:s', time());
   $bgcolor = $_POST['BgColor'];
   $borderColor = $_POST['BorderColor'];
   $text = $_POST["commentText"];//rteSafe($_POST["commentText"]);
   $type = $_POST["type"];
   $game_id = 5;

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
   }
}

echo display_comment($bgcolor, $borderColor, $uid, $publish_date, $text);
?>
