<?php
include "../include/connect.php";

$result = $conn->query("SELECT DISTINCT U.User FROM Users U
                        INNER JOIN EditorRelation ER ON U.UserCode = ER.UserCode
                        WHERE MD5(ER.PostID) = '" . $_POST["id"] . "'");

$users = array();

while($row = $result->fetch_assoc()) {
    $users[] = $row["User"];
}

echo json_encode($users);

?>