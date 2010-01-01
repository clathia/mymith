<?php
require_once($_SERVER['DOCUMENT_ROOT'] . "/sql/database.php");
$myid = $_POST['myid'];
$id = $_POST['id'];

$database->cast_vote(5, $myid, $id);
$voteCount = count($database->get_votes_against(5, 1, $id));
header('Content-type: text/plain');
   $arr = array ( "ret"       => 0,
                  "voteCount" => $voteCount);
   echo json_encode($arr);
?>
