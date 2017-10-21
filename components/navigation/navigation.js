$( ".toggle-search" ).click(function() {
    $( ".logo" ).toggleClass( "hide" );
    $( "#right-buttons" ).toggleClass( "hide" );
    $( "#search-form" ).toggleClass( "hide-on-small-only" );
    $( "#search" ).focus();
});

$( "#search-close-icon" ).click(function() {
    $( "#search" ).val("").focus();
});