<?php session_start(); ?>
<!DOCTYPE html>
<html>
    <head>
        <title>InteractED</title>

        <?php require "../include/head.html"; ?>

        <link rel="stylesheet" href="../components/navigation/navigation.css">

        <style>
            .item:hover {
                cursor: pointer;
                cursor: hand;
            }
        </style>

        <?php require "../include/scripts.html"; ?>

        <script src="../components/navigation/navigation.js"></script>

    </head>
    <body class="grey lighten-5">
        <?php require "../components/navigation/navigation.php"; ?>

        <div class="container">
            <h5>Categorias</h5>
            <a href="addCategory.php" class="btn-floating btn-large waves-effect waves-light blue">
                <i class="material-icons">add</i>
            </a>
            <br><br>
            <?php
            include "categories.php";
            GetCategories();
            ?>
        <div>

        <script src="../components/navigation/navigation.js"></script>

        <script>
            $( ".categoryOption" ).click(function() {
                window.location.href = "../category/articles/?id=" + $(this).attr("id");
            });
        </script>
    </body>
</html>