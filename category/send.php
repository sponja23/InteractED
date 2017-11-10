<?php
require "../include/connect.php";
$Parent= $_POST["#category-parent"];
$Category= $_POST["#Category"];
$sql = 'INSERT INTO Categories (CategoryName, CategoryParent, Implemented) VALUES ("'. $Category.'", "'. $Parent . '", 0)';
if ($conn->query($sql) === TRUE){
	echo "1";
}
else{
	echo "0";
}

?>