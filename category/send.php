<?php
require "../include/connect.php";
$sql = 'INSERT INTO Categories (CategoryName, Implemented) VALUES ("' . $_POST["Category"] . '", 0)';
if ($conn->query($sql)){
	echo "1";
}
else{
	echo "0";
}

?>