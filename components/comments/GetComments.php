<?php
$File = "../../post/comments/" . $_POST['PostID'] . ".comments";

if (file_exists($File)) {
    $Comments = json_decode(file_get_contents($File), true);

    if ($_POST['DownloadedComments'] != "") {
        $DownloadedComments = explode(';', $_POST['DownloadedComments']);

        for ($i = 0; $i < count($DownloadedComments); $i++)
            unset($Comments[$i]);
    }

    foreach ($Comments as $Key => $Value)
        if ($Key != "LastID")
            $Users[] = $Comments[$Key]["UserCode"];

    if (intval($Comments["LastID"]) > 0 && $Users != null) {
        require "../../include/connect.php";

        $Users = array_values(array_unique($Users));

        $sql = "SELECT UserCode, Name FROM Users WHERE UserCode=" . $Users[0];

        for ($i = 1; $i < count($Users); $i++)
            $sql .= " OR UserCode=" . $Users[$i];

        $result = $conn->query($sql);

        $UserData = '{';

        while ($row = $result->fetch_assoc()) {
            $Extension = glob("../../images/users/" . $row["UserCode"] . ".*");
            $Extension = pathinfo($Extension[0]);
            $Extension = $Extension['extension'];

            $UserData .= '"' . $row['UserCode'] . '":{"Name":"' . $row['Name'] . '","Extension":"' . $Extension . '"},';
        }

        $UserData = json_decode(substr($UserData, 0, -1) . '}', true);

        $Data = array("Comments" => $Comments, "UserData" => $UserData);

        echo json_encode($Data);
    }
}
else {
    if (!file_exists("../../post/comments/"))
        mkdir("../../post/comments", 0777, true);

    file_put_contents($File, '{"LastID":0}');
}
?>