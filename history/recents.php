<?php 
function GetRecents() {
    require "../include/connect.php";

    $sql = 'SELECT DISTINCT A.PostID, A.Title, U.Name FROM Articles A
            INNER JOIN Visited V ON A.PostID = V.PostID
            INNER JOIN Users U ON A.CreatorID = U.UserCode
            WHERE V.UserCode = ' . $_SESSION["UserCode"] . 'ORDER BY V.YEAR(Date) DESC, V.MONTH(Date) DESC, V.DAY(DATE) DESC';

    $result = $conn->query($sql);

    if ($result->num_rows > 0)
        while ($row = $result->fetch_assoc()) {
            $Image = glob("../images/posts/" . $row['PostID'] . ".*");

            echo '<div class="col s12">
                      <div class="card horizontal hoverable item" id="' . md5($row['PostID']) . '">
                          <div class="card-image valign-wrapper" style="width: 192px; margin-left: 10px;">
                              <img src="../images/posts/' . basename($Image[0]) . '" style="margin: 0 auto;">
                          </div>
                          <div class="card-stacked">
                              <div class="card-content">
                                  <strong>' . $row['Title'] . '</strong>
                                  <p>' . $row['Name'] . '</p>
                              </div>
                          </div>
                      </div>
                  </div>';
        }
    else
        echo '<p>No se encontraron posts que hayas visitado</p>';
}
?>