<?php
session_start();

$fileError = $_FILES["image"]["error"];

if(!file_exists("../post/content/"))
	mkdir("../post/content");
if(!file_exists("../post/content/" . $_GET["id"] . "/")) {
	mkdir("../post/content/" . $_GET["id"]);
	mkdir("../post/content/" . $_GET["id"] . "/images");
}

if($fileError == UPLOAD_ERR_OK) {
	$new_path = "../post/content/" . $_GET["id"] . "/images/" . $_FILES["image"]["name"];
	move_uploaded_file($_FILES["image"]["tmp_name"], $new_path);
}

echo "/InteractED/post/content/" . $_GET["id"] . "/images/" . $_FILES["image"]["name"];

?>