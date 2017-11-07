<?php
function addResult($ID, $Image, $Title, $Creator) {
    echo '<div class="col s12">
              <div class="card horizontal hoverable item" id="' . md5($ID) . '">
                  <div class="card-image valign-wrapper" style="width: 192px; margin-left: 10px;">
                      <img src="../../images/posts/' . $Image . '" style="margin: 0 auto;">
                  </div>
                  <div class="card-stacked">
                      <div class="card-content">
                          <strong>' . $Title . '</strong>
                          <p>' . $Creator . '</p>
                      </div>
                      <div class="valign-wrapper">
                            <i id="'.$row['PostID'].'" class="material-icons blue-text watch-later" style="font-size: 36px; margin-right: 24px;">watch_later</i>
                          </div>
                  </div>
              </div>
          </div>';
}

function GetUserPosts() {
    require "../../include/connect.php";

    $sql = 'SELECT A.PostID, A.Title, U.Name FROM Articles A
            INNER JOIN Users U ON A.CreatorID = U.UserCode
            WHERE CreatorID = '. $_SESSION['UserCode'] .'';

    $result = $conn->query($sql);

    if ($result->num_rows > 0) 
        while ($row = $result->fetch_assoc()) {
            $Image = glob("../../images/posts/" . $row['PostID'] . ".*");

            addResult($row['PostID'], basename($Image[0]), $row['Title'], $row['Name']);
        }
    else
        echo '<p>No se encontraron posts</p>';
}
?>