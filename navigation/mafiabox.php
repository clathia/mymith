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
$mithCbLastNewComment = "mithMbLastNewCommentId";
$mithCbLastOldComment = "mithMbLastOldCommentId";

require_once $_SERVER['DOCUMENT_ROOT'] . "/shared/comments/commentBox.php";
?>
