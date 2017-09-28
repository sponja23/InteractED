<?php
session_start();

include "../include/connect.php";

$folder = "../posts/" . $_GET["id"] . "/";

if(file_exists($folder)) {
	file_put_contents($folder . "content", $_POST["content"]);
}


?>