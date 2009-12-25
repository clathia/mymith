<?php 
require("sql/database.php");
require("core/helper.php");
?>

<script>
var idnum = 0;
var numComments = 0;

function saveComment()
{
   alert("in");
   xmlHttp = CreateXMLHttpRequest();
   var bgColor;
   var borderColor;
   numComments++;
   alert("in1");
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
   var url = "comments/saveComment.php";
   var params = 'CommentText='+text+'&BgColor='+bgColor+'&BorderColor='+borderColor+'&type=0';
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

</script>


<style>
#postButton{
  float: left;
  width: 100px;
  height: 50px;
}

div#PostCommentdiv{
  style=width:100%;
  height:150px;
  align:center;
}

div#textForTextArea{
  text-align:left;
  width:200px;
  font-size:20px;
}

div#GodMessage{
  background:#f5f5f5;
  text-align:left;
  width:95%;
  font-size:20px;
  color:black;
}
</style>
<br /> <br />
<center>
<div id="GodMessage">
<?php
$comment = $database->get_comments(5, 1, COMMENT_TYPE_GOD, 1);
if (count($comment))
echo "God says:"." ". $comment[0]['text'];
?>
</div>
</center>

<span id="indicator" style= 'visibility:hidden'>
<br>
<center>
<img src ='comments/images/indicator.gif'/>
<br>
<b>Saving Your Comment</b>
</center>
</span>

<center>

<div id='PostCommentdiv'>
<form method ="get" action ="" onsubmit = "return false;">
<table style='text-align:left' >
<tr>
<div id="textForTextArea">Who is mafia ?</label>
<td><textarea type="text" id="CommentText" style='width:400px;height:80px;' OnKeyUp="enableButtonOnText('CommentText', 'postButton')"></textarea></td>
<td align = "left" ><input type ='submit' id="postButton" value = 'Accuse' disabled="disabled" OnClick = "opacity('PostCommentdiv', 100, 0, 500);setTimeout('saveComment()',500)";></td>
</table>
</form></div>
</center>

<?php

for($i = 50; $i>=0; $i--) { 
echo "<span id = \"$i\"> </span>";
}

$comment = $database->get_comments(5, 1, COMMENT_TYPE_CITY, 20);
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
