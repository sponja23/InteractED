<?php
function addResult($ID, $Extension, $Title, $Creator) {
    echo '<div class="col s12">
              <div class="card horizontal hoverable item" id="' . md5($ID) . '">
                  <div class="card-image valign-wrapper" style="width: 192px;">
                      <img src="../images/posts/' . $ID . '.' . $Extension . '" style="display: block; margin: 0 auto;">
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

function postInArray($arr, $post) {
  foreach ($arr as $p) {
    if($p["PostID"] == $post["PostID"])
      return true;
  }
  return false;
}

function getSimilar($word, $maxDistance = 1) {
    require "../include/connect.php";

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
        $words[] = $first_part . '_' . $last_part;
    }

    $words[] = $word . '_';

    $sql = "SELECT DISTINCT A.PostID, A.Title, U.Name FROM Articles A
            INNER JOIN Users U ON A.CreatorID = U.UserCode
            INNER JOIN Tags T ON A.PostID = T.PostID
            WHERE A.Title LIKE '%" . $words[0] . "%' OR U.Name LIKE '%" . $words[0] . "%' OR T.TagName LIKE '%" . $words[0] . "%' OR A.Transcript LIKE '%" . $words[0] . "%'";

    for($i = 1; $i < count($words); $i++)
        $sql .= " OR A.Title LIKE '%" . $words[$i] . "%' OR U.Name LIKE '%" . $words[$i] . "%' OR T.TagName LIKE '%" . $words[$i] . "%' OR A.Transcript LIKE '%" . $words[$i] . "%'";
        
    $searchResult = $conn->query($sql);

    $result =  array();
    $foundResults = False;

    if($searchResult->num_rows > 0) {
        while($row = $searchResult->fetch_assoc())
            $result[] = $row;
        $foundResults = True;
    }

    if($maxDistance > 1) {
      $count = count($words);
      for($i = 0; $i < $count; $i++) {
          $similar = getSimilar($words[$i], $maxDistance - 1);
          if(!is_null($similar)) {
            $result = array_merge($result, $similar);
            $foundResults = True;
          }
      }
    }

    if($foundResults) {
      $uniqueResults = array();
      foreach ($result as $r) {
        if(!postInArray($uniqueResults, $r))
          $uniqueResults[] = $r;
      }
      return $uniqueResults;
    }
    else
      return NULL;
}

function searchArticles($query, $maxWords) {
    require "../include/connect.php";

    $sql = "SELECT DISTINCT A.PostID, A.Title, U.Name FROM Articles A
            INNER JOIN Users U ON A.CreatorID = U.UserCode
            INNER JOIN Tags T ON A.PostID = T.PostID
            WHERE MATCH(A.Title, A.Transcript, U.Name, T.TagName) AGAINST ('" . $query . "' IN BOOLEAN MODE)";

    $searchResult = $conn->query($sql);

    if($searchResult->num_rows > 0) {
        echo '<p class="results">' . $result->num_rows . ' resultados para "' . $query . '"</p>';

        while ($row = $searchResult->fetch_assoc()) {
            $Extension = glob("../images/posts/" . $row['PostID'] . ".*");
            $Extension = pathinfo($Extension[0]);
            $Extension = $Extension['extension'];

            addResult($row['PostID'], $Extension, md5($row['PostID']), $row['Title'], $row['Name']);
        }
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
            echo '<p class="results">' . $result->num_rows . ' resultados para "' . $query . '"</p>';
            $count = count($posts);
            for($i = 0; $i < $count; $i++) {
                $currentPost = $posts[$i];

                $Extension = glob("../images/posts/" . $currentPost['PostID'] . ".*");
                $Extension = pathinfo($Extension[0]);
                $Extension = $Extension['extension'];

                addResult($currentPost['PostID'], $Extension, $currentPost['Title'], $currentPost['Name']);
              }
        }
        else {
            echo '<div>
                      <p>No se han encontrado resultados para tu búsqueda</p>
                      <p>Sugerencias:</p>
                      <ul style="margin-left:1.3em;">
                          <li style="list-style-type: disc;">Asegúrate de que todas las palabras estén escritas correctamente.</li>
                          <li style="list-style-type: disc;">Prueba diferentes palabras clave.</li>
                          <li style="list-style-type: disc;">Prueba palabras clave más generales.</li>
                      </ul>
                  </div>';            
        }
    }
}
?>