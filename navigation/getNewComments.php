<?php
require_once($_SERVER['DOCUMENT_ROOT'] . "/shared/helper.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/sql/database.php");

if ($_POST) {
   // check who is posting this request. getloggedinuser is required here.	
   $lastComment = $_POST['lastComment'];
   $commentType = $_POST['type'];
   $comment = $database->get_next_comments(5, 1, $commentTtype, 20, $lastComment);
   $count = count($comment);
   
   header('Content-type: text/plain');

   if ($count) {
      $arr = array ("ret" => 0,
                    "numNewComments" => $count,
                    "comments" => $comment,
                    "lastCommentId" => $comment[0]['comment_id']);	
   } else {
   	$arr = array ("ret" => 0,
   	              "numNewComments" => 0);
   }
   echo json_encode($arr);
}
?>