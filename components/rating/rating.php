<?php
session_start();

require "../../include/connect.php";

$sql = 'SELECT Stars FROM Ratings WHERE PostID = "' . $_POST["ID"] . '" AND UserCode = ' . $_SESSION["UserCode"];

$result = $conn->query($sql);

if ($_POST["Action"] == "Upload") {
    $Rating = $_POST["Rating"];

    if ($result->num_rows > 0)
        $sql = 'UPDATE Ratings SET Stars=' . $Rating . ' WHERE PostID = "' . $_POST["ID"] . '" AND UserCode = ' . $_SESSION["UserCode"];
    else
        $sql = 'INSERT INTO Ratings (PostID, UserCode, Stars) VALUES ("' . $_POST["ID"] . '", ' . $_SESSION["UserCode"] . ', ' . $Rating . ')';

    $conn->query($sql);
}
else if ($_POST["Action"] == "Get")
    while ($row = $result->fetch_assoc())
        echo $row["Stars"];

$conn->close();
?>