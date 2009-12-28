$(document).ready(function() {
   $("#userSlide").jCarouselLite({
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

/* Need to keep this last */
$(document).ready(function() {
      $("#tabs").tabs({
         cookie: { expires: 30 },
         fx: { opacity: 'toggle'},
         spinner: 'Retrieving data...'
      });
      
   });


