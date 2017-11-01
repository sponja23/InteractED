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
                    echo '<a href="addCategory.php" class="btn-floating btn-large waves-effect waves-light blue"><i class="material-icons">add</i></a><br><br>';
                    for ($i=0; $i < 200; $i++) { 
                 echo '<a style="padding-right: 10px;" class="categoryOption waves-effect" id="1"><img src="../images/categories/1.png" alt="1" height="56" width="56"></a>';
                }
                    $categories = new Categories();

                    $categories->GetCategories();

                    //$categories->GetArticlesByCategories($_GET["q"]);
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