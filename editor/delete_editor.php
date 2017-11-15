<?php
include "../include/connect.php";

$sql = 'DELETE FROM EditorRelation WHERE MD5(PostID) = "' . $_POST["PostID"] . '" AND UserCode = ' . $_POST["UserCode"];

if ($conn->query($sql) === TRUE)
    echo '1';

$conn->close();
?>