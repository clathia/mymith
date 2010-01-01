<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:fb="http://www.facebook.com/2008/fbml">

<?php
/*
 * Copyright 2009 MiTH.  All Rights Reserved.
 *
 * Application: MiTH (Mafia in The House)
 * File: 'mafiabox.php'
 */

require_once $_SERVER['DOCUMENT_ROOT'] . "/shared/mithkeys.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/sql/database.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/shared/helper.php";

$mithTextHeader = "Muahahaha! Time to kill someone...";
$mithButtonValue = "Kill";
$mithCommentType = COMMENT_TYPE_MAFIA;
$mithCommentText = "mithMbCommentTextId";
$mithCommentBlob = "mithMbCommentBlobId";
$mithCommentPostIndicator = "mithMbCommentPostIndicatorId";
$mithNewMessage = "mithMbNewMessageId";

require_once $_SERVER['DOCUMENT_ROOT'] . "/shared/comments/commentBox.php";
?>

</html>
