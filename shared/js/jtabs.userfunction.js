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
    $('.edit_area').editable('shared/comments/saveStatus.php', { 
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

/* 
 * Need to keep this as the last function so that all other events are still registered when tabs are
 * switched automagically. Don't mess with it.
 */
$(document).ready(function() {
      $("#tabs").tabs({
         cookie: { expires: 30 },
         fx: { opacity: 'toggle'},
         spinner: 'Retrieving data...'
      });
      
   });



