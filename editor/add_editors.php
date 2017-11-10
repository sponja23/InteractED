<?php

include "../include/connect.php";

$result = $conn->query("SELECT PostID FROM Articles WHERE MD5(PostID) = '" . $_POST["id"] . "'");
$tmp = $result->fetch_assoc();
$PostID = $tmp["PostID"];

$UserCodes = array();

$num_users = count($_POST["users"]);

for($i = 0; $i < $num_users; $i++) {
    $user = $_POST["users"][$i];
    $result = $conn->query("SELECT UserCode FROM Users WHERE User = '" . $user . "' OR Name = '" . $user . "' OR Email = '" . $user . "'");
    if($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $UserCodes[] = $row["UserCode"];
        }
    }
    else {
        echo "User " . $user . " not found";
    }
}

foreach($UserCodes as $UserCode) {
    $sql = "INSERT INTO EditorRelation (PostID, UserCode)
            SELECT * FROM (SELECT " . $PostID . ", '" . $UserCode . "') AS tmp
            WHERE NOT EXISTS (SELECT PostID, UserCode FROM EditorRelation WHERE PostID = " . $PostID . " AND UserCode = " . $UserCode . ") LIMIT 1";

    echo $sql;

    $conn->query($sql);
}


?>