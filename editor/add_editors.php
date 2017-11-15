<?php
include "../include/connect.php";

$sql = 'SELECT PostID FROM Articles WHERE MD5(PostID) = "' . $_POST["ID"] . '"';
$result = $conn->query($sql);

$tmp = $result->fetch_assoc();
$PostID = $tmp["PostID"];

foreach ($_POST["Users"] as $User) {
    $sql = 'SELECT UserCode FROM Users
            WHERE User = "' . $User . '" OR Email = "' . $User . '"';
    $result = $conn->query($sql);

    $Coincidences = $result->fetch_assoc();
    $UserCode = $Coincidences["UserCode"];

    if ($UserCode > 0)
        $UserCodes[] = $UserCode;
}

if (count($UserCodes) > 0) {
    $sql = 'SELECT UserCode FROM EditorRelation WHERE MD5(PostID) = "' . $_POST["ID"] . '"';
    $Shared = $conn->query($sql);

    while ($row = $Shared->fetch_assoc())
        $SharedUserCodes[] = $row["UserCode"];

    if (count($SharedUserCodes) > 0) {
        foreach ($UserCodes as $UserCode) {
            if (in_array($UserCode, $SharedUserCodes)) {
                $Key = array_search($UserCode, $UserCodes);
                unset($UserCodes[$Key]);
            }
        }

        $UserCodes = array_values($UserCodes);
    }

    if (count($UserCodes) > 0) {
        $sql = 'INSERT INTO EditorRelation (PostID, UserCode) VALUES';

        foreach ($UserCodes as $UserCode) {
            $sql .= ' (' . $PostID . ', ' . $UserCode . '),';
        }

        if ($conn->query(substr($sql, 0, -1)) === TRUE)
            echo '1';
    }
}

$conn->close();
?>