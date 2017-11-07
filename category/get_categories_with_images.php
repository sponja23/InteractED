<?php
require "../include/connect.php";

$sql = "SELECT CategoryName, CategoryID FROM Categories WHERE Implemented = 1";
$result = $conn->query($sql);

while ($row = $result->fetch_assoc()) {
	$Image = glob("../image/categories/" . $row["CategoryID"] . ".*");
	$categories[$row["CategoryName"]] = "/InteractED/images/categories/" . basename($Image[0]);
}

echo json_encode($categories);
?>