<?php
require "../include/connect.php";

$Category = $_GET["name"];
$sql = 'INSERT INTO categories (CategoryName, Implemented)
        VALUES ("' . $Category . '", 0)';
if ($conn->query($sql) === TRUE) {
    $File = "../images/category/" . $conn->insert_id() . "." . pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION);
}

$fileError = $_FILES["image"]["error"];

if(!file_exists("../images/category/"))
    mkdir("../images/category/");

if($fileError == UPLOAD_ERR_OK) {
    //$new_path = "../Images/Category/" . $conn->insert_id() . "." . pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION;
    $new_path = "../Images/Category/" . $_FILES["image"]["name"];
    move_uploaded_file($_FILES["image"]["tmp_name"], $new_path);
}
?>