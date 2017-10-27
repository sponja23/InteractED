<?php
session_start();
require "../include/connect.php";
$Usercode= $_SESSION["UserCode"];
$Level= $_SESSION["Level"];
$Reason=$_POST["Reason"];
$LevelR=$_POST["Level"];
if($LevelR== $Level)
{
	echo "0";
}
else
{
	$sql = 'INSERT INTO requests (UserCode, LevelRequested, Answer, Reason) VALUES ('.$Usercode.','.$LevelR.',"Not Answered","'.$Reason.'")';
	if ($conn->query($sql)){
		$conn->close();
		echo "1";
	}
	else{
		echo "2";
	}
}
?>