<?php
session_start();

$File = "../../post/comments/" . $_POST['PostID'] . ".comments";

if (file_exists($File)) {
    $Comments = json_decode(file_get_contents($File), true);

    $Comments["LastID"] = intval($Comments["LastID"]) + 1;
    $LastID = $Comments["LastID"];

    $Comments[$LastID]["UserCode"] = $_SESSION['UserCode'];
    $Comments[$LastID]["Comment"] = $_POST['Comment'];

    file_put_contents($File, json_encode($Comments));

    require "../../include/connect.php";

    $sql = "SELECT Name FROM Users WHERE UserCode=" . $_SESSION['UserCode'];
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $Image = glob("../../images/users/" . $_SESSION['UserCode'] . ".*");
            $Image = basename($Image[0]);

            echo '{"CommentID":"' . $LastID . '","Name":"' . $row['Name'] . '","Image":"' . $Image . '"}';
        }
    }
}
else {
    if (!file_exists("../../post/comments/"))
        mkdir("../../post/comments");

    file_put_contents($File, '{"LastID":0}');
}
?>