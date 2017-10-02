$.ajax({
    url: "chats.php",
    type: "POST",
    success: function (response) {
        if (response != "") {
            var Chats = JSON.parse(response);

            for (Entry in Chats)
                AddChat(Entry, Chats[Entry].Image, Chats[Entry].Name, Chats[Entry].Message);
        }
        else
            $( "#chats" ).append('<p class="center-align">No tiene ningun chat, cree uno nuevo para empezar a hablar</p>');
    },
    error: function(jqXHR, textStatus, errorThrown) {
        console.log(textStatus, errorThrown);
    }
});

$( "#chats" ).on("click", ".chat", function() {
    window.location.href = "../chat?id=" + $(this).attr("id");
});

function AddChat(ID, Image, Name, Message) {
    $( "#chats" ).append(
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