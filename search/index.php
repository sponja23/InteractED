<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">

        <title>InteractED</title>

        <?php require "../include/head.html"; ?>

        <link rel="stylesheet" href="../css/navigation.css">
        <link rel="stylesheet" href="../css/font.css">

        <style>
            .item:hover {
                cursor: pointer;
                cursor: hand;
            }

            .results {
                font-size: 1.1rem;
                font-weight: 400;
                line-height: 2rem;
            }
        </style>
    </head>
    <body class="grey lighten-5">
        <!-- Navigation -->
        <?php require "../components/navigation.php"; ?>

        <div class="container">
            <?php
            $search = $_GET["q"];

            require "../include/connect.php";

            $sql = 'SELECT * FROM Articles WHERE Title LIKE "%' . $search . '%" OR Creator LIKE "%' . $search . '%" OR Tags LIKE "%' . $search . '%" OR Text LIKE "%' . $search . '%"';

            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                echo '<p class="results">' . $result->num_rows . ' resultados</p>';

                while($row = $result->fetch_assoc()) {
                    echo '<div class="col s12">
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
            }
            else
                echo '<p class="results">No hay resultados para <b>' . $search . '</b></p>';

            $conn->close();
            ?>
        </div>

        <?php require "../include/scripts.html"; ?>

        <script>
            $( "#search" ).val(<?= '"' . $_GET["q"] . '"' ?>);

            $( ".item" ).click(function() {
                window.location.href = "../post?id=" + $(this).attr("id");
            });
        </script>
    </body>
</html>