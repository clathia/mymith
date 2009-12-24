<html><body><h1>

<?php

include_once("constants.php");
include_once("database.php");

$database = new db_manager;
$admin_id = 100;
$ids = array(101, 102, 103, 104, 105);
/*
$result = $database->create_new_game($admin_id, $ids);
$game_id = $result;

$result = $database->set_player_state($game_id, 101, PLAYER_STATE_ALIVE);
$result = $database->set_player_state($game_id, 102, PLAYER_STATE_ALIVE);
$result = $database->set_player_state($game_id, 103, PLAYER_STATE_ALIVE);
$result = $database->set_player_state($game_id, 104, PLAYER_STATE_ALIVE);

$result = $database->set_player_role($game_id, 101, PLAYER_ROLE_MAFIA);
$result = $database->set_player_role($game_id, 102, PLAYER_ROLE_CIVILIAN);
$result = $database->set_player_role($game_id, 103, PLAYER_ROLE_DOCTOR);
$result = $database->set_player_role($game_id, 104, PLAYER_ROLE_INSPECTOR);

$result = $database->start_game($game_id);


$result = $database->add_comment($game_id, 101, "abc", COMMENT_TYPE_MAFIA);
$result = $database->add_comment($game_id, 102, "def", COMMENT_TYPE_CITY);
$result = $database->add_comment($game_id, 103, "xyz", COMMENT_TYPE_CITY);
*/
$game_id = 5;
$round = 1;
//number
$result = $database->get_total_comments($game_id, $round, COMMENT_TYPE_CITY);
echo "<br \>";
echo $result;

//array of (comment_id, uid, text, timestamp)
$result = $database->get_comments($game_id, $round, COMMENT_TYPE_CITY, 3);
$i = 0;
while ($row = $result[$i]) {
	echo "<br \>";
	echo $row["comment_id"];
	echo "<br \>";
	echo $row["uid"];
    echo "<br \>";
	echo $row["text"];
    echo "<br \>";
	echo $row["timestamp"];
	echo "<br \>";
	$i++;
}

//array of (comment_id, uid, text, timestamp)
$result = $database->get_prev_comments($game_id, $round, COMMENT_TYPE_CITY, 3, 1);
$i = 0;
while ($row = $result[$i]) {
	echo "<br \>";
	echo $row["comment_id"];
	echo "<br \>";
	echo $row["uid"];
    echo "<br \>";
	echo $row["text"];
    echo "<br \>";
	echo $row["timestamp"];
	echo "<br \>";
	$i++;
}

//array of (comment_id, text, timestamp)
$result = $database->get_comments_by($game_id, $round, COMMENT_TYPE_MAFIA, 101);
$i = 0;
while ($row = $result[$i]) {
	echo "<br \>";
	echo $row["comment_id"];
	echo "<br \>";
	echo $row["text"];
    echo "<br \>";
	echo $row["timestamp"];
	echo "<br \>";
	$i++;
}

/*
$result = $database->cast_vote($game_id, $vote_by, $vote_against);
//uid
$result = $database->get_max_votes($game_id, $round);
//array of (uid, vote)
$result = $database->get_all_votes($game_id, $round);
//array of uid
$result = $database->get_votes_against($game_id, $round, $uid);
//uid
$result = $database->get_votes_by($game_id, $round, $uid);


$result = $database->mark_game_finished($game_id);
*/
?>

Check it out!

</h1></body></html>
