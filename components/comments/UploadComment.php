<?php
session_start();

require "../../include/connect.php";

$sql = 'INSERT INTO Comments (PostID, UserCode, Comment)
        VALUES ((SELECT PostID FROM Articles WHERE MD5(PostID) = "' . $_POST["PostID"] . '"), ' . $_SESSION["UserCode"] . ', "' . $_POST["Comment"] . '")';

if ($conn->query($sql) === TRUE) {
    $CommentID = $conn->insert_id;

    $sql = "SELECT Name FROM Users WHERE UserCode = " . $_SESSION["UserCode"];
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();

    $Extension = glob("../../images/users/" . $_SESSION["UserCode"] . ".*");
    $Extension = pathinfo($Extension[0]);
    $Extension = $Extension["extension"];

    $Image = $_SESSION["UserCode"] . '.' . $Extension;

    echo '{"CommentID":' . $CommentID . ',"Name":"' . $row["Name"] . '","Image":"' . $Image . '"}';
}
?>