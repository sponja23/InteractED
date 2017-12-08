<?php
session_start();

require "../include/connect.php";

$sql = "SELECT CASE WHEN C.SenderUserCode != " . $_SESSION["UserCode"] . " THEN C.SenderUserCode ELSE C.ReceiverUserCode END UserCode, U.Name, Message FROM Chats C
        INNER JOIN Users U ON U.UserCode = CASE WHEN C.SenderUserCode != " . $_SESSION["UserCode"] . " THEN C.SenderUserCode ELSE C.ReceiverUserCode END
        WHERE C.MessageID = (SELECT MAX(MessageID) FROM Chats WHERE (SenderUserCode = " . $_SESSION["UserCode"] . " AND ReceiverUserCode = UserCode) OR (SenderUserCode = UserCode AND ReceiverUserCode = " . $_SESSION["UserCode"] . "))
        ORDER BY C.MessageID DESC";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $Chats = '{';

    $MessageCount = 1;

    while ($row = $result->fetch_assoc()) {
        $Extension = glob("../images/users/" . $row["UserCode"] . ".*");
        $Extension = pathinfo($Extension[0]);
        $Extension = $Extension["extension"];

        $Chats .= '"' . $MessageCount . '":{"UserCode":"' . $row["UserCode"] . '","Name":"' . $row["Name"] . '","Extension":"' . $Extension . '","Message":"' . $row["Message"] . '"},';

        $MessageCount++;
    }

    echo substr($Chats, 0, -1) . '}';
}

$conn->close();
?>