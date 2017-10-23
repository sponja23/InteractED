<?php
require "../include/connect.php";

function getTags() {
	$sql = "SELECT * FROM Users WHERE User='" . $_SESSION["UserCode"] . "'";
	$result = $conn->query($sql);
	while($row = $result->fetch_assoc()) {
		$sql = "SELECT T.TagName FROM Tags T
				INNER JOIN Recents R ON T.PostID = R.PostID
				"
	}
}

?>