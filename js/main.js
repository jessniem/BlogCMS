/*
Load more content with jQuery - May 21, 2013
(c) 2013 @ElmahdiMahmoud
*/

$(function () {
  $(".load-post").slice(0, 4).show();
  $("#loadMore").on('click', function (e) {
      e.preventDefault();
      $(".load-post:hidden").slice(0, 4).slideDown();
      if ($(".load-post:hidden").length == 0) {
          $("#load").fadeOut('slow');
      }
      $('html,body').animate({
          scrollTop: $(this).offset().top
      }, 1000);
  });
});

$('a[href=#top]').click(function () {
  $('body,html').animate({
      scrollTop: 0
  }, 600);
  return false;
});

$(window).scroll(function () {
  if ($(this).scrollTop() > 50) {
      $('.totop a').fadeIn();
  } else {
      $('.totop a').fadeOut();
  }
});
