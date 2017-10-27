<?php
session_start();

require "../include/connect.php";

$Title = $_POST["title"];
$Category = $_POST["category"];

$CreateArticle = 'INSERT INTO Articles (CreatorID, CreateDate, Title, Category)
                  VALUES (' . $_SESSION["UserCode"] . ', CURDATE(), "' . $Title . '", "' . $Category . '")';

if ($conn->query($CreateArticle) === TRUE) {
    $PostID = $conn->insert_id;

    $Tags = json_decode($_POST["tags"], true);

    $UploadTags = "INSERT INTO Tags (PostID, TagName) VALUES";

    foreach ($Tags as $Tag)
        $UploadTags .= ' (' . $PostID . ', "' . $Tag . '"),';

    if ($conn->query(substr($UploadTags, 0, -1)) === TRUE)
        header("Location: ../editor?id=" . md5($PostID));
}

$conn->close();
?>