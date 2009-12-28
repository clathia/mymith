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
<link type="text/css" href="/shared/js/jquery-lite/css/ui-lightness/jquery-ui-1.7.2.custom.css" rel="stylesheet" />	
<script type="text/javascript" src="/shared/js/jquery-lite/js/jquery-1.3.2.min.js"></script>
<script type="text/javascript" src="/shared/js/jquery-lite/js/jquery-ui-1.7.2.custom.min.js"></script>
<script type="text/javascript" src="/shared/js/jcarousellite_1.0.1.js"></script>
<script type="text/javascript" src="/shared/js/jquery.mousewheel.js"></script>
<script type="text/javascript" src="/shared/js/jquery.jeditable.js"></script>

<script type="text/javascript">

$(document).ready(function() {
      $("#tabs").tabs();
   });

$(document).ready(function() {
   $("#userSlide").jCarouselLite({
    btnNext: ".next",
    btnPrev: ".prev",
    mouseWheel: true,
    visible: 3,
    circular:false
 });
});


$(document).ready(function() {
    $('.edit_area').editable('shared/comments/saveStatus.php', { 
        type      : 'textarea',
        cancel    : 'Cancel',
        submit    : 'OK',
        indicator : '<img src="/shared/comments/images/indicator.gif">',
        tooltip   : 'Click to edit...'
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
