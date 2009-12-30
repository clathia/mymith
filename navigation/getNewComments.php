<?php
require_once($_SERVER['DOCUMENT_ROOT'] . "/shared/helper.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/sql/database.php");

if ($_POST) {
   $lastComment = $_POST['lastComment'];
   $comment_type = $_POST['type'];
   $comment = $database->get_next_comments(5, 1, $comment_type, 20, $lastComment);
   $tmp = count($comment) - 1;
?> 

<link rel="stylesheet" type="text/css" href="styles.css?19" />  
<div class="commentBlob">
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

<?php 

}

?>
