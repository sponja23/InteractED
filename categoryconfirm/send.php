<?
require "../include/connect.php";
$Category= $_POST['Category'];
$Answer= $_POST['Answer'];
$sql= 'UPDATE categories SET Implemented = "'.$Answer.'" WHERE CategoryID ='.$Category.' AND Implemented= 0' ;
$conn->query($sql);
$conn->close();
?>