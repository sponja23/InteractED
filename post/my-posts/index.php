<?php session_start(); ?>
<!DOCTYPE html>
<html>
    <head>
        <title>InteractED</title>

        <?php require "../../include/head.html"; ?>

        <link rel="stylesheet" href="../../components/navigation/navigation.css">

        <style>
            .item:hover {
                cursor: pointer;
                cursor: hand;
            }

            .results {
                font-size: 1.1rem;
                font-weight: 400;
                line-height: 2rem;
            }
        </style>
    </head>
    <body class="grey lighten-5">
        <?php require "../../components/navigation/navigation.php"; ?>

        <div class="container">
             <h5>Mis Articulos</h5>
            <div class="row">
                <?php
                    include "GetUserPosts.php";
                    GetUserPosts();
                ?>
            </div>
            
        <?php require "../../include/scripts.html"; ?>

        <script src="../../components/navigation/navigation.js"></script>

        <script>
            $( ".item" ).click(function() {
                window.location.href = "../?id=" + $(this).attr("id");
            });
            $( ".watch-later"  ).click(function() {
                $.ajax({
                        url: "../../watchlater/add.php",
                        type: "POST",
                        data: { id: $(this).attr("id")} ,
                        success: function (response) {
                            alert("Added to Watch Later");
                        },
                        error: function(jqXHR, textStatus, errorThrown) {
                            console.log(textStatus, errorThrown);
                        }
                    });
            });
        </script>
    </body>
</html>