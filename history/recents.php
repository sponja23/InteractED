<?php 
function GetRecents() {
    require "../include/connect.php";
    include "../post/functions.php";

    $sql = 'SELECT DISTINCT A.PostID, A.Title, U.Name FROM Visited V
            INNER JOIN Articles A ON A.PostID = V.PostID
            INNER JOIN Users U ON U.UserCode = A.CreatorID
            WHERE DateLastVisited = (SELECT max(DateLastVisited) FROM Visited WHERE V.PostID = Visited.PostID)
            AND V.UserCode = ' . $_SESSION["UserCode"] . ' ORDER BY V.DateLastVisited DESC';

    $result = $conn->query($sql);

    if ($result->num_rows > 0)
        while ($row = $result->fetch_assoc()) {
            $Image = glob("../images/posts/" . $row['PostID'] . ".*");
            addHorizontalCard($row['PostID'], $Image[0], $row['Title'], $row['Name']);
        }
    else
        echo '<p>No se encontraron posts que hayas visitado</p>';
}
?>