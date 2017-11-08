<?php 
function GetWatchLater() {
    require "../include/connect.php";
    include "../post/functions.php";

    $sql = 'SELECT DISTINCT A.* FROM Articles A
            INNER JOIN WatchLater W ON A.PostID = W.PostID
            WHERE W.UserCode = '. $_SESSION['id'];

    $result = $conn->query($sql);

    if ($result->num_rows > 0)
        while ($row = $result->fetch_assoc()) {
            $Image = glob("../images/posts/" . $row['PostID'] . ".*");

            addHorizontalCard($row['PostID'], $Image[0], $row['Title'], $row['Name']);
        }
    else
        echo '<p>No se encontraron posts que hayas guardado para ver mas tarde</p>';
}
?>