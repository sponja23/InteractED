<?php
session_start();

require "../include/connect.php";

$sql = 'SELECT UserCode, Name, Image, Email FROM Users WHERE (Email="' . $_POST["User"] . '" OR User="' . $_POST["User"] . '") AND Password="' . $_POST["Password"] . '"';
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $_SESSION["UserCode"] = $row["UserCode"];
        $_SESSION["Name"] = $row["Name"];
        $_SESSION["Image"] = $row["Image"];
        $_SESSION["Email"] = $row["Email"];

        echo '1';
    }
}
else {
    echo '0';
}

$conn->close();
?>