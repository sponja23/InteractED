$.ajax({
    url: "../login/status.php",
    type: "POST",
    success: function (response) {
        if (response == 1)
            $( "#rating" ).removeClass( "hide" );
    },
    error: function(jqXHR, textStatus, errorThrown) {
        console.log(textStatus, errorThrown);
    }
});