<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:fb="http://www.facebook.com/2008/fbml"> 

<?php
/*
 * Copyright 2009 MiTH.  All Rights Reserved. 
 *
 * Application: MiTH (Mafia in The House)
 * File: 'citybox.php' 
 */

require_once($_SERVER['DOCUMENT_ROOT'] . "/shared/mithkeys.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/sql/database.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/shared/helper.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/shared/head.php");

$text = "Who is mafia?";
$button_value = "Accuse";
$comment_type = COMMENT_TYPE_CITY;

?>

<head>
<link rel="stylesheet" type="text/css" href="styles.css?19" />
</head>

<body>
<!-- Needs to be kept here in the start of body tag. Don't mess with it. -->
<script src="http://static.ak.connect.facebook.com/js/api_lib/v0.4/FeatureLoader.js.php/en_US" type="text/javascript"></script>

<script type="text/javascript">

var cbNumComments = 0;
var cbDummyId = 1;
var cbLastComment;

function cbPostCommentCallback()
{
   if (xmlHttp.readyState == 4) {
      if (xmlHttp.status == 200) {
         var response = "<div id="+ cbDummyId + " > " + xmlHttp.responseText + "</div>";
         $("#cbCommentBlob").prepend(response);
         FB.XFBML.Host.parseDomElement(document.getElementById(cbDummyId));
         document.getElementById("cbIndicator").style.visibility = 'hidden';
         $("#cbCommentText").val("");
         cbDummyId++;
      }
   }
}

$("#cbToggleLink").click(function () {
   $("#cbGodMessage").slideToggle("fast");
   $(this).text($(this).text() == "hide" ? "show" : "hide");
});

function cbGetNewCommentCallback()
{
   if (xmlHttp.readyState == 4) {
      if (xmlHttp.status == 200) {
         var response = "<div id="+ cbDummyId + " > " + xmlHttp.responseText + "</div>";
         $("#cbCommentBlob").prepend(response);
         FB.XFBML.Host.parseDomElement(document.getElementById(cbDummyId));
         cbDummyId++;
      }
   }
}

/* Get new messages from the server */
$("#cbRefreshNowLink").click(function () {
	 xmlHttp = CreateXMLHttpRequest(); 
	 var url = "navigation/getNewComments.php";
	 var params = 'lastComment='+cbLastComment+'&type='+<?php echo $comment_type?>;
	 sendPostRequestAjax(xmlHttp, url, params, cbGetNewCommentCallback);  
});

$("#cbPostButton").click(function () {
   xmlHttp = CreateXMLHttpRequest();
   var bgColor;
   var borderColor;

   /* TODO: .toggleclass could be used to get rid of silly %2 */
   if (cbNumComments % 2 == 1) {
      bgColor =  "#ffffff";
      borderColor ="#ffffff";
   } else {
      bgColor =  "#f5f5f5";
      borderColor = "#c4c4c4";
   }
   cbNumComments++;
   var text= fixText($("#cbCommentText").val());
   //alert(text);
   var url = "shared/comments/saveComment.php";
   var params = 'commentText='+text+'&BgColor='+bgColor+'&BorderColor='+borderColor+'&type='+<?php echo $comment_type ?>;
   document.getElementById("cbIndicator").style.visibility = 'visible';
   sendPostRequestAjax(xmlHttp, url, params, cbPostCommentCallback);
});

</script>

<!-- Note: Include this div markup as a workaround for a known bug in this release on IE where you may get a "operation aborted" error -->
<div id="FB_HiddenIFrameContainer" style="display:none; position:absolute; left:-100px; top:-100px; width:0px; height: 0px;"></div> 

<div id="cbGodMessage" class="godMessage">
   <?php
      $comment = $database->get_comments(5, 1, COMMENT_TYPE_GOD, 1);
      if (count($comment)) {
         echo "God says:"." ". $comment[0]['text'];
      }
   ?>
</div>

<a id="cbToggleLink" class="togglelink" href=#>hide</a>


<div id="cbIndicator" class ="indicator">
   <img src ='shared/comments/images/indicator.gif'/>
</div>

<div class="textForTextArea">
   <?php echo $text; ?>
</div>

<!-- All the files that include this file should provide with $button_value text -->
<div id="cbPostComment" class="postComment">
   <form method="get" action="" onsubmit="return false;">
      <table width='100%' style='text-align:left'>
         <tr>
            <td>
               <textarea id="cbCommentText" class ="commentText">
               </textarea>
            </td>
            <td align="left">
               <input type='submit' id="cbPostButton" class ="postButton" value=<?php echo $button_value; ?>>
               </input>
            </td>
         </tr>
      </table>
   </form>
</div> <!-- End postComment -->


<a id="cbRefreshNowLink" href=#>Refresh Now</a> <br /><br />


<?php

$comment = $database->get_comments(5, 1, $comment_type, 20);
$tmp = count($comment) - 1;
?>

<script type="text/javascript">
cbNumComments=<?php echo $tmp?>;
cbLastComment = <?php echo $comment[0]['comment_id']?>
</script>

<div id="cbCommentBlob" class="commentBlob">
<?php
for($i = 0; $i <= $tmp; $i++) {

   if ($i % 2 == 1) {
      $bgColor =  "#ffffff";
      $borderColor ="#ffffff";
   } else {
      $bgColor =  "#f5f5f5";
      $borderColor = "#c4c4c4";
   }

   $text = $comment[$i]['text'];
   $uid = $comment[$i]['uid'];
   echo display_comment($bgcolor, $borderColor, $uid, $comment[$i]['timestamp'], $text);
  }
?>
</div>

<!-- Needs to be kept here at the end of body tag. Don't mess with it. -->
<script type="text/javascript">  
FB_RequireFeatures(["XFBML"], function(){ 
   FB.Facebook.init("<?php echo $appapikey?>", "xd_receiver.htm");
   FB.CanvasClient.startTimerToSizeToContent();
   }); 
</script>
</body>

</html>

