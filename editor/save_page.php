<?php
session_start();

include "../include/connect.php";

$folder = "../posts/" . $_GET["id"] . "/";

file_put_contents($folder . "content.html", $_POST["content"]);
if($_POST["name"] != $_SESSION[$_GET["id"] . "-Title"]) {
	$sql = "UPDATE Articles SET Title = '" . $_POST["name"] ."' WHERE PostID = " . $_GET["id"];
	$conn->query($sql);
}
if($_POST["category"] != $_SESSION[$_GET["id"] . "-Category"]) {
	$sql = "UPDATE Articles SET Category = '" . $_POST["category"] ."' WHERE PostID = " . $_GET["id"];
	$conn->query($sql);
}

$conn->close();

?>