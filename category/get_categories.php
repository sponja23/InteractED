<?php

require "../include/connect.php";

$sql = "SELECT CategoryName FROM Categories WHERE Implemented = 1";

$result = $conn->query($sql);

while($row = $result->fetch_assoc())
	$categories[] = $row["CategoryName"];

echo json_encode($categories);

?>