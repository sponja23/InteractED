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
	$sql= 'SELECT UserCode FROM requests WHERE UserCode="'.$Usercode.'" AND Answer= "Not answered"';
	$result = $conn->query($sql);
	if ($result->num_rows > 0) {
		echo "3";
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
}
?>