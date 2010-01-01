<body>
<!-- Needs to be kept here in the start of body tag. Don't mess with it. -->
<script src="http://static.ak.connect.facebook.com/js/api_lib/v0.4/FeatureLoader.js.php/en_US" type="text/javascript"></script>

<!-- Note: Include this div markup as a workaround for a known bug in this release on IE where you may get a "operation aborted" error -->
<div id="FB_HiddenIFrameContainer" style="display:none; position:absolute; left:-100px; top:-100px; width:0px; height: 0px;"></div>


<script type="text/javascript">
{
	var html = 0;
	var i = 0;
	var mithCbNumComments;
   <?php
   $comment = $database->get_comments(5, 1, $mithCommentType, 20);
   $tmp = count($comment) - 1;
   ?>

   mithCbNumComments = <?php echo $tmp?>;

   <?php
   if ($mithCommentType == COMMENT_TYPE_MAFIA) {
   ?>
      mithCbMafiaLastComment = <?php echo $comment[0]['comment_id']?>;
   <?php
   } else {
   ?>
	   mithCbCityLastComment = <?php echo $comment[0]['comment_id']?>;
   <?php
	}
   ?>

   mithCbComments = new Array(<?php echo $tmp ?>);

   <?php
      for($i = 0; $i <= $tmp; $i++) {
         $text = $comment[$i]['text'];
         $uid = $comment[$i]['uid'];
         /* This processing can be done on client side too. Do it. */
         $timestamp = display_date($comment[$i]['timestamp']);
         echo "mithCbComments[$i] = new Array(3);";
         echo "mithCbComments[$i][0] = escape('$text');\n";
         echo "mithCbComments[$i][1] = '$uid';\n";
         echo "mithCbComments[$i][2] = '$timestamp';\n";
      }
   ?>

   for (i = 0; i < mithCbComments.length; i++) {
      html = mithCreateCommentHtml(mithCbComments[i][0], mithCbComments[i][1], mithCbComments[i][2]);
      $("#" + <?php echo $mithCommentBlob ?>).append(html);
   }
   $(".mithCommentEntry:odd").addClass("mithCommentEntryClassOdd");
   $(".mithCommentEntry:even").addClass("mithCommentEntryClassEven");
}
</script>

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

<a class="mithRefreshNowLink" href="#" onclick='mithGetNewComments("<?php echo $mithCommentBlob ?>", "<?php echo $mithCommentType ?>", "<?php echo $mithCommentPostIndicator ?>", "<?php echo $mithNewMessage ?>")'>
Refresh Now
</a>
<br />

<div id=<?php echo $mithNewMessage ?>>
</div>

<div id=<?php echo $mithCommentBlob ?> class="mithCommentBlob">
</div>

<!-- Needs to be kept here at the end of body tag. Don't mess with it. -->
<script type="text/javascript">
FB_RequireFeatures(["XFBML"], function(){
   FB.Facebook.init("<?php echo $appapikey?>", "xd_receiver.htm");
   FB.CanvasClient.startTimerToSizeToContent();
   });
</script>
</body>
