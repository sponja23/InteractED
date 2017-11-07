<?php
require "../../include/connect.php";

$fileError = $_FILES["image"]["error"];

if($fileError == UPLOAD_ERR_OK) {
    //$sql = 'INSERT INTO Categories (CategoryName, Implemented) VALUES ("' . $_GET["name"] . '", 0)'; //WTF

    //if ($conn->query($sql) === TRUE) {
        $File = "../../images/categories/" . $conn->insert_id /* ACA VA EN REALIDAD EL ID QUE APARECE EN LA URL*/ . "." . pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION);

        if (!file_exists("../../images/categories/"))
            mkdir("../../images/categories/", 0777, true);

        move_uploaded_file($_FILES["image"]["tmp_name"], $File);
    //}
}
?>