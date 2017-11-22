<?php
require "../include/connect.php";

$fileError = $_FILES["image"]["error"];

$categoryName =  $_GET["name"];

if ($_GET["parent"] == "")
	$categoryParent = "root";
else
	$categoryParent = $_GET["parent"];


if($fileError == UPLOAD_ERR_OK) {
    $sql = 'INSERT INTO Categories (CategoryName, CategoryParent, Implemented) VALUES ("' . $categoryName . '", "' . $categoryParent . '", 0)';

    if ($conn->query($sql) === TRUE) {
        $File = "../images/categories/" . $conn->insert_id . "." . pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION);

        if (!file_exists("../images/categories/"))
            mkdir("../images/categories/", 0777, true);

        move_uploaded_file($_FILES["image"]["tmp_name"], $File);
    }
}
?>