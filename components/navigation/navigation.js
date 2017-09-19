$( "#search-mobile-icon" ).click(function() {
    $( "#mobile-search" ).removeClass("hide");
    $( "#navigation" ).addClass("hide");
    $( "#mobile-search-box" ).focus();
});

$( "#search-mobile-back" ).click(function() {
    $( "#mobile-search" ).addClass("hide");
    $( "#navigation" ).removeClass("hide");
});