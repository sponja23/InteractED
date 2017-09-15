<?php session_start(); ?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">

        <title>Titulo</title>

        <?php require "../include/head.html"; ?>

        <link rel="stylesheet" href="../css/navigation.css">
        <link rel="stylesheet" href="../css/font.css">
        <link rel="stylesheet" href="../components/comments/comments.css">
    </head>
    <body>
        <?php require "../components/navigation.php"; ?>

        <div class="container">
            <p>Hola, soy un post</p>
            <?php require "../components/comments/index.php"; ?>
        </div>

        <?php require "../include/scripts.html"; ?>

        <script src="../components/comments/comments.js"></script>
    </body>
</html>