<?php
require_once("/var/www/mithgit/sql/database.php");
$database->cast_vote(5, $_GET['myid'], $_GET['id']);
echo print_r($database->get_votes_against(5, 1, $_GET['id']));
?>