<?php 
require_once("/var/www/mithgit/sql/database.php");
require("/var/www/mithgit/core/helper.php");
?>

<script>
var idnum = 0;
var xmlHttp;
var numComments = 0;

function CreateXMLHttpRequest()
{
  if (window.ActiveXObject)
  {
    return new ActiveXObject("Microsoft.XMLHTTP");
  }
  else if (window.XMLHttpRequest)
  {
    return new XMLHttpRequest();
  }
}

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
    }
   else
    {
        bgColor =  "f5f5f5";
        borderColor = "c4c4c4";
    }
   
   var text= document.getElementById('CommentText').value;
   var url = "/mithgit/comments/saveComment.php";
   var params = 'CommentText='+text+'&BgColor='+bgColor+'&BorderColor='+borderColor+'&type=2';
   xmlHttp.open("POST", url, true);
   xmlHttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
   xmlHttp.setRequestHeader("Content-length", params.length);
   xmlHttp.setRequestHeader("Connection", "close");
   document.getElementById('indicator').style.visibility = 'visible';   xmlHttp.onreadystatechange = callback;
   xmlHttp.send(params);
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

function verifyText()
{
   if (document.getElementById('CommentText').value == '') {
      document.getElementById('postButton').disabled = true;
   } else {
      document.getElementById('postButton').disabled = false;
   }
}

</script>


<style>
#postButton{
  float: left;
  width: 50px;
  height: 30px;
}

div#PostCommentdiv{
  style=width:100%;
  height:150px;
  align:center;
}

div#textForTextArea{
  text-align:left;
  width:50px;
  font-size:15px;
}
</style>



<span id="indicator" style= 'visibility:hidden'>
<br>
<center>
<img src ='/mithgit/comments/images/indicator.gif'/>
<br>
<b>Saving Your Comment</b>
</center>
</span>

<center>

<div id='PostCommentdiv'>
<form method ="get" action ="" onsubmit = "return false;">
<table style='text-align:left' >
<tr>
<div id="textForTextArea">Enter Message</label>
<td><textarea type="text" id="CommentText" style='width:400px;height:30px;' OnKeyUp='verifyText()'></textarea></td>
<td align = "left" ><input type ='submit' id="postButton" value = 'Post' disabled="disabled" OnClick = "opacity('PostCommentdiv', 100, 0, 500);setTimeout('saveComment()',500)";></td>
</table>
</form></div>
</center>

<?php

for($i = 50; $i>=0; $i--) { 
echo "<span id = \"$i\"> </span>";
}

$comment = $database->get_comments(5, 1, COMMENT_TYPE_GOD, 20);
$tmp = count($comment) - 1;
?>

<script>
numComments=<?=$tmp?>;
</script>

<?
$i = 0;
while($i <= $tmp)
{   
    
    if ($i % 2 == 1)
    {
        $bgcolor =  "#ffffff";
        $borderColor ="#ffffff";
    }
    else
    {
        $bgcolor =  "#f5f5f5";
        $borderColor = "#c4c4c4";
    }

    $user_details = $facebook->api_client->users_getInfo($comment[$i]['uid'], 'last_name, first_name, profile_url, pic_square'); 
    if ($user_details) {
    $first_name = $user_details[0]['first_name']; 
    $last_name = $user_details[0]['last_name'];
    $full_name = $first_name." ".$last_name;
    $profile_url = $user_details[0]['profile_url'];
    $pic_square = $user_details[0]['pic_square'];
    if (! $pic_square) {
      $pic_square = "/mithgit/images/nullImage.gif";
    }

    echo display_comment($bgcolor, $borderColor, $profile_url, $pic_square, $full_name, $comment[$i]['timestamp'], $comment[$i]['text']);
    }
    $i++;
}?>
