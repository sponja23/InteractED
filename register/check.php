<?php
require "../include/connect.php";

$sql = 'SELECT User, Email FROM Users WHERE Email="' . $_POST["Email"] . '" OR User="' . $_POST["User"] . '"';
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        if ($row["User"] == $_POST["User"])
            echo '1';
        else if ($row["Email"] == $_POST["Email"])
            echo '2';
    }
}
else
    echo '0';

$conn->close();
?>