/*
Load more content with jQuery - May 21, 2013
(c) 2013 @ElmahdiMahmoud
*/
/*
$(function () {
  $(".load-post").slice(0, 5).show();
  $("#loadMore").on('click', function (e) {
      e.preventDefault();
      // console.log($(".load-post:hidden").slice(0, 5));
      $(".load-post:hidden").slice(0, 5).slideDown();
      if ($(".load-post:hidden").length == 0) {
          $("#load").fadeOut('slow');
      }
      $('html,body').animate({
          scrollTop: $(this).offset().top
      }, 1000);
  });
});*/
/*
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
*/


/**
* The function sets random color.
**/
function ran_col() {
  var colors = ['f7bc66','e27362','F39EC5', '60939f', '256B7c'];

  for (var i = 0; i < document.getElementsByClassName('comment').length; i++) {

    var color = colors[Math.floor(Math.random() * colors.length)];
    while (color == last) {
      color = colors[Math.floor(Math.random() * colors.length)];
    };
    document.getElementsByClassName('comment')[i].style.background = "#" + color;
    var last = color;
  };
};






/* HAMBURGER */
$( ".cross" ).hide();
$( ".basemenu" ).hide();
$( ".hamburger" ).click(function() {
$( ".basemenu" ).slideToggle( "slow", function() {
$( ".hamburger" ).hide();
$( ".cross" ).show();
});
});

$( ".cross" ).click(function() {
$( ".basemenu" ).slideToggle( "slow", function() {
$( ".cross" ).hide();
$( ".hamburger" ).show();
});
});
