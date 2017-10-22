<?php
session_start();
require "../include/connect.php";
$Usercode= $_SESSION["UserCode"];
$Reason=$_POST["Reason"];
$Level=$_POST["Level"];
$sql = 'INSERT INTO requests (UserCode, LevelRequested, Answer, Reason) VALUES ('.$Usercode.','.$Level.',"Waiting","'.$Reason.'")';
if ($conn->query($sql)){
	$conn->close();
	echo "0";
}
else{
	echo "1";
}
?>