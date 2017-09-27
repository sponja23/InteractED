<?php
session_start();

include "../include/connect.php";

if(!isset($_SESSION["PostCode"])) {
	$sql = "INSERT INTO Articles (CreatorID, Date, Title, Tags, Transcript) VALUES ('" . $_SESSION["UserCode"] . "', CURDATE(), "
}


?>