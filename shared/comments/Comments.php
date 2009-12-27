<?php
/*
 * Copyright 2009 MiTH.  All Rights Reserved. 
 *
 * Application: MiTH (Mafia in The House)
 * File: 'Commentsbox.php' 
 */
 
require_once($_SERVER['DOCUMENT_ROOT'] . "/shared/mithkeys.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/sql/database.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/shared/helper.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/shared/head.php");
?>
<link rel="stylesheet" type="text/css" href="styles.css?3" />
<script>
var idnum = 0;
var numComments = 0;

function saveComment()
{
   xmlHttp = CreateXMLHttpRequest();
   var bgColor;
   var borderColor;
   numComments++;

   if (numComments % 2 == 1)
   {
      bgColor =  "ffffff";
      borderColor ="ffffff";
   } else {
      bgColor =  "f5f5f5";
      borderColor = "c4c4c4";
   }
   var text= fixText(document.getElementById('CommentText').value);
   var url = "shared/comments/saveComment.php";
   var params = 'CommentText='+text+'&BgColor='+bgColor+'&BorderColor='+borderColor+'&type=0';
   document.getElementById('indicator').style.visibility = 'visible';
   sendPostRequestAjax(xmlHttp, url, params, callback);
}

function callback()
{
   if (xmlHttp.readyState == 4)
   {
      if (xmlHttp.status == 200)
      {
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

</script>

<br /> <br />
<div class="godMessage">
<?php
$comment = $database->get_comments(5, 1, COMMENT_TYPE_GOD, 1);
if (count($comment))
echo "God says:"." ". $comment[0]['text'];
?>
</div>

<span id="indicator" style= 'visibility:hidden'><br />
<center> 
<img src ='shared/comments/images/indicator.gif'/> <br />
<b>Saving Your Comment</b>
</center>
</span>

<div class="textForTextArea">Who is mafia?</div>

<div class="postComment">
<center>
<form method ="get" action ="" onsubmit = "return false;">
<table style='text-align:left'>
<td><textarea type="text" class="commentText" style='width:400px;height:50px;' OnKeyUp="enableButtonOnText('CommentText', 'postButton')"></textarea></td>
<td align = "left" ><input type ='submit' class="postButton" value = 'Accuse' disabled="disabled" OnClick = "opacity('postComment', 100, 0, 500);setTimeout('saveComment()',500)";></td>
</table>
</form></center>
</div>

<?php
for($i = 50; $i>=0; $i--) {
   echo "<span id = \"$i\"> </span>";
}
$comment = $database->get_comments(5, 1, COMMENT_TYPE_CITY, 20);
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
   $user = get_user_info($comment[$i]['uid'], $facebook);
   if ($user) {
      echo display_comment($bgcolor, $borderColor, $user['profile_url'], $user['pic_square'], $user['full_name'],$comment[$i]['timestamp'], $comment[$i]['text']);
   }
}?>
