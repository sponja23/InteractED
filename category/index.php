<?php session_start(); ?>
<!DOCTYPE html>
<html>
    <head>
        <title>InteractED</title>

        <?php 
        require "../include/head.html"; 
        require "../include/connect.php";
        include "categories.php";
        ?>

        <link rel="stylesheet" href="../components/navigation/navigation.css">

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

        <?php require "../include/scripts.html"; ?>

        <script src="../components/navigation/navigation.js"></script>

    </head>
    <body class="grey lighten-5">
        <?php require "../components/navigation/navigation.php"; ?>

        <div class="container">
            <h5>Categorias</h5>
                <?php
                    echo '<a style="padding-right:5px" href="addCategory.php" class="btn-floating btn-large waves-effect waves-light blue"><i class="material-icons">add</i></a>';
                    if($_SESSION["Level"] >= 2)
                        echo '&nbsp;<a href="confirm" class="btn-floating btn-large waves-effect waves-light blue"><i class="material-icons">mode_edit</i></a><br><br>';
                    else
                        echo '<br><br>';

                    GetCategories();

                ?>
        <div>
        <script>
            $( ".item" ).click(function() {
                window.location.href = "../post?id=" + $(this).attr("id");
            });
            $( ".categoryOption" ).click(function() {
                window.location.href = "../category/articles/?id=" + $(this).attr("id");
            });
        </script>
    </body>
</html>