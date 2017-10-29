<?php

require "../include/connect.php";

function getSimilar($word, $maxDistance = 1) {
    
    $words = array();

    $length = strlen($word);

    for ($i = 0; $i < $length; $i++) {
        $first_part = substr($word, 0, $i);
        $last_part = substr($word, $i + 1);
        if($word[$i] != '_') {
            if($word[$i] != ' ')
                $words[] = $first_part . '_' . $word[$i] . $last_part;
            $words[] = $first_part . $last_part;
        }
        $words[] = $first_part '_' . $last_part;
    }

    $words[] = $word . '_';

    $sql = "SELECT DISTINCT A.PostID, A.Title, U.Name FROM Articles A
            INNER JOIN Users U ON A.UserCode = U.UserCode
            INNER JOIN Tags T ON A.PostID = T.PostID
            WHERE A.Title LIKE %" . $words[0] . "% OR U.Name LIKE %" . $words[0] . "% OR A.TagName LIKE %" . $words[0] . "OR A.Transcript LIKE %" . $words[0] . "%";

    for($i = 1; $i < count($words); $i++)
        $sql .= " OR A.Title LIKE %" . $words[$i] . "% OR U.Name LIKE %" . $words[$i] . "% OR A.TagName LIKE %" . $words[$i] . "OR A.Transcript LIKE %" . $words[$i] . "%";
        
    $searchResult = $conn->query($sql);

    $result = array();

    if($searchResult->num_rows > 0) {
        while($row = $searchResult->fetch_assoc())
            $result[] = $row;
        return $result;
    }

    if($maxDistance > 1) {
        $count = count($words);
        for($i = 0; $i < $count; $i++)
            $result = array_merge($result, getSimilar($words, $maxDistance - 1));
        return $result;
    }

    return NULL;
}

function searchArticles($query, $maxWords) {
    $sql = "SELECT DISTINCT A.PostID, A.Title, U.Name FROM Articles A
            INNER JOIN Users U ON A.CreatorID = U.UserCode
            INNER JOIN Tags T ON A.PostID = T.PostID
            WHERE MATCH(A.Title, A.Transcript, U.Name, T.TagName) AGAINST ('" . $query . "' IN BOOLEAN MODE)";

    $searchResult = $conn->query($sql);

    if($searchResult->num_rows > 0) {
        echo '<p class="results">' . $result->num_rows . ' resultados para "' . $query . '"</p>';

        while($row = $searchResult->fetch_assoc())
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
    else {
        if (str_word_count($query) > $maxWords) {
            $pieces = explode(" ", $query);
            $cutSearch = '<p style="color: #808080; font-size: small;"><b>"' . $pieces[$maxWords] . '"</b> (y las palabras que le siguen) se ignoraron porque limitamos las consultas a ' . $maxWords . ' palabras.</p>';
            $query = implode(" ", array_splice($pieces, 0, $maxWords));
        }

        $disance = 2; // Cambiar esto hace que busque más, pero hace (3 * [largo de string]) ^ distancia queries
        $posts = getSimilar($query, 2);

        if($posts != NULL) {
            $count = count($posts)
            for($i = 0; $i < $count; $i++) {
                echo '<div class="col s12">
                          <div class="card horizontal hoverable item" id="' . $posts[$i]['PostID'] . '">
                              <div class="card-image" style="width: 192px;">
                                  <img src="../post/content/' . md5($posts[$i]['PostID']) . '/image.jpg">
                              </div>
                              <div class="card-stacked">
                                  <div class="card-content">
                                      <strong>' .$posts[$i]['Title'] . '</strong>
                                      <p>' . $posts[$i]['Name'] . '</p>
                                  </div>
                              </div>
                          </div>
                      </div>';
            }
        }
    }
}

?>