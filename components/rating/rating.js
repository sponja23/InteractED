var Path = window.location.href;
var ID = Path.substring(Path.lastIndexOf('=') + 1, Path.length);

$.ajax({
    url: "../login/status.php",
    type: "POST",
    success: function (response) {
        if (response == 1) {
            $( "#rating" ).removeClass( "hide" );

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
        }
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