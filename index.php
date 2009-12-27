<?php
/*
 * Copyright 2009 MiTH.  All Rights Reserved. 
 *
 * Application: MiTH (Mafia in The House)
 * File: 'index.php' 
 */
require_once($_SERVER['DOCUMENT_ROOT'] . "/shared/mithkeys.php");
include_once($_SERVER['DOCUMENT_ROOT'] . "/shared/head.php");

?>

<head>
<link rel="stylesheet" type="text/css" href="styles.css?2" />
 
<link type="text/css" href="/shared/jquery-ui-1.7.2.custom/css/ui-lightness/jquery-ui-1.7.2.custom.css" rel="stylesheet" />	
<script type="text/javascript" src="/shared/jquery-ui-1.7.2.custom/js/jquery-1.3.2.min.js"></script>
<script type="text/javascript" src="/shared/jquery-ui-1.7.2.custom/js/jquery-ui-1.7.2.custom.min.js"></script>
<script type="text/javascript" src="/shared/js/jcarousellite_1.0.1.js"></script>
<!-- Optional -->
<script type="text/javascript" src="/shared/js/jquery.mousewheel.js"></script>

<script type="text/javascript">

   $(function() {
      $("#tabs").tabs();
   });

   $(function() {
      $(".userSlide").jCarouselLite({
         btnNext: ".next",
         btnPrev: ".prev",
         mouseWheel: true,
         visible: 3,
         circular:false
      });
   }); 
   </script>

</head>

<body>
<div class="container">
   <div class="wrapper">
      <div class="content">
         <div class="demo">
            <div id="tabs">
            <ul>
            <li><a href="#status">Status</a></li>
            <li><a href="/shared/comments/Comments.php">CityBox</a></li>
            <li><a href="/navigation/mafiabox.php">MafiaBox</a></li>
            </ul>
            <div id="status">
              <?php
              /* include($_SERVER['DOCUMENT_ROOT'] . "/shared/comments/Comments.php"); 
               *
               */
               include($_SERVER['DOCUMENT_ROOT'] . "/navigation/status.php"); 
               ?>
            </div> <!--  End CityBox -->
           </div> <!-- End tabs -->
         </div><!-- End demo -->
      </div> <!-- End content -->
   </div> <!-- End wrapper -->
</div> <!--  End container -->
</body>
