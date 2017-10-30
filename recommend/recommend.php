<?php
require "../include/connect.php";

function postsBySimilarTags() {
	$sql = "SELECT T.TagName FROM Tags T
			INNER JOIN Visited V ON T.PostID = V.PostID
			WHERE V.UserCode = " . $_SESSION["UserCode"] . " ORDER BY V.DateLastVisited DESC LIMIT 10";
	$tag_result = $conn->query($sql);
	if($tag_result->num_rows > 0) {
		while($tag_row = $result->fetch_assoc()) {
			$sql = "SELECT A.* FROM Tags T
					INNER JOIN Articles A ON T.PostID = A.PostID
					WHERE T.TagName = '" . $tag_row["TagName"] . "' AND A.PostID NOT IN(SELECT PostID FROM Visited WHERE UserCode = '" . $_SESSION["UserCode"] . "')";
			$post_result = $conn->query($sql);
			while($post_row = $post_result->fetch_assoc())
				//$recommendedPostIDs[] = $post_row["PostID"];
				echo '<div class="col s12">
                          <div class="card horizontal hoverable item" id="' . $post_row['PostID'] . '">
                              <div class="card-image" style="width: 192px;">
                                  <img src="../post/content/' . md5($post_row['PostID']) . '/image.jpg">
                              </div>
                              <div class="card-stacked">
                                  <div class="card-content">
                                      <strong>' .$post_row['Title'] . '</strong>
                                      <p>' . $post_row['Name'] . '</p>
                                  </div>
                              </div>
                          </div>
                      </div>';
		}
	}
	else {
		// El usuario no visito ningun post
	}
}

function postsBySimilarPeople() {
	$sql = "SELECT T.TagName FROM Tags T
			INNER JOIN Visited V ON T.PostID = V.PostID
			WHERE V.UserCode = " . $_SESSION["UserCode"] . " ORDER BY V.DateLastVisited DESC LIMIT 10";
	$tag_result = $conn->query($sql);
	if($tag_result->num_rows > 0) {
		while ($row = $tag_result->fetch_assoc()){
                        $articles .= '<div class="col s12 m3">
                            <div class="card hoverable item" id="'.$row['PostID'].'">
                                <div class="card-image">
                                    <img src="https://i.ytimg.com/vi/2OgLKz9yQ0Q/hqdefault.jpg?custom=true&w=246&h=138&stc=true&jpg444=true&jpgq=90&sp=68&sigh=IE1JFDEOZl_4r872Wlo5ydYUKjc">
                                </div>
                                <div class="card-content">
                                    <strong>'.$row['Title'].'</strong>
                                    <p>ACA VA EL CREADOR</p>
                                </div>
                            </div>
                        </div>
                        '; 
	}
	else {
		// El usuario no visito ningun post
	}
}
