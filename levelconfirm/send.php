<?
require "../include/connect.php";
$User= $_POST['UC'];
$Answer= $_POST['Answer'];
$Level= $_POST['Level'];
$sql= 'UPDATE requests SET Answer = "'.$Answer.'" WHERE UserCode='.$User.'' ;
$conn->query($sql);
$sql= 'UPDATE users SET Level = "'.$Level.'" WHERE UserCode='.$User.'' ;
$conn->query($sql);
?>