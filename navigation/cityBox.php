<?php
/*
 * Copyright 2009 MiTH.  All Rights Reserved.
 *
 * Application: MiTH (Mafia in The House)
 * File: 'citybox.php'
 *
 */
require_once $_SERVER['DOCUMENT_ROOT'] . "/shared/mithkeys.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/sql/database.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/shared/helper.php";

$mithTextHeader = "Who is mafia?";
$mithButtonValue = "Accuse";
$mithCommentType = COMMENT_TYPE_CITY;
$mithCommentText = "mithCbCommentTextId";
$mithCommentBlob = "mithCbCommentBlobId";
$mithCommentPostIndicator = "mithCbCommentPostIndicatorId";
$mithNewMessage = "mithCbNewMessageId";
$mithCbLastNewComment = "mithCbLastNewCommentId";
$mithCbLastOldComment = "mithCbLastOldCommentId";
$mithGodMessage = "mithCbGodMessageId";
$mithToggleLink = "mithCbToggleLinkId";
$mithPostButton = "mithCbPostButtonId";
$mithInfoCharLimit = "mithCbInfoCharLimitId";

require_once $_SERVER['DOCUMENT_ROOT'] . "/shared/comments/commentBox.php";
?>