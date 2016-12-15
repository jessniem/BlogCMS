
/**
* The function sets random color on class comment-fa.
**/
window.onload = function(){

    function ran_col() {
        var colors = ['f7bc66','e27362','F39EC5', '60939f', '256B7c'];

        for (var i = 0; i < document.getElementsByClassName('comment').length; i++) {

            var color = colors[Math.floor(Math.random() * colors.length)];

            while (color == last) {
                color = colors[Math.floor(Math.random() * colors.length)];
            };

            document.getElementsByClassName('comment-fa')[i].style.color = "#" + color;
            var last = color;
        };
    };
    ran_col();
};

/* HAMBURGER */
$( ".cross" ).hide();
$( ".basemenu" ).hide();

$( ".hamburgericon" ).click(function() {

    $( ".basemenu" ).slideToggle( "slow", function() {

        $( ".hamburgericon" ).hide();
        $( ".cross" ).show();
    });
});

$( ".cross" ).click(function() {

    $( ".basemenu" ).slideToggle( "slow", function() {

        $( ".cross" ).hide();
        $( ".hamburgericon" ).show();
    });
});


function reload() {
    $('#filter').submit();
}
