<?php
session_start();

include "../include/connect.php";

$sql = "SELECT UserCode FROM Users";
$users = $conn->query($sql);

if($users->num_rows > 0) {
	while($user = $users->fetch_assoc()) {
		$sql = "SELECT A.Title, R.Ratings FROM Users U
				INNER JOIN Ratings R ON U.UserCode = R.UserCode
				INNER JOIN Articles A ON A.PostID = R.PostID
				WHERE U.UserCode = " . $user["UserCode"];
		$user_ratings = $conn->query($sql);
		// echo $user_ratings;
		while($user_rating = $user_ratings->fetch_assoc())
			$ratings[$user["UserCode"]][] = $user_rating;
	}
}



?>