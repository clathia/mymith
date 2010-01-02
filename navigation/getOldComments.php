<?php
require_once($_SERVER['DOCUMENT_ROOT'] . "/shared/helper.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/sql/database.php");

if ($_POST) {
   // check who is posting this request. getloggedinuser is required here. 
   $lastComment = $_POST['lastComment'];
   $commentType = $_POST['type'];
   $comment = $database->get_prev_comments(5, 1, $commentTtype, 20, $lastComment);
   $count = count($comment) - 1;

   header('Content-type: text/plain');
   $arr = array ( "ret" => 0,
                 'numOldComments'=> count($comment),
                 'comments' => $comment,
                 'lastCommentId' => $comment[$count]['comment_id']);
   echo json_encode($arr);

}
?>