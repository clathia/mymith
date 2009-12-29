<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:fb="http://www.facebook.com/2008/fbml"> 

<?php
/*
 * Copyright 2009 MiTH.  All Rights Reserved. 
 *
 * Application: MiTH (Mafia in The House)
 * File: 'commentBox.php' 
 */
require_once($_SERVER['DOCUMENT_ROOT'] . "/shared/mithkeys.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/sql/database.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/shared/helper.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/shared/head.php");
?>

<head>
<link rel="stylesheet" type="text/css" href="styles.css?15" />
</head>

<body>
<!-- Needs to be kept here in the start of body tag. Don't mess with it. -->
<script src="http://static.ak.connect.facebook.com/js/api_lib/v0.4/FeatureLoader.js.php/en_US" type="text/javascript"></script>

<script type="text/javascript">
var numComments = 0;

function commentBoxCallback()
{
   if (xmlHttp.readyState == 4) {
      if (xmlHttp.status == 200) {
         var response = xmlHttp.responseText;
         $("#commentBlob").prepend(response);
         document.getElementById('indicator').style.visibility = 'hidden';
         document.getElementById('commentText').value = "";
         document.getElementById('postButton').disabled = true;
      }
   }
}

$(".togglelink").click(function () {
   $(".godMessage").slideToggle("fast");
   $(this).text($(this).text() == "hide" ? "show" : "hide");
});

$("#postButton").click(function () {
   xmlHttp = CreateXMLHttpRequest();
	var bgColor;
	var borderColor;
	numComments++;
	   
	/* TODO: .toggleclass could be used to get rid of silly %2 */
	if (numComments % 2 == 1) {
	   bgColor =  "ffffff";
	   borderColor ="ffffff";
	} else {
	   bgColor =  "f5f5f5";
	   borderColor = "c4c4c4";
	}
	var text= fixText(document.getElementById('commentText').value);
	var url = "shared/comments/saveComment.php";
	var params = 'commentText='+text+'&BgColor='+bgColor+'&BorderColor='+borderColor+'&type='+<?php echo $comment_type?>;
	document.getElementById('indicator').style.visibility = 'visible';
	sendPostRequestAjax(xmlHttp, url, params, commentBoxCallback);
});

</script>

<!-- Note: Include this div markup as a workaround for a known bug in this release on IE where you may get a "operation aborted" error --> 
<div id="FB_HiddenIFrameContainer" style="display:none; position:absolute; left:-100px; top:-100px; width:0px; height: 0px;"></div> 

<div class="godMessage">
   <?php
      $comment = $database->get_comments(5, 1, COMMENT_TYPE_GOD, 1);
      if (count($comment)) {
         echo "God says:"." ". $comment[0]['text'];
      }
   ?>
</div>

<a class="togglelink" href=#>hide</a>


<div id="indicator">
   <img src ='shared/comments/images/indicator.gif'/>
   <b>Saving Your Comment</b>
</div>

<div class="textForTextArea">
   <?php echo $text; ?>
</div>

<!-- All the files that include this file should provide with $button_value text -->
<div id="postComment">
   <form method="get" action="" onsubmit="return false;">
      <table width='100%' style='text-align:left'>
         <tr>
            <td>
               <textarea id="commentText" onkeyup="enableButtonOnText('commentText', 'postButton')"></textarea>
            </td>
            <td align="left">
               <input type='submit' id="postButton" value=<?php echo $button_value; ?> disabled="disabled"></input>
            </td>
         </tr>
      </table>
   </form>
</div> <!-- End postComment -->

<?php
$comment = $database->get_comments(5, 1, $comment_type, 20);
$tmp = count($comment) - 1;
?>

<script type="text/javascript">
numComments=<?php echo $tmp?>;
</script>

<div id="commentBlob">
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
   $date = display_date($comment[$i]['timestamp']);
   $uid = $comment[$i]['uid'];
   echo <<<HTML
      <div class="commentTable">
      <table width='100%' cellspacing='0' bgcolor='$bgColor' align='center' style='border-top:$borderColor 1px solid; border-bottom:$borderColor 1px solid;'>
      <tr>
      <td width=55px valign="top">
      <fb:profile-pic uid='$uid' facebook-logo="false" size="square" linked="true">
      </fb:profile-pic>
      </td>
      <td valign="top"><div class="fullName"><fb:name uid='$uid' linked="true" useyou="false"></fb:name>
      <span class="date">$date</span></div>
      <div class="commentTextOld">$text</div>
      </td>
      </tr>
      </table>
      </div>
HTML;
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
