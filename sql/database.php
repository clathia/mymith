<?php
/*
 * Copyright 2009 MiTH.  All Rights Reserved.
 *
 * Application: MiTH (Mafia in The House)
 * File: 'mafiabox.php'
 * Provides the API for database interaction.
 * The return types are mentioned over the function names. These are the return
 * types for success cases. For failures, FALSE is returned. If not specified,
 * the function returns TRUE for success, and FALSE for failure.
 */

require_once($_SERVER['DOCUMENT_ROOT'] . "/sql/constants.php");


/*------------------------------------------------------------------------------
 * mithDbManager --
 *   Connects to the db and executes commands given by the business logic.
 *
 *------------------------------------------------------------------------------
 */

class
mithDbManager
{
   private $connection;


   /*---------------------------------------------------------------------------
    * __construct --
    *   mithDbManager Constructor
    *
    *---------------------------------------------------------------------------
    */

   function
   __construct()
   {
      /* Make connection to database */
      $this->connection = mysql_connect(DB_SERVER, DB_USER, DB_PASS) or die(mysql_error());
      mysql_select_db(DB_NAME, $this->connection) or die(mysql_error());
   }


   /*---------------------------------------------------------------------------
    * __destruct --
    *   mithDbManager Destructor
    *
    *---------------------------------------------------------------------------
    */

   function
   __destruct()
   {
      mysql_close($this->connection);
   }


   /*---------------------------------------------------------------------------
    * runQuery --
    *   Executes a given SQL query
    *
    * @q The SQL query to be executed
    *
    * @return result/TRUE on success
    *         FALSE on failure
    *---------------------------------------------------------------------------
    */

   function
   runQuery($q)
   {
      $result = mysql_query($q, $this->connection);
      return $result;
   }


   /* Validation Functions */

   /*---------------------------------------------------------------------------
    * idValid --
    *   Validates the game id and user id
    *
    *   Only checks if it is a positive number
    *
    * @id The id to be validated
    *
    * @return TRUE on success
    *         FALSE on failure
    *---------------------------------------------------------------------------
    */

   function
   idValid($id)
   {
      if ($id <= 0)
         return FALSE;
      return TRUE;
   }


   /*---------------------------------------------------------------------------
    * roundValid --
    *   Validates the round number
    *
    *   Only checks if it is a positive number
    *
    * @round The round number to be validated
    *
    * @return TRUE on success
    *         FALSE on failure
    *---------------------------------------------------------------------------
    */

   function
   roundValid($round)
   {
      if ($round <= 0)
         return FALSE;
      return TRUE;
   }


   /*---------------------------------------------------------------------------
    * gameStateValid --
    *   Validates the game state value passed
    *
    * @state The game state value to be validated
    *
    * @return TRUE on success
    *         FALSE on failure
    *---------------------------------------------------------------------------
    */

   function
   gameStateValid($state)
   {
      if (($state < GAME_STATE_MIN) || ($state > GAME_STATE_MAX))
         return FALSE;
      return TRUE;
   }


   /*---------------------------------------------------------------------------
    * playerRoleValid --
    *   Validates the player role value passed
    *
    * @role The player role value to be validated
    *
    * @return TRUE on success
    *         FALSE on failure
    *---------------------------------------------------------------------------
    */

   function
   playerRoleValid($role)
   {
      if (($role < PLAYER_ROLE_MIN) || ($role > PLAYER_ROLE_MAX))
         return FALSE;
      return TRUE;
   }


   /*---------------------------------------------------------------------------
    * playerStateValid --
    *   Validates the player state value passed
    *
    * @state The player state value to be validated
    *
    * @return TRUE on success
    *         FALSE on failure
    *---------------------------------------------------------------------------
    */

   function
   playerStateValid($state)
   {
      if (($state < PLAYER_STATE_MIN) || ($state > PLAYER_STATE_MAX))
         return FALSE;
      return TRUE;
   }


   /*---------------------------------------------------------------------------
    * commentTypeValid --
    *   Validates the comment type value passed
    *
    * @type The comment type value to be validated
    *
    * @return TRUE on success
    *         FALSE on failure
    *---------------------------------------------------------------------------
    */

   function
   commentTypeValid($type)
   {
      if (($type < COMMENT_TYPE_MIN) || ($type > COMMENT_TYPE_MAX))
         return FALSE;
      return TRUE;
   }


   /* Game Functions */

   /*---------------------------------------------------------------------------
    * createNewGame --
    *   Creates a new game in the database
    *
    *   Adds a new game in 'games', adds admin as god and players as invited
    *   in 'players'
    *
    * @adminId The uid of the game admin
    * @ids     The ids of the players invited by the admin
    *
    * @return New game id on success
    *         FALSE on failure
    *---------------------------------------------------------------------------
    */

   function
   createNewGame($adminId, $ids)
   {
      if (!$this->idValid($adminId) || (count($ids) == 0)) {
         return FALSE;
      }
      foreach ($ids as $id) {
         if ($id <= 0) {
            return FALSE;
         }
      }

      //add a row in game table
      $q = "INSERT INTO ".TBL_GAMES." (admin_id) VALUES ('$adminId')";
      $result = $this->runQuery($q, $this->connection);
      if ($result == FALSE) {
         return FALSE;
      }
      $gameId = mysql_insert_id();
      $ret = $gameId;

      //add adminId in players table
      $q = "INSERT INTO ".TBL_PLAYERS." (uid, gameId, state, role) VALUES ('$adminId', '$gameId', ".PLAYER_STATE_GOD.", ".PLAYER_ROLE_GOD.")";
      $result = $this->runQuery($q, $this->connection);
      if ($result == FALSE) {
         $ret = FALSE;
      }

      //add all uids in players table
      foreach ($ids as $id) {
         $q = "INSERT INTO ".TBL_PLAYERS." (uid, gameId) VALUES ('$id', '$gameId')";
         $result = $this->runQuery($q, $this->connection);
         if ($result == FALSE) {
            $ret = FALSE;
         }
      }
      return $ret;
   }


   /*---------------------------------------------------------------------------
    * startGame --
    *   Starts a created game
    *
    *   Sets the gameState to Started, currRound to 1 and deletes players who
    *   have not yet accepted
    *
    * @gameId The gameId to be started
    *
    * @return TRUE on success
    *         FALSE on failure
    *---------------------------------------------------------------------------
    */

   function
   startGame($gameId)
   {
      $q = "UPDATE ".TBL_GAMES." SET gameState = ".GAME_STATE_STARTED.", currRound = '1' WHERE gameId = '$gameId'";
      $result = $this->runQuery($q);
      if ($result == FALSE) {
         return FALSE;
      }

      //Delete all players who have not yet accepted
      $q = "DELETE FROM ".TBL_PLAYERS." WHERE gameId = '$gameId' AND state = ".PLAYER_STATE_INVITED;
      $result = $this->runQuery($q);
      if ($result == FALSE) {
         return FALSE;
      }
      return TRUE;
   }


   /*---------------------------------------------------------------------------
    * getGameDetails --
    *   Returns a row from the game table corresponding to the gameId
    *
    * @gameId The row to be returned
    *
    * @return Associative array of 'games' table fields on success
    *         FALSE on failure
    *---------------------------------------------------------------------------
    */

   function
   getGameDetails($gameId)
   {
      $q = "SELECT * FROM ".TBL_GAMES." WHERE gameId = '$gameId'";
      $result = $this->runQuery($q);
      if (($result == FALSE) || (mysql_num_rows($result) == 0)) {
         return FALSE;
      }
      $row = mysql_fetch_assoc($result);
      return $row;
   }


   /*---------------------------------------------------------------------------
    * markGameFinished --
    *   Finishes a game
    *
    *   TODO
    *
    * @gameId The completed game
    *
    * @return TRUE on success
    *         FALSE on failure
    *---------------------------------------------------------------------------
    */

   function
   markGameFinished($gameId)
   {
      if (!$this->idValid($gameId)) {
         return FALSE;
      }
      return TRUE;
   }


   /* Comment Functions */

   /*---------------------------------------------------------------------------
    * addComment --
    *   Adds a comment to the comment table
    *
    *   TODO
    *
    * @
    *
    * @return
    *---------------------------------------------------------------------------
    */

   function
   addComment($gameId, $uid, $text, $type)
   {
      if (!($this->idValid($gameId)) || !($this->idValid($uid)) || !($this->commentTypeValid($type))) {
         return FALSE;
      }

      global $commentTypeArr;
      $commentType = $commentTypeArr[$type];

      //Read last comment number for gameId
      $q = "SELECT currRound, $commentType FROM ".TBL_GAMES." WHERE gameId = '$gameId'";
      $result = $this->runQuery($q);
      if (($result == FALSE) || (mysql_num_rows($result) == 0)) {
         return FALSE;
      }
      $arr = mysql_fetch_row($result);
      $round = $arr[0];
      $commId = $arr[1];

      //Increment the value in the table
      $commId++;
      $q = "UPDATE ".TBL_GAMES." SET $commentType = '$commId' WHERE gameId = '$gameId'";
      $result = $this->runQuery($q);
      if ($result == FALSE) {
         return FALSE;
      }

      //Add comment to comments
      $q = "INSERT INTO ".TBL_COMMENTS." (commentId, gameId, round, uid, type, text) VALUES ('$commId', '$gameId', '$round', '$uid', '$type', '$text')";
      $result = $this->runQuery($q);
      if ($result == FALSE) {
         return FALSE;
      }
      return TRUE;
   }


   /*---------------------------------------------------------------------------
    * getTotalComments --
    *   Returns the total number of comments for any round of a game
    *
    * @gameId Which game
    * @round  Which round
    * @type   What type
    *
    * @return Number of Comments on success
    *         FALSE on failure
    *---------------------------------------------------------------------------
    */

   function
   getTotalComments($gameId, $round, $type)
   {
      if (!$this->idValid($gameId)) {
         return FALSE;
      }

      $q = "SELECT COUNT(*) FROM ".TBL_COMMENTS."
          WHERE gameId = '$gameId' AND round = '$round' AND type = '$type'";
      $result = $this->runQuery($q);
      if ($result == FALSE) {
         return FALSE;
      }
      $num = mysql_result($result, 0);
      return $num;
   }


   /*---------------------------------------------------------------------------
    * getComments --
    *   Get the latest few comments
    *
    *   Returns latest numComments for a particular gameId, round and type
    *
    * @
    *
    * @return
    *---------------------------------------------------------------------------
    */

   //Array of (commentId, uid, text, timestamp)
   function
   getComments($gameId, $round, $type, $numComments)
   {
      if (!$this->idValid($gameId) || !$this->roundValid($round)) {
         return FALSE;
      }

      $q = "SELECT commentId, uid, text, timestamp FROM ".TBL_COMMENTS."
          WHERE gameId = '$gameId' AND round = '$round' AND type = '$type'
          ORDER BY commentId DESC LIMIT $numComments";
      $result = $this->runQuery($q);
      if ($result == FALSE) {
         return FALSE;
      }
      $arr = array();
      while ($row = mysql_fetch_assoc($result)) {
         $arr[] = $row;
      }
      return $arr;
   }


   /*---------------------------------------------------------------------------
    * F --
    *   O
    *
    *   D
    *
    * @
    *
    * @return
    *---------------------------------------------------------------------------
    */

   //Array of (commentId, uid, text, timestamp)
   /*
    * sajain: Changed ASC -> DESC and comment <= '$lastComment' to comment < '$lastComment
    * i.e less than or equal to less than.
    */
   function
   getPrevComments($gameId, $round, $type, $numComments, $lastComment)
   {
      if (!$this->idValid($gameId)) {
         return FALSE;
      }

      $q = "SELECT commentId, uid, text, timestamp FROM ".TBL_COMMENTS."
          WHERE gameId = '$gameId' AND round = '$round'
          AND type = '$type' AND commentId < '$lastComment'
          ORDER BY commentId DESC LIMIT $numComments";
      $result = $this->runQuery($q);
      if ($result == FALSE) {
         return FALSE;
      }

      $arr = array();
      while ($row = mysql_fetch_assoc($result)) {
         $arr[] = $row;
      }
      return $arr;
   }


   /*---------------------------------------------------------------------------
    * F --
    *   O
    *
    *   D
    *
    * @
    *
    * @return
    *---------------------------------------------------------------------------
    */

   //Array of (commentId, uid, text, timestamp)
   /* sajain: Added getNextComments function.
    * Changed ASC -> DESC and comment <= '$lastComment' to comment < '$lastComment
    * i.e less than or equal to less than.
    */
   function
   getNextComments($gameId, $round, $type, $numComments, $lastComment)
   {
      if (!$this->idValid($gameId)) {
         return FALSE;
      }

      $q = "SELECT commentId, uid, text, timestamp FROM ".TBL_COMMENTS."
          WHERE gameId = '$gameId' AND round = '$round'
          AND type = '$type' AND commentId > '$lastComment'
          ORDER BY commentId DESC LIMIT $numComments";
      $result = $this->runQuery($q);
      if ($result == FALSE) {
         return FALSE;
      }

      $arr = array();
      while ($row = mysql_fetch_assoc($result)) {
         $arr[] = $row;
      }
      return $arr;
   }


   /*---------------------------------------------------------------------------
    * F --
    *   O
    *
    *   D
    *
    * @
    *
    * @return
    *---------------------------------------------------------------------------
    */

   //Array of (commentId, text, timestamp)
   function
   getCommentsBy($gameId, $round, $type, $uid)
   {
      if (!$this->idValid($gameId) || !$this->idValid($uid)) {
         return FALSE;
      }

      $q = "SELECT commentId, text, timestamp FROM ".TBL_COMMENTS."
          WHERE gameId = '$gameId' AND round = '$round'
          AND type = '$type' AND uid = '$uid'
          ORDER BY commentId DESC";
      $result = $this->runQuery($q);
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

   /*---------------------------------------------------------------------------
    * F --
    *   O
    *
    *   D
    *
    * @
    *
    * @return
    *---------------------------------------------------------------------------
    */

   function
   setPlayerState($gameId, $uid, $state)
   {
      if (!$this->idValid($gameId) || !$this->idValid($uid) || !$this->gameStateValid($state)) {
         return FALSE;
      }

      //set this user's state to $state
      $q = "UPDATE ".TBL_PLAYERS." SET state = '$state' WHERE uid = '$uid' AND gameId = '$gameId'";
      $result = $this->runQuery($q, $this->connection);
      if ($result == FALSE) {
         return FALSE;
      }
      return TRUE;
   }


   /*---------------------------------------------------------------------------
    * F --
    *   O
    *
    *   D
    *
    * @
    *
    * @return
    *---------------------------------------------------------------------------
    */

   //Player State
   function
   getPlayerState($gameId, $uid)
   {
      if (!$this->idValid($gameId) || !$this->idValid($uid)) {
         return FALSE;
      }

      $q = "SELECT state FROM ".TBL_PLAYERS." WHERE uid = '$uid' AND gameId = '$gameId'";
      $result = $this->runQuery($q, $this->connection);
      if ($result == FALSE) {
         return FALSE;
      }
      $num = mysql_result($result, 0);
      return $num;
   }


   /*---------------------------------------------------------------------------
    * F --
    *   O
    *
    *   D
    *
    * @
    *
    * @return
    *---------------------------------------------------------------------------
    */

   //Array of (uid, {state})
   function
   getPlayersByState($gameId, $state)
   {
      if (!($this->idValid($gameId))) {
         return FALSE;
      }

      if(!($this->playerStateValid($state))) {
         $q = "SELECT uid, state FROM ".TBL_PLAYERS." WHERE gameId = '$gameId'";
      } else {
         $q = "SELECT uid FROM ".TBL_PLAYERS." WHERE gameId = '$gameId' AND state = '$state'";
      }
      $result = $this->runQuery($q, $this->connection);
      if ($result == FALSE) {
         return FALSE;
      }

      $arr = array();
      while ($row = mysql_fetch_assoc($result)) {
         $arr[] = $row;
      }
      return $arr;
   }


   /*---------------------------------------------------------------------------
    * F --
    *   O
    *
    *   D
    *
    * @
    *
    * @return
    *---------------------------------------------------------------------------
    */

   function
   setPlayerRole($gameId, $uid, $role)
   {
      if (!$this->idValid($gameId) || !$this->idValid($uid) || !$this->playerRoleValid($role)) {
         return FALSE;
      }

      $q = "UPDATE ".TBL_PLAYERS." SET role = '$role' WHERE uid = '$uid' AND gameId = '$gameId'";
      $result = $this->runQuery($q, $this->connection);
      if ($result == FALSE) {
         return FALSE;
      }
      return TRUE;
   }


   /*---------------------------------------------------------------------------
    * F --
    *   O
    *
    *   D
    *
    * @
    *
    * @return
    *---------------------------------------------------------------------------
    */

   //Player Role
   function
   getPlayerRole($gameId, $uid)
   {
      if (!$this->idValid($gameId) || !$this->idValid($uid)) {
         return FALSE;
      }

      $q = "SELECT role FROM ".TBL_PLAYERS." WHERE uid = '$uid' AND gameId = '$gameId'";
      $result = $this->runQuery($q, $this->connection);
      if ($result == FALSE) {
         return FALSE;
      }
      $num = mysql_result($result, 0);
      return $num;
   }


   /*---------------------------------------------------------------------------
    * F --
    *   O
    *
    *   D
    *
    * @
    *
    * @return
    *---------------------------------------------------------------------------
    */

   //Array of (uid, role)
   function
   getPlayersByRole($gameId, $role)
   {
      if (!($this->idValid($gameId))) {
         return FALSE;
      }

      if(!($this->playerRoleValid($role))) {
         $q = "SELECT uid, role FROM ".TBL_PLAYERS." WHERE gameId = '$gameId'";
      } else {
         $q = "SELECT uid, role FROM ".TBL_PLAYERS." WHERE gameId = '$gameId' AND role = '$role'";
      }
      $result = $this->runQuery($q, $this->connection);
      if ($result == FALSE) {
         return FALSE;
      }

      $arr = array();
      while ($row = mysql_fetch_assoc($result)) {
         $arr[] = $row;
      }
      return $arr;
   }


   /*---------------------------------------------------------------------------
    * F --
    *   O
    *
    *   D
    *
    * @
    *
    * @return
    *---------------------------------------------------------------------------
    */

   //Array of uid,
   function
   getPlayersByStateRole($gameId, $state, $role)
   {
      if (!($this->idValid($gameId)) || !($this->playerStateValid($state)) || !($this->playerRoleValid($role))) {
         return FALSE;
      }

      $q = "SELECT uid FROM ".TBL_PLAYERS." WHERE gameId = '$gameId' AND state = '$state' AND role = '$role'";
      $result = $this->runQuery($q, $this->connection);
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

   /*---------------------------------------------------------------------------
    * F --
    *   O
    *
    *   D
    *
    * @
    *
    * @return
    *---------------------------------------------------------------------------
    */

   function
   castVote($gameId, $voteBy, $voteAgainst)
   {
      if (!$this->idValid($gameId) || !$this->idValid($voteBy) || !$this->idValid($voteAgainst)) {
         return FALSE;
      }

      //Get current round
      $q = "SELECT currRound FROM ".TBL_GAMES." WHERE gameId = '$gameId'";
      $result = $this->runQuery($q);
      if (($result == FALSE) || (mysql_num_rows($result) == 0)) {
         return FALSE;
      }

      $round = mysql_result($result, 0);
      if ($round <= 0) {
         return FALSE;
      }

      //Cast Vote
      $q = "INSERT INTO ".TBL_VOTES." (gameId, uid, round, vote) VALUES ('$gameId', '$voteBy', '$round', '$voteAgainst')";
      $result = $this->runQuery($q, $this->connection);
      if ($result == FALSE) {
         return FALSE;
      }
      return TRUE;
   }


   /*---------------------------------------------------------------------------
    * F --
    *   O
    *
    *   D
    *
    * @
    *
    * @return
    *---------------------------------------------------------------------------
    */

   //Array of (uid, numVotes)
   function
   getNumVotes($gameId, $round)
   {
      if (!$this->idValid($gameId) || !$this->roundValid($round)) {
         return FALSE;
      }

      //Get alive players list
      $result = $this->getPlayersByState($gameId, PLAYER_STATE_ALIVE);
      if ($result == FALSE) {
         return FALSE;
      }

      $count = count($result);

      //Build default array
      $arr = array();
      for ($i = 0; $i < $count; $i++) {
         $arr[$i]["uid"] = $result[$i]["uid"];
         $arr[$i]["numVotes"] = 0;
      }

      //Get votes
      $q = "SELECT vote, COUNT(*) as numVotes FROM ".TBL_VOTES." WHERE gameId = '$gameId' AND round = '$round' GROUP BY vote";
      $result = $this->runQuery($q, $this->connection);
      if ($result == FALSE) {

         return FALSE;
      }

      //Update array
      while ($row = mysql_fetch_assoc($result)) {
         for ($i = 0; $i < $count; $i++) {
            if ($arr[$i]["uid"] == $row["vote"]) {
               $arr[$i]["numVotes"] = $row["numVotes"];
            }
         }
      }

      return $arr;
   }


   /*---------------------------------------------------------------------------
    * F --
    *   O
    *
    *   D
    *
    * @
    *
    * @return
    *---------------------------------------------------------------------------
    */

   //Array of (voteby, voteagainst)
   function
   getAllVotes($gameId, $round)
   {
      if (!$this->idValid($gameId) || !$this->roundValid($round)) {
         return FALSE;
      }

      $q = "SELECT uid, vote FROM ".TBL_VOTES." WHERE gameId = '$gameId' AND round = '$round'";
      $result = $this->runQuery($q, $this->connection);
      if ($result == FALSE) {
         return FALSE;
      }

      $arr = array();
      while ($row = mysql_fetch_assoc($result)) {
         $arr[] = $row;
      }
      return $arr;
   }


   /*---------------------------------------------------------------------------
    * F --
    *   O
    *
    *   D
    *
    * @
    *
    * @return
    *---------------------------------------------------------------------------
    */

   //Array of uid
   function
   getVotesAgainst($gameId, $round, $uid)
   {
      if (!$this->idValid($gameId) || !$this->roundValid($round) || !$this->idValid($uid)) {
         return FALSE;
      }

      $q = "SELECT uid FROM ".TBL_VOTES." WHERE gameId = '$gameId' AND round = '$round' AND vote = '$uid'";
      $result = $this->runQuery($q, $this->connection);
      if ($result == FALSE) {
         return FALSE;
      }

      $arr = array();
      while ($row = mysql_fetch_assoc($result)) {
         $arr[] = $row;
      }
      return $arr;
   }


   /*---------------------------------------------------------------------------
    * F --
    *   O
    *
    *   D
    *
    * @
    *
    * @return
    *---------------------------------------------------------------------------
    */

   //uid
   function
   getVotesBy($gameId, $round, $uid)
   {
      if (!$this->idValid($gameId) || !$this->roundValid($round) || !$this->idValid($uid)) {
         return FALSE;
      }

      $q = "SELECT vote FROM ".TBL_VOTES." WHERE gameId = '$gameId' AND round = '$round' AND uid = '$uid'";
      $result = $this->runQuery($q, $this->connection);
      if ($result == FALSE) {
         return FALSE;
      }
      $num = mysql_result($result, 0);
      return $num;
   }
}

$database = new dbManager;

?>
