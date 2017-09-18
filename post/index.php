<?php session_start(); ?>
<!DOCTYPE html>
<html>
    <head>
        <title>Titulo</title>

        <?php require "../include/head.html"; ?>

        <link rel="stylesheet" href="../css/navigation.css">
        <link rel="stylesheet" href="../css/font.css">
        <link rel="stylesheet" href="../components/comments/comments.css">
    </head>
    <body class="grey lighten-5">
        <?php require "../components/navigation.php"; ?>

        <div class="container">
            <p>Hola, soy un post</p>
            <?php require "../components/comments/index.php"; ?>
        </div>

        <?php require "../include/scripts.html"; ?>

        <script src="../components/comments/comments.js"></script>
    </body>
</html>