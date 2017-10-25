<?php
session_start();

require "../include/connect.php";

foreach ($_POST as $Key => $Value) {
    $Fields .= $Key . '="' . $Value . '",';
    $_SESSION[$Key] = $Value;
}

$sql = "UPDATE Users SET " . substr($Fields, 0, -1) . " WHERE UserCode=" . $_SESSION["UserCode"];

if ($conn->query($sql) === TRUE)
    echo '1';
else
    echo '0';
?>