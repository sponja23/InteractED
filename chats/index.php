<?php session_start(); ?>
<!DOCTYPE html>
<html>
    <head>
        <title>Chats</title>

        <?php require "../include/head.html"; ?>

        <link rel="stylesheet" href="../components/navigation/navigation.css">
        <link rel="stylesheet" href="chats.css">
    </head>
    <body class="grey lighten-5">
        <?php require "../components/navigation/navigation.php"; ?>

        <div class="container">
            <div class="row">
                <div id="chats" class="card-panel col s12 m6 offset-m3"></div>
            </div>
        </div>

        <?php require "../include/scripts.html"; ?>

        <script src="../components/navigation/navigation.js"></script>
        <script src="chats.js"></script>
    </body>
</html>