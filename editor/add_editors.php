<?php
session_start();

include "../include/connect.php";

$result = $conn->query("SELECT PostID FROM Articles WHERE MD5(PostID) = '" . $_GET["id"] . "'");
$tmp = $result->fetch_assoc();
$PostID = $tmp["PostID"];

$UserCodes = array();

foreach($_POST["users"] as $user) {
	$result = $conn->query("SELECT UserCode FROM Users WHERE User = '" . $user . "' OR Name = '" . $user . "' OR Email = '" . $user . "'");
	if($result->num_rows > 0) {
		while($row = $result->fetch_assoc()) {
			$UserCodes[] = $row["UserCode"];
		}
	}
	else {
		echo "User " . $user . " not found";
	}
}

foreach($UserCodes as $UserCode) {
	$sql = "INSERT INTO EditorRelation (PostID, UserCode)
			SELECT * FROM (SELECT " . $PostID . ", '" . $UserCode . "'') AS tmp
			WHERE NOT EXISTS (SELECT PostID, UserCode FROM EditorRelation WHERE PostID = " . $PostID . " AND UserCode = " . $UserCode . ") LIMIT 1";

	$conn->query($sql);
}


?>