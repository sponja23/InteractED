<?php
if (!file_exists("../post/content/" . $_GET["id"] . "/images/"))
    mkdir("../post/content/" . $_GET["id"] . "/images/", 0777, true);

if ($_FILES["image"]["error"] == UPLOAD_ERR_OK) {
	$new_path = "../post/content/" . $_GET["id"] . "/images/" . $_FILES["image"]["name"];
	move_uploaded_file($_FILES["image"]["tmp_name"], $new_path);
    echo $new_path;
}
?>