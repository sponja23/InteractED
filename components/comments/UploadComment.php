<?php
session_start();

if (file_exists("../../post/comments/" . $_POST['PostID'] . ".comments")) {
    $Comments = json_decode(file_get_contents("../../post/comments/" . $_POST['PostID'] . ".comments"), true);

    $Comments["LastID"] = intval($Comments["LastID"]) + 1;

    $Comments[$Comments["LastID"]]["UserCode"] = $_SESSION['UserCode'];
    $Comments[$Comments["LastID"]]["Comment"] = $_POST['Comment'];

    file_put_contents("../../post/comments/" . $_POST['PostID'] . ".comments", json_encode($Comments));

    require "../../include/connect.php";

    $sql = "SELECT Name, Image FROM Users WHERE UserCode=" . $_SESSION['UserCode'];
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo '{"Name":"' . $row['Name'] . '","Image":"' . $row['Image'] . '","CommentID":"' . $Comments["LastID"] . '"}';
        }
    }
}
else {
    file_put_contents("../../post/comments/" . $_POST['PostID'] . ".comments", '{"LastID":0}');
}
?>