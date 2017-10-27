<?php
require "../include/connect.php";

$sql = 'SELECT CategoryName FROM categories WHERE CategoryName ="' . $_POST["category"] . '"';
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        echo '1';
    }
}
else{
    echo '0';
}

$conn->close();
?>