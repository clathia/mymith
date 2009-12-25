<?php
/*
 * Copyright 2009 MiTH.  All Rights Reserved. 
 *
 * Application: MiTH (Mafia in The House)
 * File: 'mithkeys.php' 
 */
require_once($_SERVER['DOCUMENT_ROOT'] . "/mithkeys.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/core/head.php");

?>

<head>
<link rel="stylesheet" type="text/css" href="/styles.css?2" />
</head>

<body>
<div id="container">  <?php include($_SERVER['DOCUMENT_ROOT'] . "/core/top.layout.php"); ?>
  
   <div id="wrapper">
    <div id="content">
      <?php include("godCore.php");?>
    </div>
   </div>
  
   <?php include($_SERVER['DOCUMENT_ROOT'] . "/core/bottom.layout.php");?>
  
</div>
</body>
