<?php
session_start();

if(!isset($_SESSION["UserCode"]))
    header("Location: ../");

require "../include/connect.php";

$sql = "SELECT Name FROM Users WHERE UserCode = " . $_GET["id"];
$result = $conn->query($sql);

$row = $result->fetch_assoc();
$UserName = $row["Name"];

$UserImage = glob("../images/users/" . $_GET["id"] . ".*");
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Chat</title>

        <?php require "../include/head.html"; ?>

        <link rel="stylesheet" href="chat.css">
        <link rel="stylesheet" href="../components/navigation/navigation.css">
    </head>
    <body class="grey lighten-5">
        <?php require "../components/navigation/navigation.php"; ?>

        <div class="container">
            <div id="card" class="card-panel blue">
                <div id="title" class="col s12 blue darken-3 valign-wrapper">
                    <a href="../chats"><i id="back" class="material-icons white-text">arrow_back</i></a>
                    <div id="image-wrapper">
                        <img id="image" class="circle" src=<?= '"' . $UserImage[0] . '"' ?>>
                    </div>
                    <p id="user" class="white-text"><?= $UserName ?></p>
                </div>

                <div id="messages" class="row"></div>

                <div id="message-box-card" class="card-panel">
                    <div id="message-box-row" class="row valign-wrapper">
                        <div id="message-box-wrapper" class="input-field col s12">
                            <textarea id="message-box" class="materialize-textarea" placeholder="Escribir mensaje"></textarea>
                        </div>
                        <i id="publish" class="material-icons disabled grey-text">send</i>
                    </div>
                </div>
            </div>
        </div>

        <?php require "../include/scripts.html"; ?>

        <script src="chat.js"></script>
        <script src="../components/navigation/navigation.js"></script>
    </body>
</html>