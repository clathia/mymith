<?php
require_once($_SERVER['DOCUMENT_ROOT'] . "/shared/helper.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/sql/database.php");

if ($_POST) {
   // check who is posting this request. getloggedinuser is required here.	
   $lastComment = $_POST['lastComment'];
   $commentType = $_POST['type'];
   $oldOrNew = $_POST['oldOrNew'];
   
   if ($oldOrNew == "old") {
      $comment = $database->get_prev_comments(5, 1, $commentType, 20, $lastComment);
   } else if ($oldOrNew == "new") {
   	$comment = $database->get_next_comments(5, 1, $commentType, 20, $lastComment);      
   }
   
   $count = count($comment);
   if ($count && $oldOrNew == "old") {
   	$lastCommentId = $comment[$count - 1]['comment_id'];
   } else if ($count && $oldOrNew == "new") {
   	$lastCommentId = $comment[0]['comment_id'];
   }
   
   for ($i = 0; $i < $count; $i++) {
   	$comment[$i]['timestamp'] = strtotime($comment[$i]['timestamp']);
   }
   
   header('Content-type: text/plain');

   if ($count) {
      $arr = array ("ret" => 0,
                    "numComments" => $count,
                    "comments" => $comment,
                    "lastCommentId" => $lastCommentId);	
   } else {
   	$arr = array ("ret" => 0,
   	              "numComments" => 0);
   }
   echo json_encode($arr);

}

?>