<?php
require "../include/connect.php";

$sql = "SELECT CategoryName, CategoryID FROM Categories WHERE Implemented = 1";
$result = $conn->query($sql);

while ($row = $result->fetch_assoc()) {
	$Image = glob("../images/categories/" . $row["CategoryID"] . ".*");
	$categories[$row["CategoryName"]] = "../" . $Image[0];
}

echo json_encode($categories);
?>