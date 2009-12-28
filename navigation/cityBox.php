<?php
/*
 * Copyright 2009 MiTH.  All Rights Reserved. 
 *
 * Application: MiTH (Mafia in The House)
 * File: 'cityBox.php' 
 */
 
require_once($_SERVER['DOCUMENT_ROOT'] . "/shared/mithkeys.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/sql/database.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/shared/helper.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/shared/head.php");
?>

<script type="text/javascript" src="/shared/js/jquery-lite/js/jquery-1.3.2.min.js"></script>

<?php
$text = "Who is mafia?";
$button_value = "Accuse";
$comment_type = COMMENT_TYPE_CITY;

include($_SERVER['DOCUMENT_ROOT'] . "/shared/comments/commentBox.php");
?>
