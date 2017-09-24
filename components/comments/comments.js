var Path = window.location.href;
var PostID = Path.substring(Path.lastIndexOf('=') + 1, Path.length);

var LoadedComments = "";

$( document ).ready(function() {
    LoadComments(1, LoadedComments);

    setInterval(function () {
        LoadComments(1, LoadedComments);
    }, 5000);
});

$.ajax({
    url: "../login/status.php",
    type: "POST",
    success: function (response) {
        if (response == 1) {
            $( "#bottom-divider" ).removeClass( "hide" );
            $( "#comment-box" ).removeClass( "hide" );

            $( "#comment" ).on('input', function() {
                if( !$(this).val() ) {
                    $( "#publish" ).addClass( "disabled" );
                }
                else {
                    $( "#publish" ).removeClass( "disabled" );
                }
            });

            $( "#publish" ).click(function() {
                UploadComment($( "#comment" ).val().replace(/\n/g, "<br>"));
            });
        }
    },
    error: function(jqXHR, textStatus, errorThrown) {
        console.log(textStatus, errorThrown);
    }
});

function LoadComments(PostID, DownloadedComments) {
    $.ajax({
        url: "../components/comments/GetComments.php",
        type: "POST",
        data: { PostID: PostID, DownloadedComments: DownloadedComments } ,
        success: function (response) {
            if (response != "") {
                var Data = JSON.parse(response);

                $( "#top-divider" ).removeClass( "hide" );

                for (Entry in Data.Comments) {
                    if (Entry != "LastID") {
                        LoadedComments += Entry + ';';

                        var Name = Data.UserData[Data.Comments[Entry].UserCode].Name;
                        var Image = Data.UserData[Data.Comments[Entry].UserCode].Image;
                        var Comment = Data.Comments[Entry].Comment;
                        //var Time = "3 h"

                        $( "#comments" ).append(
                            "<div class=\"comment-wrapper\">" +
                                "<img class=\"circle comment-image\" src=\"" + Image + "\">" +
                                //"<div style=\"width: 100%;\">" +
                                    "<p class=\"comment-text\">" +
                                        "<strong>" + Name + "</strong>" +
                                        //"<span style=\"float: right; color: #757575;\">" + Time + "</span>" +
                                        "<br>" +
                                        Comment +
                                    "</p>" +
                                //"</div>" +
                            "</div>"
                        );
                    }
                }
            }
        },
        error: function(jqXHR, textStatus, errorThrown) {
            console.log(textStatus, errorThrown);
        }
    });
}

function UploadComment(Comment) {
    $.ajax({
        url: "../components/comments/UploadComment.php",
        type: "POST",
        data: { PostID: PostID, Comment: Comment } ,
        success: function (response) {
            var Data = JSON.parse(response);

            $( "#top-divider" ).removeClass( "hide" );

            $( "#comments" ).append(
                "<div class=\"comment-wrapper\">" +
                    "<img class=\"circle comment-image\" src=\"" + Data.Image + "\">" +
                    "<p class=\"comment-text\">" + "<strong>" + Data.Name + "</strong>" + "<br>" + Comment + "</p>" +
                "</div>"
            );

            $( "#comment" ).val("").css("height", "auto");
            $( "#publish" ).addClass( "disabled" );

            LoadedComments += Data.CommentID + ';';
        },
        error: function(jqXHR, textStatus, errorThrown) {
            console.log(textStatus, errorThrown);
        }
    });
}