<?php
function postsByRatings() {
	require "include/connect.php";
    include "post/functions.php";

    $sql = "SELECT A.PostID, A.Title, U.Name FROM Ratings R
			INNER JOIN Articles A ON A.PostID = R.PostID
			INNER JOIN Users U ON U.UserCode = A.CreatorID
			GROUP BY R.PostID
			ORDER BY AVG(R.Stars)";
	$result = $conn->query($sql);
	if($result->num_rows > 0) {
		while($row = $result->fetch_assoc()) {
				$Image = glob("../../images/posts/" . $post_row['PostID'] . ".*");
				addHorizontalCard($post_row['PostID'], $Image[0], $post_row['Title'], $post_row['Name']);
			}
		}
	}
}


function postsBySimilarTags() {
	require "include/connect.php";
    include "post/functions.php";
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
			while($post_row = $post_result->fetch_assoc()) {
                $Image = glob("../../images/posts/" . $post_row['PostID'] . ".*");
				addHorizontalCard($post_row['PostID'], $Image[0], $post_row['Title'], $post_row['Name']);
            }
		}
	}
	else {
		echo '<div>
                  <p>No se han encontrado articulos recomendados</p>
              </div>';
	}
}

function postsBySimilarPeople() {
	require "include/connect.php";

	// ESTA CONSULTA TIENE QUE AGARRAR TAGS DE TUS VISITADOS, ENCONTRAR GENTE QUE TIENE ESTOS TAGS EN SUS VISITADOS Y QUE ME DEVUELVA EL RESTO DE SUS TAGS, Y DE ESOS TAGS OBTENER MAS ARTICULOS !!!
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
	echo $articles;
	}
	else {
		 echo '<div>
                  <p>No se han encontrado articulos recomendados</p>
              </div>';
	}
	
}
