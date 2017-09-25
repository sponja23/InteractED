var Path = window.location.href;
var UserCode = Path.substring(Path.lastIndexOf('=') + 1, Path.length);

var LoadedMessages = "";

$( document ).ready(function() {
    LoadChat(LoadedMessages);

    setInterval(function () {
        LoadChat(LoadedMessages);
    }, 5000);
});

$( "#message-box" ).on("input", function() {
    if( !$(this).val() )
        $( "#publish" ).removeClass( "enabled blue-text" ).addClass( "disabled grey-text" );
    else
        $( "#publish" ).addClass( "enabled blue-text" ).removeClass( "disabled grey-text" );
});

$( "#message-box" ).keypress(function(e) {
    if(e.which == 13 && !e.shiftKey) {
        $( "#publish" ).trigger( "click" );
    }
});

$( "#publish" ).click(function() {
    if ($( "#publish" ).hasClass( "enabled" ))
        UploadMessage($( "#message-box" ).val().replace(/\n/g, "<br>").replace(/ /g, "&nbsp;"));
});

function UploadMessage(Message) {
    $.ajax({
        url: "chat.php",
        type: "POST",
        data: { Function: "Upload", UserCode: UserCode, Message: Message } ,
        success: function (CommentID) {
            AddMessage(Message, "blue", "right");

            $( "#message-box" ).val("").css("height", "1.5rem");
            $( "#publish" ).removeClass( "enabled blue-text" ).addClass( "disabled grey-text" );

            LoadedMessages += CommentID + ';';
        },
        error: function(jqXHR, textStatus, errorThrown) {
            console.log(textStatus, errorThrown);
        }
    });
}

function LoadChat(DownloadedMessages) {
    $.ajax({
        url: "chat.php",
        type: "POST",
        data: { Function: "Load", UserCode: UserCode, DownloadedMessages: DownloadedMessages } ,
        success: function (response) {
            if (response != "") {
                var Chat = JSON.parse(response);

                if (Chat.LastID > 0) {
                    for (Entry in Chat) {
                        if (Entry != "LastID") {
                            LoadedMessages += Entry + ';';

                            if (Chat[Entry].UserCode == UserCode) {
                                var Color = "grey";
                                var ExtraClass = "left";
                            }
                            else {
                                var Color = "blue";
                                var ExtraClass = "right";
                            }

                            AddMessage(Chat[Entry].Message, Color, ExtraClass);
                        }
                    }
                }
            }
        },
        error: function(jqXHR, textStatus, errorThrown) {
            console.log(textStatus, errorThrown);
        }
    });
}

function AddMessage(Message, Color, ExtraClass) {
    $( "#messages" ).append(
        '<div class="col s12">' +
            '<div class="card-panel message-card ' + Color + ' lighten-3 ' + ExtraClass + '">' +
                '<span class="message-text">' + Message + '</span>' +
            '</div>' +
        '</div>'
    );
}