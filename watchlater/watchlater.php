<?php 
function GetWatchLater() {
    require "../include/connect.php";

    $sql = 'SELECT DISTINCT A.* FROM Articles A
            INNER JOIN WatchLater W ON A.PostID = W.PostID
            WHERE W.UserCode = '. $_SESSION['id'];

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
                          <div class="valign-wrapper">
                            <i id="'.$row['PostID'].'" class="material-icons blue-text watch-later" style="font-size: 36px; margin-right: 24px;">watch_later</i>
                          </div>
                      </div>
                  </div>';
        }
    else
        echo '<p>No se encontraron posts que hayas guardado para ver mas tarde</p>';
}
?>