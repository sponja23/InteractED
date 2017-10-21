<?php
session_start();

if (!isset($_SESSION["UserCode"]))
    header("Location: ../login");
?>
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
                <div id="chats-wrapper" class="card-panel col s12 m6 offset-m3">
                    <div id="new-chat" class="valign-wrapper search-toggle">
                        <div id="new-chat-button" class="blue circle valign-wrapper">
                            <i id="new-chat-icon" class="material-icons white-text">add</i>
                        </div>
                        <span class="text">Conversaci&oacute;n nueva</span>
                    </div>
                    <div id="chats"></div>
                </div>
                <div id="search-users" class="card-panel col s12 m6 offset-m3 hide">
                    <div id="search-user-wrapper" class="input-field col s12">
                        <i id="user-back-icon" class="material-icons prefix grey-text search-toggle">arrow_back</i>
                        <input id="user-search" type="search" placeholder="Usuario, nombre o correo electr&oacute;nico">
                        <i id="user-close-icon" class="material-icons waves-effect waves-light">close</i>
                    </div>
                    <div id="search-results">
                        <p class="center-align generic">Ingrese un usuario, nombre o correo electr&oacute;nico</p>
                    </div>
                </div>
            </div>
        </div>

        <?php require "../include/scripts.html"; ?>

        <script src="../components/navigation/navigation.js"></script>
        <script src="chats.js"></script>
    </body>
</html>