<?php
/*
 * Copyright 2009 MiTH.  All Rights Reserved. 
 *
 * Application: MiTH (Mafia in The House)
 * File: 'mithkeys.php' 
 */
require_once("/var/www/mithgit/mithkeys.php");
require_once("/var/www/mithgit/sql/database.php");

?>

<head>
<link rel="stylesheet" type="text/css" href="/mithgit/styles.css?2" />
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
function commentPost(id, myid)
{
   
   xmlHttp = CreateXMLHttpRequest();
   globalId = id;
   var url = 'registerVote.php?id='+id+'&myid='+myid;
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
<div id="container">
   <Table width = '80%'  cellspacing='0' bgcolor = '#ffffff' align = 'center' valign= 'top'>   <?php 
   
   include("/var/www/mithgit/core/top.layout.php");
   echo "<br /> <br />";
   
   $vote = $database->get_num_votes(5, 1);

   //$ids = array(896250631, 1008714147, 1012279117, 1019638372, 1022408517, 1022737191, 1050467088, 1059871404);
   
   $i = 0;
   
   $myuid = $facebook->api_client->users_GetLoggedInUser();
   for ($i = 0; $i < count($vote); $i++) {
    
    $user_details = $facebook->api_client->users_getInfo($vote[$i]['uid'], 'last_name, first_name, profile_url, pic_square');
    if ($user_details) { 
       $first_name = $user_details[0]['first_name']; 
       $last_name = $user_details[0]['last_name'];
       $full_name = $first_name." ".$last_name;
       $profile_url = $user_details[0]['profile_url'];
       $pic_square = $user_details[0]['pic_square'];
       if (! $pic_square) {
         $pic_square = "/mithgit/images/nullImage.gif";
       }
    
   ?>

    <tr>
       <th><a target = '_blank' href ="<?=$profile_url?>"><?=$full_name?></a></th>
       <tr width='10%'><th rowspan="2"><a target = '_blank' href ="<?=$profile_url?>"><img src="<?=$pic_square?>" /></a></th> </tr>
       <tr>
       <td>votes:<font size=20px><div id="<?=$vote[$i]['uid']?>"><?=$vote[$i]['num_votes']?> </div></font> </td>
       <td><input type ='submit' id="voteButton" value = 'Vote' OnClick = 'commentPost("<?=$vote[$i]['uid']?>", "<?=$myuid?>")';></input></td>
       </tr>
    </tr>

   <?php 
      }
   } ?>
   </Table>

   <? include("/var/www/mithgit/core/bottom.layout.php"); ?>
  
</div>
</body>
