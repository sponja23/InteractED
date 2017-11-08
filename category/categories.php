<?php 
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
    include "../../post/functions.php";

    $sql = 'SELECT DISTINCT * FROM Articles A
            INNER JOIN Categories C ON A.CategoryID = C.CategoryID
            INNER JOIN Users U ON A.CreatorID = U.UserCode
            WHERE C.CategoryName = "' . $category . '"';

    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $Image = glob("../../images/posts/" . $row['PostID'] . ".*");

            addHorizontalCard($row['PostID'], $Image[0], $row['Title'], $row['Name']);
        }
    }
}
?>