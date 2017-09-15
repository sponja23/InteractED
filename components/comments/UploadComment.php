<?php
if (file_exists("../../posts/" . $_POST['PostID'] . "/" . $_POST['PostID'] . ".comments")) {
    $Comments = json_decode(file_get_contents("../../posts/" . $_POST['PostID'] . "/" . $_POST['PostID'] . ".comments"), true);

    $Comments["LastID"] = intval($Comments["LastID"]) + 1;

    $Comments[$Comments["LastID"]]["UserCode"] = $_POST['UserCode'];
    $Comments[$Comments["LastID"]]["Comment"] = $_POST['Comment'];

    file_put_contents("../../posts/" . $_POST['PostID'] . "/" . $_POST['PostID'] . ".comments", json_encode($Comments));

    $conn = new mysqli("localhost", "root", "root", "InteractED");

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $sql = "SELECT Name, Image FROM Users WHERE UserCode=" . $_POST['UserCode'];
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo '{"Name":"' . $row['Name'] . '","Image":"' . $row['Image'] . '","CommentID":"' . $Comments["LastID"] . '"}';
        }
    }
}
else {
    file_put_contents("../../posts/" . $_POST['PostID'] . "/" . $_POST['PostID'] . ".comments", '{"LastID":0}');
}
?>