<?php
session_start();

if ($_POST['UserCode'] < $_SESSION['UserCode'])
    $File = "chats/" . $_POST['UserCode'] . '-' . $_SESSION['UserCode'] . ".chat";
else
    $File = "chats/" . $_SESSION['UserCode'] . '-' . $_POST['UserCode'] . ".chat";

if (file_exists($File)) {
    if ($_POST['Function'] == "Load") {
        if ($_POST['DownloadedMessages'] != "") {
            $Chat = json_decode(file_get_contents($File), true);

            $DownloadedMessages = explode(';', $_POST['DownloadedMessages']);

            for ($i = 0; $i < count($DownloadedMessages); $i++) {
                unset($Chat[$i]);
            }

            echo json_encode($Chat);
        }
        else
            echo file_get_contents($File);
    }
    else {
        $Chat = json_decode(file_get_contents($File), true);

        $Chat["LastID"] = intval($Chat["LastID"]) + 1;
        $LastID = $Chat["LastID"];

        $Chat[$LastID]["UserCode"] = $_SESSION['UserCode'];
        $Chat[$LastID]["Message"] = $_POST['Message'];

        file_put_contents($File, json_encode($Chat));

        echo $LastID;
    }
}
else {
    mkdir("chats");
    file_put_contents($File, '{"LastID":0}');
}
?>