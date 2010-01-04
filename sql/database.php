<?php

/* The return types are mentioned over the function names. These are the return
 * types for success cases. For failures, FALSE is returned. If not specified,
 * the function returns TRUE for success, and FALSE for failure.
 */
/* Turn off error reporting on live site */
error_reporting(E_ALL);
require_once($_SERVER['DOCUMENT_ROOT'] . "/sql/constants.php");
class db_manager
{
   var $connection;       //The MySQL database connection

   function __construct()
   {
      /* Make connection to database */
      $this->connection = mysql_connect(DB_SERVER, DB_USER, DB_PASS) or die(mysql_error());
      mysql_select_db(DB_NAME, $this->connection) or die(mysql_error());
   }

   function __destruct()
   {
      mysql_close($this->connection);
   }

   function run_query($q)
   {
      $result = mysql_query($q, $this->connection);
      return $result;
   }


   /* Validation Functions */
   function id_valid($id)
   {
      if ($id <= 0)
         return FALSE;
      return TRUE;
   }

   function round_valid($round)
   {
      if ($round <= 0)
         return FALSE;
      return TRUE;
   }

   function game_state_valid($state)
   {
      if (($state < GAME_STATE_MIN) || ($state > GAME_STATE_MAX))
         return FALSE;
      return TRUE;
   }

   function player_role_valid($role)
   {
      if (($role < PLAYER_ROLE_MIN) || ($role > PLAYER_ROLE_MAX))
         return FALSE;
      return TRUE;
   }

   function player_state_valid($state)
   {
      if (($state < PLAYER_STATE_MIN) || ($state > PLAYER_STATE_MAX))
         return FALSE;
      return TRUE;
   }

   function comment_type_valid($type)
   {
      if (($type < COMMENT_TYPE_MIN) || ($type > COMMENT_TYPE_MAX))
         return FALSE;
      return TRUE;
   }


   /* Game Functions */
   //Game_Id
   function create_new_game($admin_id, $ids)
   {
      if (!$this->id_valid($admin_id) || (count($ids) == 0)) {
         return FALSE;
      }
      foreach ($ids as $id) {
         if ($id <= 0) {
            return FALSE;
         }
      }

      //add a row in game table
      $q = "INSERT INTO ".TBL_GAMES." (admin_id) VALUES ('$admin_id')";
      $result = $this->run_query($q, $this->connection);
      if ($result == FALSE) {
         return FALSE;
      }
      $game_id = mysql_insert_id();
      $ret = $game_id;

      //add admin_id in players table
      $q = "INSERT INTO ".TBL_PLAYERS." (uid, game_id, state, role) VALUES ('$admin_id', '$game_id', ".PLAYER_STATE_GOD.", ".PLAYER_ROLE_GOD.")";
      $result = $this->run_query($q, $this->connection);
      if ($result == FALSE) {
         $ret = FALSE;
      }

      //add all uids in players table
      foreach ($ids as $id) {
         $q = "INSERT INTO ".TBL_PLAYERS." (uid, game_id) VALUES ('$id', '$game_id')";
         $result = $this->run_query($q, $this->connection);
         if ($result == FALSE) {
            $ret = FALSE;
         }
      }
      return $ret;
   }

   function start_game($game_id)
   {
      $q = "UPDATE ".TBL_GAMES." SET game_state = ".GAME_STATE_STARTED.", curr_round = '1' WHERE game_id = '$game_id'";
      $result = $this->run_query($q);
      if ($result == FALSE) {
         return FALSE;
      }

      //Delete all players who have not yet accepted
      $q = "DELETE FROM ".TBL_PLAYERS." WHERE game_id = '$game_id' AND state = ".PLAYER_STATE_INVITED."";
      $result = $this->run_query($q);
      if ($result == FALSE) {
         return FALSE;
      }
      return TRUE;
   }

   //Associative array of game fields
   function get_game_details($game_id)
   {
      $q = "SELECT * FROM ".TBL_GAMES." WHERE game_id = '$game_id'";
      $result = $this->run_query($q);
      if (($result == FALSE) || (mysql_num_rows($result) == 0)) {
         return FALSE;
      }
      $row = mysql_fetch_assoc($result);
      return $row;
   }

   function mark_game_finished($game_id)
   {
      if (!$this->id_valid($game_id)) {
         return FALSE;
      }
      return TRUE;
   }


   /* Comment Functions */
   function add_comment($game_id, $uid, $text, $type)
   {
      if (!($this->id_valid($game_id)) || !($this->id_valid($uid)) || !($this->comment_type_valid($type))) {
         return FALSE;
      }

      global $comment_type_arr;
      $comment_type = $comment_type_arr[$type];

      //Read last comment number for game_id
      $q = "SELECT curr_round, $comment_type FROM ".TBL_GAMES." WHERE game_id = '$game_id'";
      $result = $this->run_query($q);
      if (($result == FALSE) || (mysql_num_rows($result) == 0)) {
         return FALSE;
      }
      $arr = mysql_fetch_row($result);
      $round = $arr[0];
      $comm_id = $arr[1];

      //Increment the value in the table
      $comm_id++;
      $q = "UPDATE ".TBL_GAMES." SET $comment_type = '$comm_id' WHERE game_id = '$game_id'";
      $result = $this->run_query($q);
      if ($result == FALSE) {
         return FALSE;
      }

      $timestamp = time();
      //Add comment to comments
      $q = "INSERT INTO ".TBL_COMMENTS." (comment_id, game_id, round, uid, type, text, timestamp) VALUES ('$comm_id', '$game_id', '$round', '$uid', '$type', '$text', '$timestamp')";
      $result = $this->run_query($q);
      if ($result == FALSE) {
         return FALSE;
      }
      return TRUE;
   }

   //Number of Comments
   function get_total_comments($game_id, $round, $type)
   {
      if (!$this->id_valid($game_id)) {
         return FALSE;
      }

      $q = "SELECT COUNT(*) FROM ".TBL_COMMENTS."
          WHERE game_id = '$game_id' AND round = '$round' AND type = '$type'";
      $result = $this->run_query($q);
      if ($result == FALSE) {
         return FALSE;
      }
      $num = mysql_result($result, 0);
      return $num;
   }

   //Array of (comment_id, uid, text, timestamp)
   function get_comments($game_id, $round, $type, $num_comments)
   {
      if (!$this->id_valid($game_id) || !$this->round_valid($round)) {
         return FALSE;
      }

      $q = "SELECT comment_id, uid, text, timestamp FROM ".TBL_COMMENTS."
          WHERE game_id = '$game_id' AND round = '$round' AND type = '$type'
          ORDER BY comment_id DESC LIMIT $num_comments";
      $result = $this->run_query($q);
      if ($result == FALSE) {
         return FALSE;
      }
      $arr = array();
      while ($row = mysql_fetch_assoc($result)) {
         $arr[] = $row;
      }
      return $arr;
   }

   //Array of (comment_id, uid, text, timestamp)
   /*
    * sajain: Changed ASC -> DESC and comment <= '$last_comment' to comment < '$last_comment
    * i.e less than or equal to less than.
    */
   function get_prev_comments($game_id, $round, $type, $num_comments, $last_comment)
   {
      if (!$this->id_valid($game_id)) {
         return FALSE;
      }

      $q = "SELECT comment_id, uid, text, timestamp FROM ".TBL_COMMENTS."
          WHERE game_id = '$game_id' AND round = '$round'
          AND type = '$type' AND comment_id < '$last_comment'
          ORDER BY comment_id DESC LIMIT $num_comments";
      $result = $this->run_query($q);
      if ($result == FALSE) {
         return FALSE;
      }

      $arr = array();
      while ($row = mysql_fetch_assoc($result)) {
         $arr[] = $row;
      }
      return $arr;
   }

   //Array of (comment_id, uid, text, timestamp)
   /* sajain: Added get_next_comments function.
    * Changed ASC -> DESC and comment <= '$last_comment' to comment < '$last_comment
    * i.e less than or equal to less than.
    */
   function get_next_comments($game_id, $round, $type, $num_comments, $last_comment)
   {
      if (!$this->id_valid($game_id)) {
         return FALSE;
      }

      $q = "SELECT comment_id, uid, text, timestamp FROM ".TBL_COMMENTS."
          WHERE game_id = '$game_id' AND round = '$round'
          AND type = '$type' AND comment_id > '$last_comment'
          ORDER BY comment_id DESC LIMIT $num_comments";
      $result = $this->run_query($q);
      if ($result == FALSE) {
         return FALSE;
      }

      $arr = array();
      while ($row = mysql_fetch_assoc($result)) {
         $arr[] = $row;
      }
      return $arr;
   }
   
   
   //Array of (comment_id, text, timestamp)
   function get_comments_by($game_id, $round, $type, $uid)
   {
      if (!$this->id_valid($game_id) || !$this->id_valid($uid)) {
         return FALSE;
      }

      $q = "SELECT comment_id, text, timestamp FROM ".TBL_COMMENTS."
          WHERE game_id = '$game_id' AND round = '$round'
          AND type = '$type' AND uid = '$uid'
          ORDER BY comment_id DESC";
      $result = $this->run_query($q);
      if ($result == FALSE) {
         return FALSE;
      }

      $arr = array();
      while ($row = mysql_fetch_assoc($result)) {
         $arr[] = $row;
      }
      return $arr;
   }


   /* Player functions */
   function set_player_state($game_id, $uid, $state)
   {
      if (!$this->id_valid($game_id) || !$this->id_valid($uid) || !$this->game_state_valid($state)) {
         return FALSE;
      }

      //set this user's state to $state
      $q = "UPDATE ".TBL_PLAYERS." SET state = '$state' WHERE uid = '$uid' AND game_id = '$game_id'";
      $result = $this->run_query($q, $this->connection);
      if ($result == FALSE) {
         return FALSE;
      }
      return TRUE;
   }

   //Player State
   function get_player_state($game_id, $uid)
   {
      if (!$this->id_valid($game_id) || !$this->id_valid($uid)) {
         return FALSE;
      }

      $q = "SELECT state FROM ".TBL_PLAYERS." WHERE uid = '$uid' AND game_id = '$game_id'";
      $result = $this->run_query($q, $this->connection);
      if ($result == FALSE) {
         return FALSE;
      }
      $num = mysql_result($result, 0);
      return $num;
   }

   //Array of (uid, {state})
   function get_players_by_state($game_id, $state)
   {
      if (!($this->id_valid($game_id))) {
         return FALSE;
      }

      if(!($this->player_state_valid($state))) {
         $q = "SELECT uid, state FROM ".TBL_PLAYERS." WHERE game_id = '$game_id'";
      } else {
         $q = "SELECT uid FROM ".TBL_PLAYERS." WHERE game_id = '$game_id' AND state = '$state'";
      }
      $result = $this->run_query($q, $this->connection);
      if ($result == FALSE) {
         return FALSE;
      }

      $arr = array();
      while ($row = mysql_fetch_assoc($result)) {
         $arr[] = $row;
      }
      return $arr;
   }

   function set_player_role($game_id, $uid, $role)
   {
      if (!$this->id_valid($game_id) || !$this->id_valid($uid) || !$this->player_role_valid($role)) {
         return FALSE;
      }

      $q = "UPDATE ".TBL_PLAYERS." SET role = '$role' WHERE uid = '$uid' AND game_id = '$game_id'";
      $result = $this->run_query($q, $this->connection);
      if ($result == FALSE) {
         return FALSE;
      }
      return TRUE;
   }


   //Player Role
   function get_player_role($game_id, $uid)
   {
      if (!$this->id_valid($game_id) || !$this->id_valid($uid)) {
         return FALSE;
      }

      $q = "SELECT role FROM ".TBL_PLAYERS." WHERE uid = '$uid' AND game_id = '$game_id'";
      $result = $this->run_query($q, $this->connection);
      if ($result == FALSE) {
         return FALSE;
      }
      $num = mysql_result($result, 0);
      return $num;
   }

   //Array of (uid, role)
   function get_players_by_role($game_id, $role)
   {
      if (!($this->id_valid($game_id))) {
         return FALSE;
      }

      if(!($this->player_role_valid($role))) {
         $q = "SELECT uid, role FROM ".TBL_PLAYERS." WHERE game_id = '$game_id'";
      } else {
         $q = "SELECT uid, role FROM ".TBL_PLAYERS." WHERE game_id = '$game_id' AND role = '$role'";
      }
      $result = $this->run_query($q, $this->connection);
      if ($result == FALSE) {
         return FALSE;
      }

      $arr = array();
      while ($row = mysql_fetch_assoc($result)) {
         $arr[] = $row;
      }
      return $arr;
   }


   //Array of uid,
   function get_players_by_state_role($game_id, $state, $role)
   {
      if (!($this->id_valid($game_id)) || !($this->player_state_valid($state)) || !($this->player_role_valid($role))) {
         return FALSE;
      }

      $q = "SELECT uid FROM ".TBL_PLAYERS." WHERE game_id = '$game_id' AND state = '$state' AND role = '$role'";
      $result = $this->run_query($q, $this->connection);
      if ($result == FALSE) {
         return FALSE;
      }

      $arr = array();
      while ($row = mysql_fetch_assoc($result)) {
         $arr[] = $row;
      }
      return $arr;
   }


   /* Voting */
   function cast_vote($game_id, $vote_by, $vote_against)
   {
      if (!$this->id_valid($game_id) || !$this->id_valid($vote_by) || !$this->id_valid($vote_against)) {
         return FALSE;
      }
      
      //Get current round
      $q = "SELECT curr_round FROM ".TBL_GAMES." WHERE game_id = '$game_id'";
      $result = $this->run_query($q);
      if (($result == FALSE) || (mysql_num_rows($result) == 0)) {
         return FALSE;
      }

      $round = mysql_result($result, 0);
      if ($round <= 0) {
         return FALSE;
      }

      //Cast Vote
      $q = "INSERT INTO ".TBL_VOTES." (game_id, uid, round, vote) VALUES ('$game_id', '$vote_by', '$round', '$vote_against')";
      $result = $this->run_query($q, $this->connection);
      if ($result == FALSE) {
         return FALSE;
      }
      return TRUE;
   }

   //Array of (uid, num_votes)
   function get_num_votes($game_id, $round)
   {
      if (!$this->id_valid($game_id) || !$this->round_valid($round)) {
         return FALSE;
      }

      //Get alive players list
      $result = $this->get_players_by_state($game_id, PLAYER_STATE_ALIVE);
      if ($result == FALSE) {
         return FALSE;
      }

      $count = count($result);

      //Build default array
      $arr = array();
      for ($i = 0; $i < $count; $i++) {
         $arr[$i]["uid"] = $result[$i]["uid"];
         $arr[$i]["num_votes"] = 0;
      }

      //Get votes
      $q = "SELECT vote, COUNT(*) as num_votes FROM ".TBL_VOTES." WHERE game_id = '$game_id' AND round = '$round' GROUP BY vote";
      $result = $this->run_query($q, $this->connection);
      if ($result == FALSE) {
         
         return FALSE;
      }

      //Update array
      while ($row = mysql_fetch_assoc($result)) {
         for ($i = 0; $i < $count; $i++) {
            if ($arr[$i]["uid"] == $row["vote"]) {
               $arr[$i]["num_votes"] = $row["num_votes"];
            }
         }
      }

      return $arr;
   }

   //Array of (voteby, voteagainst)
   function get_all_votes($game_id, $round)
   {
      if (!$this->id_valid($game_id) || !$this->round_valid($round)) {
         return FALSE;
      }

      $q = "SELECT uid, vote FROM ".TBL_VOTES." WHERE game_id = '$game_id' AND round = '$round'";
      $result = $this->run_query($q, $this->connection);
      if ($result == FALSE) {
         return FALSE;
      }

      $arr = array();
      while ($row = mysql_fetch_assoc($result)) {
         $arr[] = $row;
      }
      return $arr;
   }

   //Array of uid
   function get_votes_against($game_id, $round, $uid)
   {
      if (!$this->id_valid($game_id) || !$this->round_valid($round) || !$this->id_valid($uid)) {
         return FALSE;
      }

      $q = "SELECT uid FROM ".TBL_VOTES." WHERE game_id = '$game_id' AND round = '$round' AND vote = '$uid'";
      $result = $this->run_query($q, $this->connection);
      if ($result == FALSE) {
         return FALSE;
      }

      $arr = array();
      while ($row = mysql_fetch_assoc($result)) {
         $arr[] = $row;
      }
      return $arr;
   }

   //uid
   function get_votes_by($game_id, $round, $uid)
   {
      if (!$this->id_valid($game_id) || !$this->round_valid($round) || !$this->id_valid($uid)) {
         return FALSE;
      }

      $q = "SELECT vote FROM ".TBL_VOTES." WHERE game_id = '$game_id' AND round = '$round' AND uid = '$uid'";
      $result = $this->run_query($q, $this->connection);
      if ($result == FALSE) {
         return FALSE;
      }
      $num = mysql_result($result, 0);
      return $num;
   }
}

$database = new db_manager;

/*
Log
 */
?>
