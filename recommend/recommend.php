<?php
function postsByRatings() {
    require "include/connect.php";

    $sql = "SELECT A.PostID, A.Title, U.Name FROM Ratings R
            INNER JOIN Articles A ON A.PostID = R.PostID
            INNER JOIN Users U ON U.UserCode = A.CreatorID
            WHERE A.PostID NOT IN(SELECT PostID FROM Visited WHERE UserCode = '" . $_SESSION["UserCode"] . "')
            GROUP BY R.PostID
            ORDER BY AVG(R.Stars)
            LIMIT 4";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        echo '<h5 class="col s12">Destacados</h5>
                  <div class="row" style="margin-bottom: 0;">';

        while($row = $result->fetch_assoc()) {
            $Image = glob("images/posts/" . $row['PostID'] . ".*");
            addVerticalCard($row['PostID'], $Image[0], $row['Title'], $row['Name']);
        }

        echo '</div>';
    }
}

function postsBySimilarTags() {
    require "include/connect.php";

    $sql = "SELECT A.PostID FROM Articles A
            INNER JOIN Visited V ON A.PostID = V.PostID
            WHERE V.UserCode = ". $_SESSION["UserCode"];
    $result = $conn->query($sql);
    if($result->num_rows > 0){
        while($row = $result->fetch_assoc()){
            $sql = "SELECT TagName FROM Tags
                    WHERE PostID = ".$row['PostID'];
            $result2 = $conn->query($sql);
            if($result2->num_rows > 0){
                while($row2 = $result2->fetch_assoc()){
                    $sql = "SELECT A.PostID, A.Title, U.Name FROM Tags T
                            INNER JOIN Articles A ON T.PostID = A.PostID
                            INNER JOIN Users U ON U.UserCode = A.CreatorID
                            WHERE T.TagName = '".$row2['TagName']."'
                            AND A.PostID NOT IN(SELECT PostID FROM Visited WHERE UserCode = '" . $_SESSION["UserCode"] . "')";
                    $result3 = $conn->query($sql);
                    if($result3->num_rows > 0){
						echo '<h5 class="col s12">Recomendaciones</h5>';
                        while($row3 = $result3->fetch_assoc()){
                            addHorizontalCard($row3['PostID'], $Image[0], $row3['Title'], $row3['Name']);
                        }
                    }
                }
            }
        }
    }


    /*$sql = "SELECT DISTINCT A.PostID, A.Title, U.Name FROM Articles A
            INNER JOIN Users U ON U.UserCode = A.CreatorID
            INNER JOIN Tags T ON A.PostID = T.PostID
            WHERE T.TagName = (SELECT T.TagName FROM Tags T
                                INNER JOIN Visited V ON T.PostID = V.PostID
                                WHERE V.UserCode = " . $_SESSION["UserCode"] .")
            AND A.PostID NOT IN(SELECT PostID FROM Visited WHERE UserCode = '" . $_SESSION["UserCode"] . "')";


    $sql = "SELECT A.PostID, A.Title, U.Name FROM Tags T
            INNER JOIN Visited V ON T.PostID = V.PostID
            INNER JOIN Articles A ON V.PostID = A.PostID
            INNER JOIN Users U ON U.UserCode = A.CreatorID
            WHERE DateLastVisited = (SELECT max(DateLastVisited) FROM Visited WHERE V.PostID = Visited.PostID)
            AND V.UserCode = " . $_SESSION["UserCode"] . "" ORDER BY V.DateLastVisited DESC
";





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
    }*/
}

function noResults(){
    echo '<div>
                  <p>No se han encontrado articulos recomendados</p>
              </div>';
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
