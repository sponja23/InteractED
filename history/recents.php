<?php 
class History{
	function GetRecents(){
		require "../include/connect.php";

		$sql = 'SELECT DISTINCT *
                FROM Articles
                INNER JOIN Visited ON Articles.PostID = Visited.PostID WHERE Visited.UserCode = '. session_id() .';';

        $result = $conn->query($sql);

        if ($result->num_rows > 0) 
        {
            while ($row = $result->fetch_assoc()){
                echo '<div class="col s12">
                          <div class="card horizontal hoverable item" id="' . $row['PostID'] . '">
                              <div class="card-image" style="width: 192px;">
                                  <img src="../post/content/' . md5($row['PostID']) . '/image.jpg">
                              </div>
                              <div class="card-stacked">
                                  <div class="card-content">
                                      <strong>' .$row['Title'] . '</strong>
                                      <p>' . $row['Name'] . '</p>
                                  </div>
                              </div>
                          </div>
                      </div>';
            }

        }
	}
}
?>	