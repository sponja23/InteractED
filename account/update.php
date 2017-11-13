<?php
session_start();

require "../include/connect.php";

foreach ($_POST as $Key => $Value) {
    if (!($Key == "Password" && $Value == ""))
        $Fields .= $Key . '="' . $Value . '",';

    if ($Key != "Password")
        $_SESSION[$Key] = $Value;
}

$sql = "UPDATE Users SET " . substr($Fields, 0, -1) . " WHERE UserCode=" . $_SESSION["UserCode"];

if ($conn->query($sql) === TRUE) {
    if ($_FILES["image"]["name"] != "") {
        $File = "../images/users/" . $_SESSION["UserCode"] . "." . pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION);

        $CurrentImage = glob("../images/users/" . $_SESSION["UserCode"] . ".*");
        unlink($CurrentImage[0]);

        if (move_uploaded_file($_FILES["image"]["tmp_name"], $File))
            header("Location: ../account/");
    }
    else
        header("Location: ../account/");
}
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Resultado - Actualizar cuenta</title>

        <?php require "../include/head.html"; ?>

        <link rel="stylesheet" href="../components/navigation/navigation.css">
    </head>
    <body class="grey lighten-5">
        <?php require "../components/navigation/navigation.php"; ?>

        <div class="container">
            <div class="card-panel center-align">
                <span>Error al actualizar los datos de la cuenta</span>
            </div>
        </div>

        <?php require "../include/scripts.html"; ?>

        <script src="../components/navigation/navigation.js"></script>
    </body>
</html>