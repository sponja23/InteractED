var Path = window.location.href;
var ID = Path.substring(Path.lastIndexOf('=') + 1, Path.length);

$.ajax({
    url: "../components/rating/rating.php",
    type: "POST",
    data: { ID: ID, Action: "Get" },
    success: function (response) {
        if (response != "")
            $( "#star" + response ).prop( "checked", true );
    },
    error: function(jqXHR, textStatus, errorThrown) {
        console.log(textStatus, errorThrown);
    }
});

$('.rating input').change(function() {
    var Rating = $( this ).attr( "value" );

    $.ajax({
        url: "../components/rating/rating.php",
        type: "POST",
        data: { ID: ID, Action: "Upload", Rating: Rating },
        success: function (response) {
            console.log(response);
        },
        error: function(jqXHR, textStatus, errorThrown) {
            console.log(textStatus, errorThrown);
        }
    });
});

$( ".rating #reset" ).click(function() {
    $.ajax({
        url: "../components/rating/rating.php",
        type: "POST",
        data: { ID: ID, Action: "Reset" },
        success: function (response) {
            if (response == '1')
                for (i = 1; i <= 5; i++)
                    $( "#star" + i ).prop( "checked", false );
        },
        error: function(jqXHR, textStatus, errorThrown) {
            console.log(textStatus, errorThrown);
        }
    });
});