<?php
require "../../include/connect.php";

$fileError = $_FILES["image"]["error"];
$ID= $_POST["id"];  
if($fileError == UPLOAD_ERR_OK) {
    
    $File = "../../images/categories/" . $ID . "." . pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION);

        if (!file_exists("../../images/categories/"))
            mkdir("../../images/categories/", 0777, true);

        move_uploaded_file($_FILES["image"]["tmp_name"], $File);
    //}
}
?>