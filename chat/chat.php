<?php
session_start();

function RemoveDownloadedMessages($sql, $field) {
    if ($_POST["DownloadedMessages"] != "") {
        $DownloadedMessages = explode(';', $_POST["DownloadedMessages"]);

        foreach ($DownloadedMessages as $MessageID)
            $sql .= " AND " . $field . " != " . $MessageID;
    }

    return $sql;
}

require "../include/connect.php";

if ($_POST["Function"] == "Load") {
    $sql = "SELECT MessageID, Message, (SenderUserCode = " . $_SESSION["UserCode"] . ") Sent FROM Chats
            WHERE ((SenderUserCode = " . $_SESSION["UserCode"] . " AND ReceiverUserCode = " . $_POST["UserCode"] . ")
            OR (SenderUserCode = " . $_POST["UserCode"] . " AND ReceiverUserCode = " . $_SESSION["UserCode"] . "))";
    $sql = RemoveDownloadedMessages($sql, "MessageID");

    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $Messages = '{';

        while ($row = $result->fetch_assoc())
            $Messages .= '"' . $row["MessageID"] . '":{"Message":"' . $row["Message"] . '","Sent":' . $row["Sent"] . '},';

        echo substr($Messages, 0, -1) . '}';
    }
}
else if ($_POST["Function"] == "Upload") {
    $sql = 'INSERT INTO Chats (SenderUserCode, ReceiverUserCode, Message)
            VALUES (' . $_SESSION["UserCode"] . ', ' . $_POST["UserCode"] . ', "' . $_POST["Message"] . '")';

    if ($conn->query($sql) === TRUE)
        echo $conn->insert_id;
}

$conn->close();
?>