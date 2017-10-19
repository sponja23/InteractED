<?php
session_start();

include "../include/connect.php";

$sql = "SELECT * FROM Articles WHERE PostID = " . $_GET["id"];
$result = $conn->query($sql);

if ($result->num_rows > 0)
    while ($row = $result->fetch_assoc()) {
        $Title = $row["Title"];
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

        <link rel="stylesheet" href="../components/navigation/navigation.css">
        <link rel="stylesheet" href="../components/rating/rating.css">
        <link rel="stylesheet" href="../components/comments/comments.css">
    </head>
    <body class="grey lighten-5">
        <?php require "../components/navigation/navigation.php"; ?>

        <div class="container">
            <?= file_get_contents("content/" . $_GET["id"] . "/index.html") ?>
            <?php require "../components/rating/index.html"; ?>
            <?php require "../components/comments/index.php"; ?>
        </div>

        <?php require "../include/scripts.html"; ?>

        <script src="../components/navigation/navigation.js"></script>

        <script src="../components/comments/comments.js"></script>
    </body>
</html>