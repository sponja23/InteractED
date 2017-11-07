<?php
require "../include/connect.php";

$sql = 'INSERT INTO Watchlater (PostID , UserCode) VALUES (' .$_POST["id"] . ', ' . $_SESSION['UserCode'] . ')';
if ($conn->query($sql) === TRUE) {
   $conn->close();
?>