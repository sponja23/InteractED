<?php
require "../include/connect.php";

$sql = 'SELECT CategoryName FROM Categories WHERE CategoryName ="' . $_POST["category"] . '"';
$result = $conn->query($sql);

if ($result->num_rows > 0)
    echo '0';
else
    echo '1';

$conn->close();
?>