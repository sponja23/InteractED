<?php
function GetUserPosts() {
    require "../../include/connect.php";
    include "../../post/functions.php";

    $sql = 'SELECT A.PostID, A.Title, U.Name FROM Articles A
            INNER JOIN Users U ON A.CreatorID = U.UserCode
            WHERE CreatorID = '. $_SESSION['UserCode'] .'';

    $result = $conn->query($sql);

    if ($result->num_rows > 0) 
        while ($row = $result->fetch_assoc()) {
            $Image = glob("../../images/posts/" . $row['PostID'] . ".*");

            addHorizontalCard($row['PostID'], $Image[0], $row['Title'], $row['Name']);
        }
    else
        echo '<p>No se encontraron posts</p>';
}
?>