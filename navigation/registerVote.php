<?php
require_once($_SERVER['DOCUMENT_ROOT'] . "/sql/database.php");

$database->cast_vote(5, $_GET['myid'], $_GET['id']);
echo count($database->get_votes_against(5, 1, $_GET['id']));
?>
