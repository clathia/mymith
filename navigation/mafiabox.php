<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:fb="http://www.facebook.com/2008/fbml"> 

<?php
/*
 * Copyright 2009 MiTH.  All Rights Reserved. 
 *
 * Application: MiTH (Mafia in The House)
 * File: 'mafiabox.php' 
 */

require_once($_SERVER['DOCUMENT_ROOT'] . "/shared/mithkeys.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/sql/database.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/shared/helper.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/shared/head.php");

$text = "Muahahaha! Time to kill someone...";
$button_value = "Kill";
$comment_type = COMMENT_TYPE_MAFIA;

?>

<head>
<link rel="stylesheet" type="text/css" href="styles.css?19" />
</head>

<body>
<!-- Needs to be kept here in the start of body tag. Don't mess with it. -->
<script src="http://static.ak.connect.facebook.com/js/api_lib/v0.4/FeatureLoader.js.php/en_US" type="text/javascript"></script>

<script type="text/javascript">

var mbNumComments = 0;
var mbDummyId = 1;

function mbCallback()
{
   if (xmlHttp.readyState == 4) {
      if (xmlHttp.status == 200) {
         var response = "<div id="+ mbDummyId + " > " + xmlHttp.responseText + "</div>";
         $("#mbCommentBlob").prepend(response);
         FB.XFBML.Host.parseDomElement(document.getElementById(mbDummyId));
         document.getElementById("mbIndicator").style.visibility = 'hidden';
         $("#mbCommentText").val("");
         mbDummyId++;
      }
   }
}

$("#mbToggleLink").click(function () {
   $("#mbGodMessage").slideToggle("fast");
   $(this).text($(this).text() == "hide" ? "show" : "hide");
});

$("#mbPostButton").click(function () {
   xmlHttp = CreateXMLHttpRequest();
   var bgColor;
   var borderColor;

   /* TODO: .toggleclass could be used to get rid of silly %2 */
   if (mbNumComments % 2 == 1) {
      bgColor =  "#ffffff";
      borderColor ="#ffffff";
   } else {
      bgColor =  "#f5f5f5";
      borderColor = "#c4c4c4";
   }
   mbNumComments++;
   var text= fixText($("#mbCommentText").val());
   var url = "shared/comments/saveComment.php";
   var params = 'commentText='+text+'&BgColor='+bgColor+'&BorderColor='+borderColor+'&type='+<?php echo $comment_type?>;
   document.getElementById("mbIndicator").style.visibility = 'visible';
   sendPostRequestAjax(xmlHttp, url, params, mbCallback);
});

</script>

<!-- Note: Include this div markup as a workaround for a known bug in this release on IE where you may get a "operation aborted" error -->
<div id="FB_HiddenIFrameContainer" style="display:none; position:absolute; left:-100px; top:-100px; width:0px; height: 0px;"></div> 

<div id="mbGodMessage" class="godMessage">
   <?php
      $comment = $database->get_comments(5, 1, COMMENT_TYPE_GOD, 1);
      if (count($comment)) {
         echo "God says:"." ". $comment[0]['text'];
      }
   ?>
</div>

<a id="mbToggleLink" class="togglelink" href=#>hide</a>


<div id="mbIndicator" class ="indicator">
   <img src ='shared/comments/images/indicator.gif'/>
</div>

<div class="textForTextArea">
   <?php echo $text; ?>
</div>

<!-- All the files that include this file should provide with $button_value text -->
<div id="mbPostComment" class="postComment">
   <form method="get" action="" onsubmit="return false;">
      <table width='100%' style='text-align:left'>
         <tr>
            <td>
               <textarea id="mbCommentText" class ="commentText"></textarea>
            </td>
            <td align="left">
               <input type='submit' id="mbPostButton" class ="postButton" value=<?php echo $button_value; ?>></input>
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
mbNumComments=<?php echo $tmp?>;
</script>

<div id="mbCommentBlob" class="commentBlob">
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

