<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:fb="http://www.facebook.com/2008/fbml">

<head>
<?php
/*
 * Copyright 2009 MiTH.  All Rights Reserved. 
 *
 * Application: MiTH (Mafia in The House)
 * File: 'play.php' 
 */
require_once($_SERVER['DOCUMENT_ROOT'] . "/shared/mithkeys.php");
?>

<link rel="stylesheet" type="text/css" href="styles.css?31" />

</head>

<body>
<!-- Needs to be kept here in the start of body tag. Don't mess with it. -->
<script src="http://static.ak.facebook.com/js/api_lib/v0.4/FeatureLoader.js.php/en_US" type="text/javascript"></script>

<!-- Note: Include this div markup as a workaround for a known bug in this release on IE where you may get a "operation aborted" error -->
<div id="FB_HiddenIFrameContainer" style="display:none; position:absolute; left:-100px; top:-100px; width:0px; height: 0px;"></div>

<div class="container">
   <div class="wrapper">
      <div class="content">
         <div class="demo">

if () {

} else {



<fb:serverfbml style="width: 750px;">  
<script type="text/fbml">  
<fb:fbml>  

<fb:request-form 
   action="http://apps.facebook.com/mafiainthehouse/invitation/inviteSelection.php" 
   method="get"
   type="MiTH"
   content = '<fb:req-choice url=\"http://apps.facebook.com/mafiainthehouse\" label=\"Confirm\" />' 
   <fb:multi-friend-selector showborder="false" actiontext="Create your group for MiTH !.">  
</fb:request-form>  

</fb:fbml>
</script>
</fb:serverfbml>

}

         </div><!-- End demo -->
      </div> <!-- End content -->
   </div> <!-- End wrapper -->
</div> <!--  End container -->



<script type="text/javascript">
FB_RequireFeatures(["CanvasUtil"], function()
{
   //You can optionally enable extra debugging logging in Facebook JavaScript client
   //FB.FBDebug.isEnabled = true;
   //FB.FBDebug.logLevel = 4;
   FB.XdComm.Server.init("xd_receiver.htm");
   FB.CanvasClient.startTimerToSizeToContent();
});

FB_RequireFeatures(["XFBML"], function(){
   FB.Facebook.init("<?php echo $appapikey?>", "xd_receiver.htm");
});
</script>
</body>

</html>