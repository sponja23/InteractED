<?php

require "../include/connect.php";

function getSimilar($word, $maxDistance = 1) {
    
    $words = array();

    for ($i = 0; $i < strlen($word); $i++) {
        if($word[$i] != '_') {
            if($word[$i] != ' ')
                $words[] = substr($word, 0, $i) . '_' . substr($word, $i);
            $words[] = substr($word, 0, $i) . substr($word, $i + 1);
        }
        $words[] = substr($word, 0, $i) . '_' . substr($word, $i + 1);
    }

    $words[] = $word . '_';

    $sql = "SELECT DISTINCT A.PostID FROM Articles A
            INNER JOIN Users U ON A.UserCode = U.UserCode
            INNER JOIN Tags T ON A.PostID = T.PostID
            WHERE A.Title LIKE %" . $words[0] . "% OR U.Name LIKE %" . $words[0] . "% OR A.TagName LIKE %" . $words[0] . "OR A.Transcript LIKE %" . $words[0] . "%";

    for($i = 1; $i < count($words); $i++)
        $sql .= " OR A.Title LIKE %" . $words[0] . "% OR U.Name LIKE %" . $words[0] . "% OR A.TagName LIKE %" . $words[0] . "OR A.Transcript LIKE %" . $words[0] . "%";
        
    $searchResult = $conn->query($sql);

    $result = array();

    if($searchResult->num_rows > 0) {
        while($row = $searchResult->fetch_assoc())
            $result[] = $row["PostID"];
        return $result;
    }

    if($maxDistance > 1) {
        for($i = 0; i < count($words); $i++)
            $result[] = getSimilar($words, $maxDistance - 1);
        return $result;
    }

    return NULL;
}

function searchArticles($query, $maxWords) {
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
        if (str_word_count($query) > $maxWords) {
            $pieces = explode(" ", $query);
            $cutSearch = '<p style="color: #808080; font-size: small;"><b>"' . $pieces[$maxWords] . '"</b> (y las palabras que le siguen) se ignoraron porque limitamos las consultas a ' . $maxWords . ' palabras.</p>';
            $query = implode(" ", array_splice($pieces, 0, $maxWords));
        }


    }
}

?>