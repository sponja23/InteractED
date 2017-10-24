<?php
require "../include/connect.php";

function postsBySimilarTags() {
	$sql = "SELECT T.TagName FROM Tags T
			INNER JOIN Visited V ON T.PostID = V.PostID
			WHERE V.UserCode = " . $_SESSION["UserCode"] . " ORDER BY V.DateLastVisited DESC LIMIT 10";
	$tag_result = $conn->query($sql);
	if($tag_result->num_rows > 0) {
		while($tag_row = $result->fetch_assoc()) {
			$sql = "SELECT A.PostID FROM Tags T
					INNER JOIN Articles A ON T.PostID = A.PostID
					WHERE T.TagName = '" . $tag_row["TagName"] . "' AND A.PostID NOT IN(SELECT PostID FROM Visited WHERE UserCode = '" . $_SESSION["UserCode"] . "')";
			$post_result = $conn->query($sql);
			while($post_row = $post_result->fetch_assoc())
				$recommendedPostIDs[] = $post_row["PostID"];
		}
	}
	else {
		// El usuario no visito ningun post
	}
}

function postsBySimilarPeople() {

}

?>