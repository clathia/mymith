<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd"> 
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:fb="http://www.facebook.com/2008/fbml"> 

<?php
/*
 * Copyright 2009 MiTH.  All Rights Reserved. 
 *
 * Application: MiTH (Mafia in The House)
 * File: 'group.php'
 */

require_once 'mithkeys.php';
?>
<body>
<script src="http://static.ak.facebook.com/js/api_lib/v0.4/FeatureLoader.js.php" type="text/javascript"></script>

<fb:serverfbml style="width: 755px;">  
<script type="text/fbml">  
<fb:fbml>  

<fb:request-form 
   action="http://apps.facebook.com/mafiainthehouse/inviteSelection.php" 
   method="get"
   type="MiTH"
   content = '<fb:req-choice url=\"http://apps.facebook.com/mafiainthehouse\" label=\"Confirm\" />' 
   <fb:multi-friend-selector showborder="false" actiontext="Create your group for MiTH !.">  
</fb:request-form>  

</fb:fbml>
</script>  
</fb:serverfbml>

<script type="text/javascript">
   FB_RequireFeatures(["XFBML"], function()
   { 
      FB.Facebook.init("10d020f9dcb70b3d5aeebc0124ddd387", "xd_receiver.htm"); 
   }); 
</script> 


</body>
</html>
