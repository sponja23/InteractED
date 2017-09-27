<?php
class Search {
    function SearchQuery($Search, $MaxWords) {
        require "../include/connect.php";

        $sql = 'SELECT * FROM Articles WHERE Title LIKE "%' . $Search . '%" OR Creator LIKE "%' . $Search . '%" OR Tags LIKE "%' . $Search . '%" OR Text LIKE "%' . $Search . '%"';

        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            echo '<p class="results">' . $result->num_rows . ' resultados para "' . $Search . '"</p>';

            while($row = $result->fetch_assoc())
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
            if (str_word_count($Search) > $MaxWords) {
                $Pieces = explode(" ", $Search);
                $CutSearch = '<p style="color: #808080; font-size: small;"><b>"' . $Pieces[$MaxWords] . '"</b> (y las palabras que le siguen) se ignoraron porque limitamos las consultas a ' . $MaxWords . ' palabras.</p>';
                $Search = implode(" ", array_splice($Pieces, 0, $MaxWords));
            }

            $Words = $this->CheckQuery($Search);

            $sql = 'SELECT * FROM Articles WHERE Title LIKE "%' . $Words[0] . '%" OR Creator LIKE "%' . $Words[0] . '%" OR Tags LIKE "%' . $Words[0] . '%" OR Text LIKE "%' . $Words[0] . '%"';

            for ($i = 1; $i < count($Words); $i++)
                $sql .= 'OR Title LIKE "%' . $Words[$i] . '%" OR Creator LIKE "%' . $Words[$i] . '%" OR Tags LIKE "%' . $Words[$i] . '%" OR Text LIKE "%' . $Words[$i] . '%"';

            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                if (isset($CutSearch))
                    echo $CutSearch;

                echo '<p class="results">' . $result->num_rows . ' resultados</p>';

                while ($row = $result->fetch_assoc())
                    echo '<div class="col s12" id="article">
                              <div class="card horizontal hoverable item" id="' . $row['PostID'] . '">
                                  <div class="card-image" style="width: 192px;">
                                      <img src="' . $row['Image'] . '">
                                  </div>
                                  <div class="card-stacked">
                                      <div class="card-content">
                                          <strong>' . $row['Title'] . '</strong>
                                          <p>' . $row['Creator'] . '</p>
                                      </div>
                                  </div>
                              </div>
                          </div>';
            }
            else
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

        $conn->close();
    }

    function CheckQuery($Search) {
        return array_merge(
            $this->Replace($Search, "Delete"),
            $this->Replace($Search, "Replace"),
            $this->Replace($Search, "Swap"),
            $this->Replace($Search, "Insert")
        );
    }

    function Replace($Search, $Action) {
        $Alphabet = str_split("abcdefghijklmnopqrstuvwxyz0123456789");

        for ($i = 0; $i < strlen($Search); $i++) {
            switch ($Action) {
                case "Delete":
                    $Edits[] = substr($Search, 0, $i) . substr($Search, $i + 1);
                    break;
                case "Replace":
                    foreach ($Alphabet as $Letter)
                        $Edits[] = substr($Search, 0, $i) . $Letter . substr($Search, $i + 1);
                    break;
                case "Swap":
                    $Edits[] = substr($Search, 0, $i) . $Search[$i + 1] . $Search[$i] . substr($Search, $i + 2);
                    break;
                case "Insert":
                    foreach ($Alphabet as $Letter)
                        $Edits[] = substr($Search, 0, $i) . $Letter . substr($Search, $i);
                    break;
            }
        }

        return array_unique($Edits);
    }
}
?>