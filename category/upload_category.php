<?php
require "../include/connect.php";

$Category = $_GET["name"];
$sql = 'INSERT INTO categories (CategoryName, Implemented)
    VALUES ("' . $Category . '", 0)';
if ($conn->query($sql) === TRUE) {

    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $File = "../../images/categories/" . $row['CategoryID'] . "." . pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION);
        }


    }
    if (!file_exists("../images/categories/"))
        mkdir("../images/categories/");
    if (move_uploaded_file($_FILES["image"]["tmp_name"], $File)){
        header('Location: ../InteractED/Category/');
    }

}
?>