<?php
session_start();

$conn = new mysqli("localhost", "root", "root", "Usuarios");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = 'SELECT Name, Image, Email FROM Usuarios WHERE Email="' . $_POST["Email"] . '" AND Password="' . $_POST["Password"] . '"';
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
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