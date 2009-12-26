<?php
/*
 * Copyright 2009 MiTH.  All Rights Reserved. 
 *
 * Application: MiTH (Mafia in The House)
 * File: 'godbox.php' 
 */
require_once($_SERVER['DOCUMENT_ROOT'] . "/mithkeys.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/core/head.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/core/helper.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/sql/database.php");
?>

<head>
<script language="javascript" type="text/javascript" src="/calendar/datetimepicker_css.js">

//Date Time Picker script- by TengYong Ng of http://www.rainforestnet.com
//Script featured on JavaScript Kit (http://www.javascriptkit.com)
//For this script, visit http://www.javascriptkit.com
</script>
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
   var params = 'CommentText='+text+'&BgColor='+bgColor+'&BorderColor='+borderColor+'&type=2';
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
  width: 50px;
  height: 30px;
}

div#PostCommentdiv{
  width:100%;
  height:150px;
  align:center;
}

div#textForTextArea{
  text-align:left;
  width:50px;
  font-size:15px;
}
</style>

</head>


<body>
<div id="container">  <?php include($_SERVER['DOCUMENT_ROOT'] . "/core/top.layout.php"); ?>
  
   <div id="wrapper">
    <div id="content">

<br /><br />

<!--  Display calendar  -->
Deadline for Round 1:
<input id="deadline" type="text" size="25">
<a href="javascript:NewCssCal('demo1', 'MMddyyyy', 'Arrow', 'true', '12', 'true')">
<img src="/calendar/images/cal.gif" alt="Pick a date" /></a>




<span id="indicator" style= 'visibility:hidden'>
<br>
<center>
<img src ='/comments/images/indicator.gif' />
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
<td><textarea type="text" id="CommentText" style='width:400px;height:30px;' OnKeyUp="enableButtonOnText('CommentText', 'postButton')"></textarea></td>
<td align = "left" ><input type ='submit' id="postButton" value = 'Post' disabled="disabled" OnClick = "opacity('PostCommentdiv', 100, 0, 500);setTimeout('saveComment()',500)";></td>
</table>
</form>
</div>
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

<?php
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
}
?>
      
    </div>
   </div>
  
   <?php include($_SERVER['DOCUMENT_ROOT'] . "/core/bottom.layout.php");?>
  
</div>
</body>
