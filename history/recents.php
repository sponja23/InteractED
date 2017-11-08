<?php 
function GetRecents() {
    require "../include/connect.php";
    include "../post/functions.php";

    $sql = 'SELECT DISTINCT A.PostID, A.Title, U.Name FROM Articles A
            INNER JOIN Visited V ON A.PostID = V.PostID
            INNER JOIN Users U ON A.CreatorID = U.UserCode
            WHERE V.UserCode = ' . $_SESSION["UserCode"] . 'ORDER BY V.YEAR(Date) DESC, V.MONTH(Date) DESC, V.DAY(DATE) DESC';

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