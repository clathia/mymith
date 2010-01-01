/*
 * Copyright 2009 MiTH.  All Rights Reserved. 
 *
 * Application: MiTH (Mafia in The House)
 * File: 'mithFunctions.js'
 * Contains main javascript functions. 
 */

/*----------------------------------------------------------------------------------------
 * FunctionName --
 *   One line comment description.
 *
 *   [In detail description]
 *
 * @param1 Description1
 * @param2 Description2
 *
 * @return params
 *----------------------------------------------------------------------------------------
 */
$(document).ready(function() {
   $(".userSlide").jCarouselLite({
    btnNext: ".next",
    btnPrev: ".prev",
    mouseWheel: true,
    visible: 3,
    circular:false
 });
});

$(document).ready(function() {
    $('.editStatus').editable('shared/comments/saveStatus.php', { 
        type      : 'textarea',
        cancel    : 'Cancel',
        submit    : 'OK',
        indicator : '<img src="/shared/comments/images/indicator.gif">',
        tooltip   : 'Click to edit...'
    });
});

$(function () {
    //You can change the defaults like this:
    //$.extend($.fn.dtpicker.defaults, { squeeze: true });
    //You can apply the dtpicker to all inputs (type=date, datetime, time, datetime-local) like this:
    $("#deadline").dtpicker();
    //Or you could have done the same thing but applying options:
    //$("input").dtpicker({ squeeze: true });
    //I've used the metadata plugin and applied options in the class attribute below
});

$("a.mithToggleLink").live("click", function () {
   $("div.mithGodMessage").slideToggle("fast");
   $(this).text($(this).text() == "hide" ? "show" : "hide");
});



/*----------------------------------------------------------------------------------------
 * Anonymous --
 *   After the DOM is ready, event is registered on #tabs.
 *
 *   Tabs uses this functionality. Need to keep this as the last function so that all
 *   other events are still registered when tabs are switched automagically. Note that
 *   I have enabled caching currently. If there are problems we will need to think.
 *   Don't mess with it.
 *
 * @cookie    Keep note of last selected tab by user.
 * @fx        UI effects.
 * @spinner   Message to display when page is loading. XXX Never gets displayed!
 * @load      Function is called whenever content of the remote tab is loaded, which
 *            means that as of it is now called for cityBox and mafiaBox.
 *
 * @return none
 *----------------------------------------------------------------------------------------
 */

$(document).ready(function() {
   $("#tabs").tabs({
      cookie: { expires: 30 },
      fx: { opacity: 'toggle'},
      cache: true,
      spinner: 'Retrieving data...',
      load: function(event, ui) {
    	  $(".mithCommentEntry:odd").addClass("mithCommentEntryClassOdd");
    	  $(".mithCommentEntry:even").addClass("mithCommentEntryClassEven");
      }
   });   
});


function
mithGenerateCommentResponse(commentText,
		                    commentDate,
		                    uid)
{
/*	return
	<div class="commentTable">
    <table width='100%' cellspacing='0' align='center'>
    <tr>
    <td width=55px valign="top">
    <fb:profile-pic uid='$uid' facebook-logo="false" size="square" linked="true">
    </fb:profile-pic>
    </td>
    <td valign="top"><div class="fullName"><fb:name uid='$uid' linked="true" useyou="false"></fb:name>
    <span class="date">$date</span></div>
    <div class="commentTextOld">$text</div>
    </td>
    </tr>
    </table>
    </div>
	alert(commentText + " "+commentDate+" "+uid);*/
}

/*----------------------------------------------------------------------------------------
 * mithPostComment --
 *   Post the comment to the server and display it.
 *
 *   Both cityBox and mafiaBox uses this function. As a side-effect page will get updated
 *   when the response arrives. One day, I will add ajax error function too.
 *
 * @commentTextId Id of comment textarea
 * @commentBlobId Id of comment blob, area where comments are displayed
 * @commentType   can be COMMENT_CITY_TYPE or COMMENT_MAFIA_TYPE
 *
 * @return none
 *----------------------------------------------------------------------------------------
 */
var mithDummyId = 1;

function 
mithPostComment(commentTextId,
		        commentBlobId,
		        commentType,
		        commentPostIndicator)
{
   var text = fixText(document.getElementById(commentTextId).value);
   document.getElementById(commentPostIndicator).style.visibility = 'visible';
   $.ajax({
      type: "POST",
      global: false,
      url: "shared/comments/saveComment.php",
      dataType: "json",
      data: 'commentText='+text+'&type='+commentType,
      error: function(XMLHttpRequest, textStatus) {
   	     alert("Some error occured. We will fix asap."); 
      },
      success: function(json){
    	 if (json.ret == 0) {
            mithGenerateCommentResponse(text, json.date, json.uid);
    	 }
    	  /*
         var response = "<div id="+ mithDummyId + " > " + html + "</div>";
	     $("#"+commentBlobId).prepend(response);
	     FB.XFBML.Host.parseDomElement(document.getElementById(mithDummyId));
	     document.getElementById(commentTextId).value = '';
	     document.getElementById(commentPostIndicator).style.visibility = 'hidden';
	     var newClass = $("#"+commentBlobId +" .commentTable").length % 2 == 0 ? 'commentTableClassEven' : 'commentTableClassOdd';
	 	 $("#"+mithDummyId).addClass(newClass);
	     mithDummyId++;
	     */
      }
	 });
}


/*----------------------------------------------------------------------------------------
 * mithGetNewComments --
 *   Get new messages from the server.
 *
 *   Both cityBox and mafiaBox uses this function. As a side-effect page will get updated
 *   when the response arrives. One day, I will add ajax error function too.
 *
 * @commentTextId Id of comment textarea
 * @commentBlobId Id of comment blob, area where comments are displayed
 * @commentType   can be COMMENT_CITY_TYPE or COMMENT_MAFIA_TYPE
 *
 * @return none
 *----------------------------------------------------------------------------------------
 */

var cbLastComment;

function 
mithGetNewComments(commentBlobId,
		           commentType,
		           commentPostIndicator)
{
   alert("this function called\n");
   
   $.ajax({
      type: "POST",
      global: false,
      url: "navigation/getNewComments.php",
      dataType: "json",
      data: 'lastComment='+cbLastComment+'&type='+commentType,
      error: function(XMLHttpRequest, textStatus) {
	     alert("Some error occured. We will fix asap.");
      },
      success: function(json){
    	 alert("got datatype json"+ json.numNewComments);
    	  /*
         var response = "<div id="+ mithDummyId + " > " + xmlHttp.responseText + "</div>";
         $("#cbCommentBlob").prepend(response);
         FB.XFBML.Host.parseDomElement(document.getElementById(mithDummyId));
         document.getElementById("cbIndicator").style.visibility = 'hidden';
         $("#cbCommentText").val("");
         cbDummyId++;
         */
      }
   });
   
}

