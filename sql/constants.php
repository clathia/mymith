<?php

// **** Database Constants ****
define("DB_SERVER", "localhost");  // Database Server
define("DB_NAME", "mith");           // Database Name
define("DB_USER", "root");        // Database User
define("DB_PASS", "");        // Database User Password

// **** Database Table Constants ****
define("TBL_PLAYERS", "players");
define("TBL_GAMES", "game");
define("TBL_COMMENTS", "comments");
define("TBL_VOTES", "votes");

define("GAME_STATE_MIN", 0);
define("GAME_STATE_MAX", 2);
define("ROUND_STATE_MIN", 0);
define("ROUND_STATE_MAX", 2);
define("PLAYER_STATE_MIN", 0);
define("PLAYER_STATE_MAX", 3);
define("PLAYER_ROLE_MIN", 0);
define("PLAYER_ROLE_MAX", 5);
define("COMMENT_TYPE_MIN", 0);
define("COMMENT_TYPE_MAX", 2);

define("GAME_STATE_CREATED", 0);
define("GAME_STATE_STARTED", 1);
define("GAME_STATE_OVER", 2);

define("ROUND_STATE_DAY", 0);
define("ROUND_STATE_VOTING", 1);
define("ROUND_STATE_NIGHT", 2);

define("PLAYER_STATE_INVITED", 0);
define("PLAYER_STATE_ALIVE", 1);
define("PLAYER_STATE_DEAD", 2);
define("PLAYER_STATE_GOD", 3);

define("PLAYER_ROLE_NONE", 0);
define("PLAYER_ROLE_GOD", 1);
define("PLAYER_ROLE_MAFIA", 2);
define("PLAYER_ROLE_CIVILIAN", 3);
define("PLAYER_ROLE_DOCTOR", 4);
define("PLAYER_ROLE_INSPECTOR", 5);

define("COMMENT_TYPE_CITY", 0);
define("COMMENT_TYPE_MAFIA", 1);
define("COMMENT_TYPE_GOD", 2);

$comment_type_arr = array(0 => 'comment_num_city', 1 => 'comment_num_mafia', 2 => 'comment_num_god');
?>
