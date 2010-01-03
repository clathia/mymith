<script type="text/javascript">
{
	var html = 0;
	var i = 0;

   <?php
   $comment = $database->get_comments(5, 1, $mithCommentType, 20);
   $tmp = count($comment) - 1;
   ?>

   mithCbComments = new Array(<?php echo $tmp ?>);

   <?php
      for($i = 0; $i <= $tmp; $i++) {
         /* In order to send it to JS - escape quotes */
         $text = addSlashes($comment[$i]['text']);
         $text = preg_replace("/\\n/"," ", $text);
         $uid = $comment[$i]['uid'];
         /* This processing can be done on client side too. Do it. */
         $timestamp = display_date($comment[$i]['timestamp']);
         echo "mithCbComments[$i] = new Array(3);";
         echo "mithCbComments[$i][0] = '$text';\n";
         echo "mithCbComments[$i][1] = '$uid';\n";
         echo "mithCbComments[$i][2] = '$timestamp';\n";
      }
   ?>

   for (i = 0; i < mithCbComments.length; i++) {
      html = mithCreateCommentHtml(mithCbComments[i][0], mithCbComments[i][1], mithCbComments[i][2]);
      $("#" + "<?php echo $mithCommentBlob ?>").append(html);
   }
   $(".mithCommentEntry:odd").addClass("mithCommentEntryClassOdd");
   $(".mithCommentEntry:even").addClass("mithCommentEntryClassEven");
   /* Now that we have added all the FB tags, it is time to make sense of those tags. */
   FB.XFBML.Host.parseDomTree();
}
</script>

<input type='hidden' id=<?php echo $mithCbLastNewComment ?> value=<?php echo $comment[0]['comment_id'] ?> />
<input type='hidden' id=<?php echo $mithCbLastOldComment ?> value=<?php echo $comment[$tmp]['comment_id'] ?> />

<a id=<?php echo $mithToggleLink ?> class="mithToggleLink" href=# onclick='mithToggleLinkFunc("<?php echo $mithGodMessage ?>", "<?php echo $mithToggleLink ?>")'>
   hide
</a>

<div id=<?php echo $mithGodMessage ?> class="mithGodMessage">
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
      <div class="mithButtons">
         <button class="mithPostButton" type='submit' value="<?php echo $mithButtonValue ?>"
          onclick='mithPostComment("<?php echo $mithCommentText ?>", "<?php echo $mithCommentBlob ?>", "<?php echo $mithCommentType ?>", "<?php echo $mithCommentPostIndicator ?>");'>
            <?php echo $mithButtonValue ?>
         </button>
      </div>
   </form>
</div> <!-- End div.mithPostComment -->

<a class="mithRefreshNowLink" href="#" onclick='mithGetNewComments("<?php echo $mithCommentBlob ?>", "<?php echo $mithCommentType ?>", "<?php echo $mithCommentPostIndicator ?>", "<?php echo $mithCbLastNewComment ?>", "<?php echo $mithNewMessage ?>")'>
Refresh Now
</a>
<br />


<div id=<?php echo $mithNewMessage ?>>
</div>


<div id=<?php echo $mithCommentBlob ?> class="mithCommentBlob">
</div>

<a href="#" onclick='mithGetOldComments("<?php echo $mithCommentBlob ?>", "<?php echo $mithCommentType ?>", "<?php echo $mithCommentPostIndicator ?>", "<?php echo $mithCbLastOldComment ?>")'>
More
</a>

