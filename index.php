<?php
/*
 * <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
 * Copyright 2009 MiTH.  All Rights Reserved.
 *
 * Application: MiTH (Mafia in The House)
 * File: 'index.php'
 */
require_once($_SERVER['DOCUMENT_ROOT'] . "/shared/mithkeys.php");
include_once($_SERVER['DOCUMENT_ROOT'] . "/shared/head.php");

?>

<head>
<link type="text/css" href="/shared/js/jquery-lite/css/ui-lightness/jquery-ui-1.7.2.custom.css" rel="stylesheet" />
<link type="text/css" href="/shared/js/jquery-dtpicker-1.0a5/jquery.dtpicker.css" rel="stylesheet" />
<link rel="stylesheet" type="text/css" href="styles.css?12" />

<script type="text/javascript" src="/shared/js/jquery-lite/js/jquery-1.3.2.min.js"></script>
<script type="text/javascript" src="/shared/js/jquery-lite/js/jquery-ui-1.7.2.custom.min.js"></script>

<script type="text/javascript" src="/shared/js/jcarousellite_1.0.1.js"></script>
<script type="text/javascript" src="/shared/js/jquery.mousewheel.js"></script>
<script type="text/javascript" src="/shared/js/jquery.jeditable.js"></script>
<script type="text/javascript" src="/shared/js/jquery.cookie.js"></script>

<script type="text/javascript" src="/shared/js/jquery-dtpicker-1.0a5/jquery.metadata.min.js"></script>
<script type="text/javascript" src="/shared/js/jquery-dtpicker-1.0a5/jquery.dtpicker.min.js"></script>
<script type="text/javascript" src="/shared/js/mithFunctions.js?4"></script>
</head>

<body>

<div class="container">
   <div class="wrapper">
      <div class="content">
         <div class="demo">
            <div id="tabs">
            <ul>
            <li><a href="#status">Status</a></li>
            <li><a href="/navigation/cityBox.php">CityBox</a></li>
            <li><a href="/navigation/mafiaBox.php">MafiaBox</a></li>
            </ul>
            <div id="status">
              <?php
               include($_SERVER['DOCUMENT_ROOT'] . "/navigation/status.php");
               ?>
            </div> <!--  End CityBox -->
           </div> <!-- End tabs -->
         </div><!-- End demo -->
      </div> <!-- End content -->
   </div> <!-- End wrapper -->
</div> <!--  End container -->

</body>
