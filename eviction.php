<?php
/*
 * Copyright 2009 MiTH.  All Rights Reserved. 
 *
 * Application: MiTH (Mafia in The House)
 * File: 'mithkeys.php' 
 */
require_once("/var/www/mithgit/mithkeys.php");
?>

<head>
<link rel="stylesheet" type="text/css" href="styles.css?2" />
</head>

<style>
#voteButton{
  float: left;
  width: 80px;
  height: 40px;
}
</style>

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

var globalId;
function commentPost(id)
{
   
   xmlHttp = CreateXMLHttpRequest();
   globalId = id;
   
   var url = 'incrementVote.php?id='+id;
   
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
        document.getElementById(globalId).innerHTML = response;
    }
  }
}


</script>


<body>
<div id="container">   <?php 
   
   include("/var/www/mithgit/core/top.layout.php");
  
   $num_players = 10;//getNumPlayers($game_id);
   $i = 0;
   for ($i = 0; $i < $num_players; $i++) {
   ?>
    <br /><br />
    <Table width = '80%'  cellspacing='0' bgcolor = '#ffffff' align = 'center'>
    <tr>
       <th><a target = '_blank' href ='http://sajain.com'>Black Life</a></th>
       <tr><th rowspan="2"><img src="/mithgit/images/nullImage.gif" /></th> </tr>
       <tr>
       <td>votes:<font size=20px><div id='<?=$i?>'> 5</div></font> </td>
       <td><input type ='submit' id="voteButton" value = 'Vote' OnClick = 'commentPost("<?=$i?>")';></input></td>
       </tr>
    </tr>
    </Table>
   
   <?php 
      
   }

   include("/var/www/mithgit/core/bottom.layout.php");
   
   
   ?>
  
</div>
</body>
