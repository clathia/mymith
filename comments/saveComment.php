<?php require_once($_SERVER['DOCUMENT_ROOT'] . "/mithkeys.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/sql/database.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/core/helper.php");

if($_POST )
{

$uid = $facebook->api_client->users_GetLoggedInUser();
$user_details = $facebook->api_client->users_GetInfo($user_id, 'last_name, first_name, profile_url, pic_square'); 
$first_name = $user_details[0]['first_name']; 
$last_name = $user_details[0]['last_name']; 
$profile_url = $user_details[0]['profile_url'];
$pic_square = $user_details[0]['pic_square'];
if (! $pic_square) {
   $pic_square = "/images/nullImage.gif";
}

$publish_date = $mysqldate = date( 'Y-m-d H:i:s', time());
$bgcolor = $_POST['BgColor'];
$borderColor = $_POST['BorderColor'];
$full_name = $first_name." ".$last_name;
$text = $_POST["CommentText"];//rteSafe($_POST["CommentText"]);
$type = $_POST["type"];
$game_id = 5;

   if ($type == 0){
      $ret = $database->add_comment($game_id, $uid, $text, COMMENT_TYPE_CITY);
      if ($ret == FALSE) {
         echo "Error inserting into database";
      }
   } else if ($type == 1){
      $database->add_comment($game_id, $uid, $text, COMMENT_TYPE_MAFIA);
   } else if ($type == 2) {
      $ret = $database->add_comment($game_id, $uid, $text, COMMENT_TYPE_GOD);
      if ($ret == FALSE) {
         echo "Error inserting into database";
      }
   }
}

echo display_comment($bgcolor, $borderColor, $profile_url, $pic_square, $full_name, $publish_date, $text);
?>
