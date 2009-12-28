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

<link rel="stylesheet" type="text/css" href="styles.css?4" />

<?php
$text = "Who is mafia?";
$button_value = "Accuse";
$comment_type = COMMENT_TYPE_CITY;

include($_SERVER['DOCUMENT_ROOT'] . "/shared/comments/commentBox.php");
?>
