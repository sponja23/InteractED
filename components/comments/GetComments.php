<?php
function RemoveDownloadedComments($sql, $field) {
    if ($_POST["DownloadedComments"] != "") {
        $DownloadedComments = explode(';', $_POST["DownloadedComments"]);

        foreach ($DownloadedComments as $CommentID)
            $sql .= " AND " . $field . " != " . $CommentID;
    }

    return $sql;
}

require "../../include/connect.php";

$sql = 'SELECT CommentID, UserCode, Comment FROM Comments WHERE MD5(PostID) = "' . $_POST["PostID"] . '"';
$sql = RemoveDownloadedComments($sql, "CommentID");

$result = $conn->query($sql);

$Comments = '{';

while ($row = $result->fetch_assoc())
    $Comments .= '"' . $row["CommentID"] . '":{"UserCode":' . $row["UserCode"] . ',"Comment":"' . $row["Comment"] . '"},';

$Comments = substr($Comments, 0, -1) . '}';

$sql = 'SELECT DISTINCT U.UserCode, U.Name FROM Comments C
        INNER JOIN Users U ON U.UserCode = C.UserCode
        WHERE MD5(PostID) = "' . $_POST["PostID"] . '"';
$sql = RemoveDownloadedComments($sql, "C.CommentID");

$result = $conn->query($sql);

$UserData = '{';

while ($row = $result->fetch_assoc()) {
    $Extension = glob("../../images/users/" . $row["UserCode"] . ".*");
    $Extension = pathinfo($Extension[0]);
    $Extension = $Extension["extension"];

    $UserData .= '"' . $row["UserCode"] . '":{"Name":"' . $row["Name"] . '","Extension":"' . $Extension . '"},';
}

$UserData = substr($UserData, 0, -1) . '}';

if ($Comments != '}') {
    $Data = array("Comments" => json_decode($Comments), "UserData" => json_decode($UserData));
    echo json_encode($Data);
}
?>