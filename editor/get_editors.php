<?php
session_start();

include "../include/connect.php";

$sql = 'SELECT U.UserCode, U.Name, U.Email FROM Articles A
        LEFT JOIN EditorRelation ER ON ER.PostID = A.PostID
        INNER JOIN Users U ON U.UserCode = ER.UserCode
        WHERE MD5(A.PostID) = "' . $_POST["ID"] . '" AND U.UserCode != ' . $_SESSION["UserCode"];
$result = $conn->query($sql);

$Users = '{';

while ($row = $result->fetch_assoc()) {
    $Extension = glob("../images/users/" . $row["UserCode"] . ".*");
    $Extension = pathinfo($Extension[0]);
    $Extension = $Extension['extension'];

    $Users .= '"' . $row['UserCode'] . '":{"Name":"' . $row['Name'] . '","Email":"' . $row['Email'] . '","Extension":"' . $Extension . '"},';
}

$conn->close();

if ($Users != '{')
    echo substr($Users, 0, -1) . '}';
?>