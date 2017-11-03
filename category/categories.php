<?php 
class Categories{
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
	function GetCategories(){
		require "../include/connect.php";

		$sql = 'SELECT CategoryName,CategoryID FROM Categories WHERE Implemented = 1';
        
        $result = $conn->query($sql);

        if ($result->num_rows > 0) 
        {
            while ($row = $result->fetch_assoc()){
               echo '<a class="categoryOption waves-effect" id="'.$row['CategoryName'].'"><img src="../images/category/'.$row['CategoryID'].'.png" alt="'.$row['CategoryName'].'" height="56" width="56"></a>';
            }
        }
	}	
	function GetArticlesByCategories($category){
		require "../../include/connect.php";
		$sql = 'SELECT DISTINCT *
				FROM articles
				INNER JOIN categories ON articles.CategoryID = categories.CategoryID WHERE categories.CategoryName = "' .$category. '";';

		$result = $conn->query($sql);

		if ($result->num_rows > 0) 
		{
		    while ($row = $result->fetch_assoc())
		       $this->addResult($row['PostID'], md5($row['PostID']), $row['Title'], $row['Name']);
		}
	}
}
?>