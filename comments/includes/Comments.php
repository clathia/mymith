<?php require("commentsconfig.php");

?>
<script>
var xmlHttp;
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
   xmlHttp =CreateXMLHttpRequest(); 
   var url = 'saveComment.php?FullName='+document.getElementById('FullName').value+'&CommentText='+document.getElementById('CommentText').value+'&Website='+document.getElementById('Website').value+'&Email='+document.getElementById('Email').value+"&BgColor="+ document.getElementById('Bgcolor').value+"&BorderColor="+document.getElementById('BorderColor').value+'&ForeignID=<?$ForeignID ?>';

   document.getElementById('indicator').style.visibility = 'visible';
    
   xmlHttp.onreadystatechange = callback;
   xmlHttp.open("GET", url, true);
   xmlHttp.send(null);
}

function callback()
{
  if (xmlHttp.readyState == 4)
  {
    if (xmlHttp.status == 200)
    {
        var response = xmlHttp.responseText;
        document.getElementById('PostCommentdiv').innerHTML = response;
        document.getElementById('indicator').style.display = 'none';
        opacity("PostCommentdiv", 0, 100, 1500);
    }
  }
}
</script>
<?php

mysql_connect($host,$username,$password);
@mysql_select_db($database) or die( "Unable to select database");
$query 	="select * from comments  where ForeignID = '$ForeignID' ";
$result=mysql_query($query);
$num=mysql_numrows($result);
$i =0 ;

while($num > $i )
{   
    $CommentSubject = mysql_result($result,$i,"CommentSubject");
    $PublishDate = mysql_result($result,$i,"PublishDate");
    $FullName = mysql_result($result,$i,"FullName");
    $CommentText = mysql_result($result,$i,"CommentText");
    $Website = mysql_result($result,$i,"Website");
    $CommentID = mysql_result($result,$i,"CommentID");
    $PublishDate= create_date('D M d, Y g:i a', $PublishDate, 0);
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
        
    
  ?>

<Table width = '90%'  cellspacing='0' bgcolor = '<?=$bgcolor?>' align = 'center'  style='border-top:<?=$borderColor?> 1px solid ;border-bottom:<?=$borderColor?> 1px solid ;'>
    <tr>
        <td style='text-align:left;font-weight:bold'><a target = '_blank' href = '<?=$Website?>'><?=$FullName?></a> Says:</td>
    </tr>
    <tr>
        <td style='text-align:left;'><?=$PublishDate?></td>
    </tr>
    <tr>
        <td style='text-align:left;'><?=$CommentText?></td>
    </tr>
       
</Table>
<?
$i++;
}
if ($i % 2 == 1)
{
        $bgcolor =  "ffffff";
        $borderColor ="ffffff";
}
        else
{
        $bgcolor =  "f5f5f5";
        $borderColor = "c4c4c4";
}
?>
<input type=hidden value='<?=$bgcolor?>' id ="Bgcolor">
<input type=hidden value='<?=$borderColor?>' id="BorderColor">
<?

function create_date($format, $gmepoch, $tz)
{
	global $board_config, $lang;
	static $translate;

	if ( empty($translate) && $board_config['default_lang'] != 'english' )
	{
		@reset($lang['datetime']);
		while ( list($match, $replace) = @each($lang['datetime']) )
		{
			$translate[$match] = $replace;
		}
	}

	return ( !empty($translate) ) ? strtr(@gmdate($format, $gmepoch + (3600 * $tz)), $translate) : @gmdate($format, $gmepoch + (3600 * $tz));
}

?>
<span id="indicator" style= 'visibility:hidden'>
<br>
<center>
<img src ='images/indicator.gif'/>
<br>
<b>Saving Your Comment</b>
</center>
</span>
<center>
<div id='PostCommentdiv' style='width:100%;height:350px;align:center' >
<h2 >Leave Your Comment <img src="images/ajax2.gif"/></h2>
<form method ="get" action ="" onsubmit = "return false;">
<table width = "70%" style='text-align:left' >
<tr>
<td><input type="text" id="FullName"/></td>
<td>Name (Required)</td>
</tr>
 <tr>
<td><input type="text" id="Email"/></td>
<td>Mail (will not be published) (required)</td>

</tr>
<tr>
<td><input type="text" id="Website"/></td>
<td>Website</td>
</tr>
<tr>

<td colspan = '2'><textarea type="text" id="CommentText" style='width:100%; height: 200px;' ></textarea></td>
</tr>
<td colspan = '2' align = "right" ><input type ='submit' value = 'Post' OnClick = 'opacity("PostCommentdiv", 100, 0, 500);setTimeout("saveComment()",500)';> </td>
</tr>
</table>
</form></div>
</center>
