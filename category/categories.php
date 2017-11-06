<?php 
function addResult($ID, $Extension, $Title, $Creator) {
    echo '<div class="col s12">
              <div class="card horizontal hoverable item" id="' . md5($ID) . '">
                  <div class="card-image valign-wrapper" style="width: 192px;">
                      <img src="../../images/posts/' . $ID . '.' . $Extension . '" style="display: block; margin: 0 auto;">
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

function GetCategories() {
    require "../include/connect.php";

    $sql = 'SELECT CategoryName, CategoryID FROM Categories WHERE Implemented = 1';
    
    $result = $conn->query($sql);

    if ($result->num_rows > 0) 
    {
        while ($row = $result->fetch_assoc()) {
            $Extension = glob("../images/categories/" . $row['CategoryID'] . ".*");
            $Extension = pathinfo($Extension[0]);
            $Extension = $Extension['extension'];

            echo '<a class="categoryOption waves-effect" id="' . $row['CategoryName'] . '">
                      <img src="../images/categories/' . $row['CategoryID'] . '.' . $Extension . '" class="circle" width="56" height="56">
                  </a>';
        }
    }
}

function GetArticlesByCategory($category) {
    require "../../include/connect.php";

    $sql = 'SELECT DISTINCT * FROM Articles A
            INNER JOIN Categories C ON A.CategoryID = C.CategoryID
            WHERE C.CategoryName = "' . $category . '"';

    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $Extension = glob("../../images/posts/" . $row['PostID'] . ".*");
            $Extension = pathinfo($Extension[0]);
            $Extension = $Extension['extension'];

            addResult($row['PostID'], $Extension, $row['Title'], $row['Name']);
        }
    }
}
?>