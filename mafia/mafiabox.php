<?php
/*
 * Copyright 2009 MiTH.  All Rights Reserved. 
 *
 * Application: MiTH (Mafia in The House)
 * File: 'mafiabox.php' 
 */

require_once($_SERVER['DOCUMENT_ROOT'] . "/mithkeys.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/sql/database.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/core/helper.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/core/head.php");
?>

<head>

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
    }
   else
    {
        bgColor =  "f5f5f5";
        borderColor = "c4c4c4";
    }

   var text= fixText(document.getElementById('CommentText').value);
   var url = "/comments/saveComment.php";
   var params = 'CommentText='+text+'&BgColor='+bgColor+'&BorderColor='+borderColor+'&type=1';
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

<link rel="stylesheet" type="text/css" href="/styles.css?2" />
<style>
#postButton{
  float: left;
  width: 100px;
  height: 50px;
}

div#PostCommentdiv{
  width:100%;
  height:150px;
  align:center;
}

div#textForTextArea{
  text-align:left;
  width:200px;
  font-size:20px;
}

</style>


</head>

<body>
<div id="container">   <?php include($_SERVER['DOCUMENT_ROOT'] . "/core/top.layout.php"); ?>
  
   <div id="wrapper">
    <div id="content">

<span id="indicator" style= 'visibility:hidden'>
<br />
<center>
<img src ='/comments/images/indicator.gif'/>
<br>
<b>Saving Your Comment</b>
</center>
</span>

<center>

<div id='PostCommentdiv'>
<form method ="get" action ="" onsubmit = "return false;">
<table style='text-align:left' >
<tr>
<div id="textForTextArea">Muhahaha! Time to kill someone..</label>
<td><textarea type="text" id="CommentText" style='width:400px;height:80px;' OnKeyUp="enableButtonOnText('CommentText', 'postButton')"></textarea></td>
<td align = "left" ><input type ='submit' id="postButton" value = 'Kill' disabled="disabled" OnClick = 'opacity("PostCommentdiv", 100, 0, 500);setTimeout("saveComment()",500)';></td>
</table>
</form></div>
</center>

<?php

for($i = 50; $i>=0; $i--) { 
echo "<span id = \"$i\"> </span>";
}

$comment = $database->get_comments(5, 1, COMMENT_TYPE_MAFIA, 5);
$tmp = count($comment) - 1;
?>

<script>
numComments=<?php echo $tmp?>;
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
    $user = get_user_info($comment[$i]['uid'], $facebook);
    if ($user) {
       echo display_comment($bgcolor, $borderColor, $user['profile_url'], $user['pic_square'], $user['full_name'],$comment[$i]['timestamp'], $comment[$i]['text']);
    }
    $i++;
}?>
    </div>
   </div>
  
   <?php include($_SERVER['DOCUMENT_ROOT'] . "/core/bottom.layout.php");?>
  
</div>
</body>
