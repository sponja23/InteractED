<?php
if (!file_exists("../../post/comments/" . $_POST['PostID'] . ".comments")) {
    file_put_contents("../../post/comments/" . $_POST['PostID'] . ".comments", '{"LastID":0}');
    $Comments = json_decode("../../post/comments/" . $_POST['PostID'] . ".comments");
    $Data = array('Comments' => $Comments, 'UserData' => null);
    echo json_encode($Data);
}
else {
    $Comments = json_decode(file_get_contents("../../post/comments/" . $_POST['PostID'] . ".comments"), true);

    if ($_POST['DownloadedComments'] != "") {
        $DownloadedComments = explode(';', $_POST['DownloadedComments']);

        for ($i = 0; $i < count($DownloadedComments); $i++) {
            unset($Comments[$i]);
        }
    }

    foreach ($Comments as $Key => $Value) {
        if ($Key != "LastID") {
            $Users[] = $Comments[$Key]["UserCode"];
        }
    }

    if (intval($Comments["LastID"]) > 0 && $Users != null) {
        require "../../include/connect.php";

        $Users = array_values(array_unique($Users));

        $sql = "SELECT UserCode, Name, Image FROM Users WHERE UserCode=" . $Users[0];

        for ($i = 1; $i < count($Users); $i++) {
            $sql .= " OR UserCode=" . $Users[$i];
        }

        $result = $conn->query($sql);

        $UserData = '{';

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $UserData .= '"' . $row['UserCode'] . '":{"Name":"' . $row['Name'] . '","Image":"' . $row['Image'] . '"},';
            }
        }

        $UserData = json_decode(substr($UserData, 0, -1) . '}', true);

        $Data = array("Comments" => $Comments, "UserData" => $UserData);
    }
    else
        $Data = array("Comments" => $Comments, "UserData" => null);

    echo json_encode($Data);
}
?>