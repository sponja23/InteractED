<?php
$conn = new mysqli("localhost", "root", "root", "InteractED");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>