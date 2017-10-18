$( ".search-toggle" ).click(function() {
    $( "#chats-wrapper, #search-users" ).toggleClass( "hide" );
    $( "#user-search" ).focus();
});

$.ajax({
    url: "chats.php",
    type: "POST",
    success: function (response) {
        if (response != "") {
            var Chats = JSON.parse(response);

            for (Entry in Chats)
                AddChat(Entry, "../images/" + Entry + '.' + Chats[Entry].Extension, Chats[Entry].Name, Chats[Entry].Message);
        }
        else
            $( "#chats-wrapper" ).append('<p class="center-align">No tiene ningun chat, cree uno nuevo para empezar a hablar</p>');
    },
    error: function(jqXHR, textStatus, errorThrown) {
        console.log(textStatus, errorThrown);
    }
});

function AddChat(ID, Image, Name, Message) {
    $( "#chats-wrapper" ).append(
        '<div id="' + ID + '" class="valign-wrapper chat">' +
            '<div>' +
                '<img class="circle image" src="' + Image + '">' +
            '</div>' +
            '<p class="truncate text">' +
                '<strong>' + Name + '</strong>' +
                '<br>' +
                '<span>' + Message + '</span>' +
            '</p>' +
        '</div>'
    );
}

$( ".container" ).on("click", ".chat", function() {
    window.location.href = "../chat?id=" + $(this).attr("id");
});

$( "#user-close-icon" ).click(function() {
    $( "#user-search" ).val("").focus();
});

$( "#user-search" ).keydown(function() {
    $( "#search-results" ).empty();

    if ($( "#user-search" ).val() != "")
        $.ajax({
            url: "search-users.php",
            type: "POST",
            data: { Search: $( "#user-search" ).val() } ,
            success: function (response) {
                console.log(response);
                if (response != "") {
                    var Chats = JSON.parse(response);

                    for (Entry in Chats)
                        AddSearchEntry(Entry, "../images/" + Entry + '.' + Chats[Entry].Extension, Chats[Entry].Name);
                }
                else
                    $( "#search-results" ).append('<p class="center-align generic">No se encontraron resultados que coincidan</p>');
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.log(textStatus, errorThrown);
            }
        });
    else
        $( "#search-results" ).append('<p class="center-align generic">Ingrese un usuario, nombre o correo electr&oacute;nico</p>');
});

function AddSearchEntry(ID, Image, Name) {
    $( "#search-results" ).append(
        '<div id="' + ID + '" class="valign-wrapper chat">' +
            '<div>' +
                '<img class="circle image" src="' + Image + '">' +
            '</div>' +
            '<p class="truncate text">' + Name + '</p>' +
        '</div>'
    );
}