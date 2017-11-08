<?php
session_start();

require "../include/connect.php";

$sql = 'SELECT UserCode, User, Name, Email, Level FROM Users WHERE (Email="' . $_POST["User"] . '" OR User="' . $_POST["User"] . '") AND Password="' . $_POST["Password"] . '"';

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $_SESSION["UserCode"] = $row["UserCode"];
        $_SESSION["Name"] = $row["Name"];
        $Image = glob("../images/users/" . $row["UserCode"] . ".*");
        $_SESSION["Image"] = $Image[0];
        $_SESSION["Email"] = $row["Email"];
        $_SESSION["Level"] = $row["Level"];
        echo '1';
    }
}
else
    echo '0';

$conn->close();
?>