<?php 
function GetCategories() {
    require "../include/connect.php";

    $sql = 'SELECT CategoryName, CategoryID FROM Categories WHERE Implemented = 1';
    
    $result = $conn->query($sql);

    if ($result->num_rows > 0) 
    {
        while ($row = $result->fetch_assoc()) {
            $Image = glob("../images/categories/" . $row['CategoryID'] . ".*");

            echo '<a id="' . $row['CategoryName'] . '" class="categoryOption waves-effect waves-light">
                      <img src="' . $Image[0] . '" class="circle" width="56" height="56">
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