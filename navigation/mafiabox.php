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

<script type="text/javascript" src="/shared/js/jquery-lite/js/jquery-1.3.2.min.js"></script>

<?php
$text = "Muahahaha! Time to kill someone...";
$button_value = "Kill";
$comment_type = COMMENT_TYPE_MAFIA;

include($_SERVER['DOCUMENT_ROOT'] . "/shared/comments/commentBox.php");
?>
