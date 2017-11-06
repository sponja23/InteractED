<?php
require "../include/connect.php";

$sql = "SELECT CategoryName, CategoryID FROM Categories WHERE Implemented = 1";
$result = $conn->query($sql);

$categories = '{';

while ($row = $result->fetch_assoc())
	$categories .= '"' . $row["CategoryID"] . '":"' . $row["CategoryName"] . '",';

echo substr($categories, 0, -1) . '}';
?>