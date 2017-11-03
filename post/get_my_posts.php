<?php
function addResult($ID, $Image, $Title, $Creator) {
    echo '<div class="col s12">
              <div class="card horizontal hoverable item" id="' . $ID . '">
                  <div class="card-image" style="width: 192px;">
                      <img src="../post/content/' . $Image . '/image.jpg">
                  </div>
                  <div class="card-stacked">
                      <div class="card-content">
                          <strong>' . $Title . '</strong>
                          <p>' . $Creator . '</p>
                      </div>
                  </div>
              </div>
          </div>';
}

function GetMyPosts(){
    require "../include/connect.php";

    $sql = 'SELECT * FROM Articles WHERE CreatorID = '. $_SESSION['UserCode'] .'';
        
        $result = $conn->query($sql);

        if ($result->num_rows > 0) 
        {
            while ($row = $result->fetch_assoc()){
               addResult($row['PostID'], md5($row['PostID']), $row['Title'], $row['Name']);
            }
        }
  } 
?>