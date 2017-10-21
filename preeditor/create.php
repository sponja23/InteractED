<?php
session_start();
require "../include/connect.php";
$Title = $_POST["Title"];
$Tags = $_POST["Tags"];
$Category = $_POST["Category"];
$Usercode= $_SESSION["UserCode"];
$sql = 'INSERT INTO articles (CreatorID, CreateDate, Title, Tags, Category) VALUES (' . $Usercode . ', CURDATE(), "' . $Title . '", "' . $Tags . '", "' . $Category . '")';

echo $sql;
$conn->query($sql);
$conn->close();
?>