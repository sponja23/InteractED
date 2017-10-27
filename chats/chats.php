<?php
session_start();

$File = "users/" . $_SESSION['UserCode'] . ".chats";

if (file_exists($File)) {
    $Chats = json_decode(file_get_contents($File), true);

    require "../include/connect.php";

    foreach ($Chats["Chats"] as $Value)
        $Users[] = $Value;

    $sql = "SELECT UserCode, Name FROM Users WHERE UserCode=" . $Users[0];

    for ($i = 1; $i < count($Users); $i++)
        $sql .= " OR UserCode=" . $Users[$i];

    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $UserData = '{';

        while ($row = $result->fetch_assoc()) {
            if ($row['UserCode'] < $_SESSION['UserCode'])
                $ChatFile = "../chat/chats/" . $row['UserCode'] . '-' . $_SESSION['UserCode'] . ".chat";
            else
                $ChatFile = "../chat/chats/" . $_SESSION['UserCode'] . '-' . $row['UserCode'] . ".chat";

            $SpecificChat = json_decode(file_get_contents($ChatFile), true);
            $Message = $SpecificChat[$SpecificChat["LastID"]]["Message"];

            $Extension = pathinfo(glob("../images/" . $row['UserCode'] . ".*")[0])['extension'];
            $UserData .= '"' . $row['UserCode'] . '":{"Name":"' . $row['Name'] . '","Extension":"' . $Extension . '","Message":"' . $Message . '"},';
        }

        echo substr($UserData, 0, -1) . '}';
    }
}
else {
    mkdir("users");
    file_put_contents($File, '{"Chats":[]}');
}
?>