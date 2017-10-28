<?php

require "../include/connect.php";

function Search($query, $maxWords) {
    $sql = "SELECT DISTINCT A.PostID FROM Articles A
            INNER JOIN Users U ON A.UserCode = U.UserCode
            INNER JOIN Tags T ON A.PostID = T.PostID
            WHERE MATCH(A.Title, A.Transcript, U.Name, T.TagName) AGAINST ('" . $query . "' IN BOOLEAN MODE)";

    $searchResult = $conn->query($sql);

    if($searchResult->num_rows > 0) {
        echo '<p class="results">' . $result->num_rows . ' resultados para "' . $query . '"</p>';

        while($row = $searchResult->fetch_assoc())
            echo '<div class="col s12">
                          <div class="card horizontal hoverable item" id="' . $row['PostID'] . '">
                              <div class="card-image" style="width: 192px;">
                                  <img src="' . $row['Image'] . '">
                              </div>
                              <div class="card-stacked">
                                  <div class="card-content">
                                      <strong>' .$row['Title'] . '</strong>
                                      <p>' . $row['Creator'] . '</p>
                                  </div>
                              </div>
                          </div>
                      </div>';
    }
    else {
        if (str_word_count($Search) > $maxWords) {
            $Pieces = explode(" ", $query);
            $CutSearch = '<p style="color: #808080; font-size: small;"><b>"' . $Pieces[$maxWords] . '"</b> (y las palabras que le siguen) se ignoraron porque limitamos las consultas a ' . $maxWords . ' palabras.</p>';
            $query = implode(" ", array_splice($Pieces, 0, $maxWords));
        }


    }
}

?>