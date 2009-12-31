<?php
require_once($_SERVER['DOCUMENT_ROOT'] . "/shared/helper.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/sql/database.php");

if ($_POST) {
	
   $lastComment = $_POST['lastComment'];
   $commentType = $_POST['type'];
   $comment = $database->get_next_comments(5, 1, $commentTtype, 20, $lastComment);
   $tmp = count($comment) - 1;
   header('Content-type: text/plain');
   $arr = array ('numNewComments'=>$tmp);
   echo json_encode($arr);
   //echo json_encode($tmp);
}
?>