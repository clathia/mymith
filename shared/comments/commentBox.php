<body>
<!-- Needs to be kept here in the start of body tag. Don't mess with it. -->
<script src="http://static.ak.connect.facebook.com/js/api_lib/v0.4/FeatureLoader.js.php/en_US" type="text/javascript"></script>

<!-- Note: Include this div markup as a workaround for a known bug in this release on IE where you may get a "operation aborted" error -->
<div id="FB_HiddenIFrameContainer" style="display:none; position:absolute; left:-100px; top:-100px; width:0px; height: 0px;"></div>


<a class="mithToggleLink" href=#>hide</a>

<div class="mithGodMessage">
   <?php
      $comment = $database->get_comments(5, 1, COMMENT_TYPE_GOD, 1);
      if (count($comment)) {
         echo "God says:"." ". $comment[0]['text'];
      }
   ?>
</div>

<div id="<?php echo $mithCommentPostIndicator ?>" class ="mithCommentIndicator">
   <img src ='shared/comments/images/indicator.gif'/>
</div>

<!-- All the files that include this file should provide with $button_value text -->
<div class="mithPostComment">
   <div class="mithTextForTextArea">
      <?php echo $mithTextHeader; ?>
   </div>
   <form method="get" action="" onsubmit="return false;">
      <textarea id="<?php echo $mithCommentText ?>">
      </textarea>
      <input class="mithPostButton" type='submit' value="<?php echo $mithButtonValue ?>"
       onclick='mithPostComment("<?php echo $mithCommentText ?>", "<?php echo $mithCommentBlob ?>", "<?php echo $mithCommentType ?>", "<?php echo $mithCommentPostIndicator ?>");'>
   </form>
</div> <!-- End div.mithPostComment -->

<?php
$comment = $database->get_comments(5, 1, $mithCommentType, 20);
$tmp = count($comment) - 1;
?>

<script type="text/javascript">
cbNumComments=<?php echo $tmp?>;
cbLastComment = <?php echo $comment[0]['comment_id']?>;
</script>

<a class="mithRefreshNowLink" href="#" onclick='mithGetNewComments("<?php echo $mithCommentBlob ?>", "<?php echo $mithCommentType ?>", "<?php echo $mithCommentPostIndicator ?>")'>
Refresh Now
</a>
<br />

<div id=<?php echo $mithCommentBlob ?> class="mithCommentBlob">
<?php
for($i = 0; $i <= $tmp; $i++) {
   $text = $comment[$i]['text'];
   $uid = $comment[$i]['uid'];
   echo display_comment($uid, $comment[$i]['timestamp'], $text);
  }
?>
</div>

<!-- Needs to be kept here at the end of body tag. Don't mess with it. -->
<script type="text/javascript">
FB_RequireFeatures(["XFBML"], function(){
   FB.Facebook.init("<?php echo $appapikey?>", "xd_receiver.htm");
   FB.CanvasClient.startTimerToSizeToContent();
   });
</script>
</body>