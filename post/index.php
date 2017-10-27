<?php
session_start();

include "../include/connect.php";

$sql = 'SELECT * FROM Articles
        INNER JOIN Users ON Articles.CreatorID = Users.UserCode
        WHERE MD5(PostID) = "' . $_GET["id"] . '"';

$result = $conn->query($sql);

if ($result->num_rows > 0)
    while ($row = $result->fetch_assoc()) {
        $PostImage = glob("../images/" . $row["UserCode"] . ".*");
        $PostImage = "../images/" . basename($PostImage[0]);
        $Title = $row["Title"];
        $UserImage = glob("../images/" . $row["UserCode"] . ".*");
        $UserImage = "../images/" . basename($UserImage[0]);
        $Name = $row["Name"];
        $CreateDate = $row["CreateDate"];
    }
else
    header("Location: ../");

$conn->close();
?>
<!DOCTYPE html>
<html>
    <head>
        <title><?= $Title ?> - InteractED</title>

        <?php require "../include/head.html"; ?>

        <link rel="stylesheet" href="styles.css">
        <link rel="stylesheet" href="../components/navigation/navigation.css">
        <link rel="stylesheet" href="../components/rating/rating.css">
        <link rel="stylesheet" href="../components/comments/comments.css">
    </head>
    <body class="grey lighten-5">
        <?php require "../components/navigation/navigation.php"; ?>

        <div class="container">
            <img id="post-image" src=<?= '"' . $PostImage . '"' ?>>
            <h5 id="post-title"><?= $Title ?></h5>
            <div class="valign-wrapper">
                <img id="post-creator-image" class="circle" src=<?= '"' . $UserImage . '"' ?>>
                <p>
                    <strong><?= $Name ?></strong>
                    <br>
                    Creado el <?= $CreateDate ?>
                </p>
                <a href=<?= "../editor?id=" . $_GET["id"] ?> id="edit" class="btn blue waves-effect waves-light">Editar post</a>
            </div>
            <?= file_get_contents("content/" . $_GET["id"] . "/index.html") ?>
            <?php require "../components/rating/index.html"; ?>
            <?php require "../components/comments/index.php"; ?>
        </div>

        <?php require "../include/scripts.html"; ?>

        <script src="../components/navigation/navigation.js"></script>
        <script src="../components/rating/rating.js"></script>
        <script src="../components/comments/comments.js"></script>
    </body>
</html>