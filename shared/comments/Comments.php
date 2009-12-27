<?php
require_once($_SERVER['DOCUMENT_ROOT'] . "/sql/database.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/shared/helper.php"); 
?>

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
   var url = "comments/saveComment.php";
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
  font-size:large;
}

div#GodMessage{
  background:#f5f5f5;
  text-align:left;
  width:95%;
  font-size:20px;
  color:black;
}

div#full_name a{
  font-size:medium;
  margin-left:1px;
  font-family:"Times New Roman";
}

div#date{
  font-size:x-small;
  color:#8E8F95;
  margin-left:5px;
  margin-top:1px;
  margin-bottom:4px;
}

div#comment_text{
  margin-left:5px;
  font-family:"Georgia";
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

<span id="indicator" style= 'visibility:hidden'><br />
<center> 
<img src ='shared/comments/images/indicator.gif'/> <br />
<b>Saving Your Comment</b>
</center>
</span>

<center>
<div id='PostCommentdiv'>
<form method ="get" action ="" onsubmit = "return false;">
<table style='text-align:left' >
<tr>
<div id="textForTextArea">Who is mafia?</label>
<td><textarea type="text" id="CommentText" style='width:400px;height:50px;' OnKeyUp="enableButtonOnText('CommentText', 'postButton')"></textarea></td>
<td align = "left" ><input type ='submit' id="postButton" value = 'Accuse' disabled="disabled" OnClick = "opacity('PostCommentdiv', 100, 0, 500);setTimeout('saveComment()',500)";></td>
</table>
</form>
</center>

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