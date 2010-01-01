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
      spinner: 'Retrieving data...'
   });   
});


/*----------------------------------------------------------------------------------------
 * mithCreateCommentHtml --
 *   Post the comment to the server and display it.
 *
 *   Both cityBox and mafiaBox uses this function. As a side-effect page will get updated
 *   when the response arrives. One day, I will add ajax error function too.
 *
 * @text     
 * @uid 
 * @timestamp   
 *
 * @return none
 *----------------------------------------------------------------------------------------
 */
function
mithCreateCommentHtml(text,
		              uid,
		              timestamp) {

   var html = '<div class="mithCommentEntry">\n';
   html +=    '<table cellspacing=\'0\'>\n';
   html +=    '<tr>\n';
   html +=    '<td width=55px valign="top">\n';
   html +=    '<fb:profile-pic uid=\''+ uid + '\' facebook-logo="false" size="square" linked="true">\n';
   html +=    '<\/fb:profile-pic>\n';
   html +=    '<\/td>\n';
   html +=    '<td valign="top">';
   html +=    '<div class="mithFullName">';
   html +=    '<fb:name uid=\''+ uid + '\' linked="true" useyou="false">';
   html +=    '<\/fb:name>\n';
   html +=    '<span class="mithDate">'+ timestamp +'<\/span>\n';
   html +=    '<div class="mithCommentText">'+ stripslashes(text) +'<\/div>\n';
   html +=    '<\/td>\n';
   html +=    '<\/tr>\n';
   html +=    '<\/table>\n';
   html +=    '<\/div>';
   return html;

}

function addslashes(str) {
	str=str.replace(/\\/g,'\\\\');
	str=str.replace(/\'/g,'\\\'');
	str=str.replace(/\"/g,'\\"');
	str=str.replace(/\0/g,'\\0');
	return str;
}
	
function stripslashes(str) {
	str=str.replace(/\\'/g,'\'');
	str=str.replace(/\\"/g,'"');
	str=str.replace(/\\0/g,'\0');
	str=str.replace(/\\\\/g,'\\');
	return str;
}


function
mithToggleLinkFunc(godMessageId,
		           toggleLinkId)
{
	$("#"+godMessageId).toggle();
	$("#"+toggleLinkId).text($("#"+toggleLinkId).text() == "show" ? "hide" : "show");
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
   var text = document.getElementById(commentTextId).value;
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
            var html = mithCreateCommentHtml(text, json.uid, json.date);
    	    var response = "<div id="+ mithDummyId + " > " + html + "</div>";
    		$("#"+commentBlobId).prepend(response);
    		FB.XFBML.Host.parseDomElement(document.getElementById(mithDummyId));
    		document.getElementById(commentTextId).value = '';
    		document.getElementById(commentPostIndicator).style.visibility = 'hidden';
    		var newClass = $("#"+commentBlobId +" .mithCommentEntry").length % 2 == 0 ? 'mithCommentEntryClassEven' : 'mithCommentEntryClassOdd';
    		$("#"+mithDummyId).addClass(newClass);
    		mithDummyId++;
    	 } else {
    		 alert("Server rejected your request.");
    	 }
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

function 
mithGetNewComments(commentBlobId,
		           commentType,
		           commentPostIndicator,
		           lastNewCommentId,
		           newMessageId)
{
   $.ajax({
      type: "POST",
      global: false,
      url: "navigation/getNewComments.php",
      dataType: "json",
      data: 'lastComment='+$("#"+lastNewCommentId).val()+'&type='+commentType,
      error: function(XMLHttpRequest, textStatus) {
	     alert("Some error occured. We will fix asap.");
      },
      success: function(json){
    	 if (json.ret == 0 && json.numNewComments > 0) {
            $.each(json.comments, function (i, comment) {
        	   var html = mithCreateCommentHtml(comment.text, comment.uid, comment.timestamp);
         	   var response = "<div id="+ mithDummyId + " > " + html + "</div>";
         	   $("#"+commentBlobId).prepend(response);
               FB.XFBML.Host.parseDomElement(document.getElementById(mithDummyId));
         	   var newClass = $("#"+commentBlobId +" .mithCommentEntry").length % 2 == 0 ? 'mithCommentEntryClassEven' : 'mithCommentEntryClassOdd';
         	   $("#"+mithDummyId).addClass(newClass); 
         	   mithDummyId++;
    	   })
    	   $("#"+lastNewCommentId).val(json.lastCommentId);
    	 }
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

function 
mithGetOldComments(commentBlobId,
		           commentType,
		           commentPostIndicator,
		           lastOldCommentId)
{
   $.ajax({
      type: "POST",
      global: false,
      url: "navigation/getOldComments.php",
      dataType: "json",
      data: 'lastComment='+$("#"+lastOldCommentId).val()+'&type='+commentType,
      error: function(XMLHttpRequest, textStatus) {
	     alert("Some error occured. We will fix asap.");
      },
      success: function(json){
         $.each(json.comments, function (i, comment) {
        	 var html = mithCreateCommentHtml(comment.text, comment.uid, comment.timestamp);
         	 var response = "<div id="+ mithDummyId + " > " + html + "</div>";
         	 $("#"+commentBlobId).append(response);
             FB.XFBML.Host.parseDomElement(document.getElementById(mithDummyId));
         	 var newClass = $("#"+commentBlobId +" .mithCommentEntry").length % 2 == 0 ? 'mithCommentEntryClassEven' : 'mithCommentEntryClassOdd';
         	 $("#"+mithDummyId).addClass(newClass); 
         	 mithDummyId++;
    	 })
    	 $("#"+lastOldCommentId).val(json.lastCommentId);
      }
   }); 
}


function mithStatusRegisterVote(id, myid, type)
{  
   $.ajax({
      type: "POST",
	  global: false,
	  url: "navigation/registerVote.php",
	  dataType: "json",
	  data: 'id='+id+'&myid='+myid,
	  error: function(XMLHttpRequest, textStatus) {
	   	     alert("Some error occured. We will fix asap."); 
	         },
	  success: function(json){
	    	    if (json.ret == 0) {
	    		   document.getElementById(id).innerHTML = json.voteCount
	    	    } else {
	    		   alert("Server rejected your request.");
	    	    }
	         }
   });   
}