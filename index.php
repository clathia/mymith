<?php
/*
 * Copyright 2009 MiTH.  All Rights Reserved. 
 *
 * Application: MiTH (Mafia in The House)
 * File: 'mithkeys.php' 
 */
require_once("mithkeys.php");
require("comments/includes/head.php");

?>

<head>
<link rel="stylesheet" type="text/css" href="styles.css?2" />
</head>

<body>
<div id="container">   <?php include("core/top.layout.php"); ?>
  
   <div id="wrapper">
    <div id="content">
      <?php $ForeignID = '1'; include("comments/includes/Comments.php");?>
    </div>
   </div>
  
   <?php include("core/bottom.layout.php");?>
  
</div>
</body>
