<?php
/*
 * Copyright 2009 MiTH.  All Rights Reserved. 
 *
 * Application: MiTH (Mafia in The House)
 * File: 'index.php' 
 */
require_once($_SERVER['DOCUMENT_ROOT'] . "/shared/mithkeys.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/shared/head.php");

?>

<head>
<link rel="stylesheet" type="text/css" href="styles.css?2" />
</head>

<body>
<div id="container">   <?php include($_SERVER['DOCUMENT_ROOT'] . "/shared/top.layout.php"); ?>
  
   <div id="wrapper">
    <div id="content">
      <?php  include($_SERVER['DOCUMENT_ROOT'] . "/shared/comments/Comments.php"); ?>
    </div>
   </div>
  
   <?php include($_SERVER['DOCUMENT_ROOT'] . "/shared/bottom.layout.php"); ?>
  
</div>
</body>
