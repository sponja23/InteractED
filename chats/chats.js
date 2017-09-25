$.ajax({
    url: "chats.php",
    type: "POST",
    success: function (response) {
        console.log(response);
        if (response != "") {
            var Chats = JSON.parse(response);

            for (Entry in Chats) {
                $( "#chats" ).append(
                    '<div id="' + Entry + '" class="valign-wrapper chat">' +
                        '<div>' +
                            '<img class="circle image" src="' + Chats[Entry].Image + '">' +
                        '</div>' +
                        '<p class="truncate text">' +
                            '<strong>' + Chats[Entry].Name + '</strong>' +
                            '<br>' +
                            '<span>' + Chats[Entry].Message + '</span>' +
                        '</p>' +
                    '</div>'
                );
            }
        }
    },
    error: function(jqXHR, textStatus, errorThrown) {
        console.log(textStatus, errorThrown);
    }
});

$( "#chats" ).on("click", ".chat", function() {
    window.location.href = "../chat?id=" + $(this).attr("id");
});