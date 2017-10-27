<?php
require "../../include/connect.php";

$Category = $_POST["Category"];
$sql = 'INSERT INTO categories (Category, Status)
    VALUES ("' . $Category . '","2")';
if ($conn->query($sql) === TRUE) {
    $sql = 'SELECT CategoryID FROM categories WHERE CategoryName ="' . $Category . '"';
    $result = $conn->query($sql);

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