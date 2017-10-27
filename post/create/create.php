<?php
session_start();

require "../../include/connect.php";

$Title = $_POST["title"];
$Category = $_POST["category"];

$CreateArticle = 'INSERT INTO Articles (CreatorID, CreateDate, Title, Category)
                  VALUES (' . $_SESSION["UserCode"] . ', CURDATE(), "' . $Title . '", "' . $Category . '")';

if ($conn->query($CreateArticle) === TRUE) {
    $PostID = $conn->insert_id;

    $UploadTags = "INSERT INTO Tags (PostID, TagName) VALUES";

    foreach ($_POST["tags"] as $Tag)
        $UploadTags .= ' (' . $PostID . ', "' . $Tag . '"),';

    $UploadTags = substr($UploadTags, 0, -1);

    if ($conn->query($UploadTags) === TRUE)
        header("Location: ../../editor?id=" . md5($PostID));
}

$conn->close();
?>