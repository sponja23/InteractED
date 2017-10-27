<?php
session_start();

require "../include/connect.php";

$sql = 'SELECT Name FROM Users WHERE UserCode=' . $_GET["id"];

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $Name = $row['Name'];
        $Extension = pathinfo(glob("../images/" . $_GET["id"] . ".*")[0])['extension'];
        $Image = "../images/" . $_GET["id"] . '.' . $Extension;
    }
}

$File = "../chats/users/" . $_SESSION["UserCode"] . ".chats";

if (file_exists($File)) {
    $Chats = json_decode(file_get_contents($File), true);
    $Chats["Chats"][count($Chats["Chats"])] = $_GET["id"];
    file_put_contents($File, json_encode($Chats));
}
else {
    mkdir("../chats/users");
    file_put_contents($File, '{"Chats":[' . $_GET["id"] . ']}');
}
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Chat</title>

        <?php require "../include/head.html"; ?>

        <link rel="stylesheet" href="../components/navigation/navigation.css">
        <link rel="stylesheet" href="chat.css">
    </head>
    <body class="grey lighten-5">
        <?php require "../components/navigation/navigation.php"; ?>

        <div class="container">
            <div id="card" class="card-panel blue">
                <div id="title" class="col s12 blue darken-3 valign-wrapper">
                    <a href="../chats"><i id="back" class="material-icons white-text">arrow_back</i></a>
                    <img id="image" class="circle" src=<?= '"' . $Image . '"' ?>>
                    <p id="user" class="white-text"><?= $Name ?></p>
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

        <script src="../components/navigation/navigation.js"></script>
        <script src="chat.js"></script>
    </body>
</html>