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
<link rel="stylesheet" type="text/css" href="styles.css?4" />
</head>


<body>
<script src="http://static.ak.connect.facebook.com/js/api_lib/v0.4/FeatureLoader.js.php/en_US" type="text/javascript"></script>
<script type="text/javascript" src="/shared/js/jquery-lite/js/jquery-1.3.2.min.js"></script>

<script>
var idnum = 0;
var numComments = 0;

function saveComment()
{
   xmlHttp = CreateXMLHttpRequest();
   var bgColor;
   var borderColor;
   numComments++;

   if (numComments % 2 == 1) {
      bgColor =  "ffffff";
      borderColor ="ffffff";
   } else {
      bgColor =  "f5f5f5";
      borderColor = "c4c4c4";
   }
   var text= fixText(document.getElementById('CommentText').value);
   var url = "shared/comments/saveComment.php";
   var params = 'CommentText='+text+'&BgColor='+bgColor+'&BorderColor='+borderColor+'&type='+<?php echo $comment_type?>;
   document.getElementById('indicator').style.visibility = 'visible';
   sendPostRequestAjax(xmlHttp, url, params, callback);
}

function callback()
{
   if (xmlHttp.readyState == 4) {
      if (xmlHttp.status == 200) {
         var response = xmlHttp.responseText;
         document.getElementById(idnum).innerHTML = response;
         document.getElementById('indicator').style.visibility = 'hidden';
         document.getElementById('CommentText').value = "";
         opacity("PostCommentdiv", 0, 100, 1500);
         document.getElementById('postButton').disabled = true;
         idnum++;
      }
   }
}

$(".abc").click(function () {
   $(".godMessage").slideToggle("fast");
});

</script>

<!-- Note: Include this div markup as a workaround for a known bug in this release on IE where you may get a "operation aborted" error --> 
<div id="FB_HiddenIFrameContainer" style="display:none; position:absolute; left:-100px; top:-100px; width:0px; height: 0px;"></div> 

<br /> <br />
<div class="godMessage">
<?php
$comment = $database->get_comments(5, 1, COMMENT_TYPE_GOD, 1);
if (count($comment))
echo "God says:"." ". $comment[0]['text'];
?>
</div>
<button class="abc">Show/Hide</button>

<center> 
<span id="indicator" style= 'visibility:hidden'><br />
<img src ='shared/comments/images/indicator.gif'/> <br />
<b>Saving Your Comment</b>
</span>
</center>

<div class="textForTextArea"><?php echo $text; ?></div>

<div class="postComment">
<center>
<form method ="get" action ="" onsubmit = "return false;">
<table style='text-align:left'>
<tr>
<td><textarea class="commentText" style='width:400px;height:50px;' OnKeyUp="enableButtonOnText('commentText', 'postButton')"></textarea></td>
<td align="left" ><input type='submit' class="postButton" value=<?php echo $button_value; ?> disabled="disabled" OnClick="opacity('postComment', 100, 0, 500);setTimeout('saveComment()',500)";></td>
</table>
</form></center>
</div>

<?php
for ($i = 50; $i >= 0; $i--) {
   echo "<span id = \"$i\"> </span>";
}
$comment = $database->get_comments(5, 1, $comment_type, 20);
$tmp = count($comment) - 1;
?>

<script>
numComments=<?php echo $tmp?>;
</script>

<?php
for($i = 0; $i <= $tmp; $i++) {
   if ($i % 2 == 1) {
      $bgcolor =  "#ffffff";
      $borderColor ="#ffffff";
   } else {
      $bgcolor =  "#f5f5f5";
      $borderColor = "#c4c4c4";
   }

   $text = $comment[$i]['text'];
   $date = display_date($comment[$i]['timestamp']);
   $uid = $comment[$i]['uid'];
   echo <<<HTML
      <table width = '90%' cellspacing='0' bgcolor='$bgColor' align='center' style='border-top:$borderColor 1px solid; border-bottom:$borderColor 1px solid;'>
      <tr>
      <td width=55px valign="top">
      <fb:profile-pic uid='$uid' facebook-logo="false" size="thumb" linked="true">
      </fb:profile-pic>
      </td>
      <td><div class="fullName"><fb:name uid='$uid' linked="true" useyou="false"></fb:name>
      <span class="date">$date</span></div>
      <div class="commentText">$text</div>
      </td>
      </tr>
      </table>
HTML;
  }
?>

<script type="text/javascript">  
FB_RequireFeatures(["XFBML"], function(){ 
   FB.Facebook.init("<?php echo $appapikey?>", "xd_receiver.htm");
   FB.CanvasClient.startTimerToSizeToContent();
   }); 
</script>

</body>
</html>