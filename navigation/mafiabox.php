<?php
/*
 * Copyright 2009 MiTH.  All Rights Reserved. 
 *
 * Application: MiTH (Mafia in The House)
 * File: 'mafiabox.php' 
 */

require_once($_SERVER['DOCUMENT_ROOT'] . "/shared/mithkeys.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/sql/database.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/shared/helper.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/shared/head.php");
?>

<?php
$text = "Muahahaha! Time to kill someone...";
$button_value = "Kill";
$comment_type = COMMENT_TYPE_MAFIA;

include_once($_SERVER['DOCUMENT_ROOT'] . "/shared/comments/commentBox.php");
?>
