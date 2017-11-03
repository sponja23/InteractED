<?php session_start(); ?>
<!DOCTYPE html>
<html>
    <head>
        <title>InteractED</title>
        
        <link rel="stylesheet" href="../../components/navigation/navigation.css">
        <?php 
        require "../../include/head.html"; 
        require "../../include/connect.php";
        include "../categories.php";
        ?>
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

        <?php require "../../include/scripts.html"; ?>

        <script src="../../components/navigation/navigation.js"></script>

    </head>
    <body class="grey lighten-5">
        <?php require "../../components/navigation/navigation.php"; ?>

        <div class="container">
            <h5>Categoria: <?php echo $_GET["id"]; ?></h5>
                <?php
                    $categories = new Categories();

                    $categories->GetArticlesByCategories($_GET["id"]);
                ?>
        <div>
        <script>
            $( ".item" ).click(function() {
                window.location.href = "../../post?id=" + $(this).attr("id");
            });
        </script>
    </body>
</html>