<?php
require "../../include/connect.php";
$sql = 'INSERT INTO Categories (CategoryName, Implemented) VALUES ("' . $_POST["Category"] . '", 0)';
$conn->query($sql);
?>