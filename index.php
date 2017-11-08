<?php session_start(); ?>
<!DOCTYPE html>
<html>
    <head>
        <title>InteractED</title>

        <?php require "include/head.html"; ?>

        <link rel="stylesheet" href="components/navigation/navigation.css">

        <style>
            .item:hover {
                cursor: pointer;
                cursor: hand;
            }

            .image-card {
                width: initial !important;
                max-width: 100%;
                max-height: 100%;
                display: block;
                margin: 0 auto;
            }

            .image-card-wrapper {
                height: 100px;
            }
        </style>
    </head>
    <body class="grey lighten-5">
        <?php require "components/navigation/navigation.php"; ?>

        <div class="container">
            <div class="row">
                <?php
                include "include/connect.php";

                $sql = "SELECT CategoryID, CategoryName FROM Categories WHERE Implemented = 1 ORDER BY CategoryName";
                $Categories = $conn->query($sql);

                $CurrentRow = 1;

                while ($CategoryValues = $Categories->fetch_assoc()) {
                    echo '<h5>' . $CategoryValues["CategoryName"] . '</h5>';

                    $sql = 'SELECT A.PostID, A.Title, U.User FROM Articles A
                            INNER JOIN Users U ON A.CreatorID = U.UserCode
                            WHERE A.CategoryID = "' . $CategoryValues["CategoryID"] . '"';
                    $PostsInCategory = $conn->query($sql);

                    $Cards = 1;

                    while ($PostValues = $PostsInCategory->fetch_assoc()) {
                        $Image = glob("images/posts/" . $PostValues["PostID"] . ".*");

                        if ($Cards == 1)
                            echo '<div class="row" style="margin-bottom: 0;">';

                        echo '<div class="col s12 m3">
                                  <div class="card hoverable item" id="' . md5($PostValues["PostID"]) . '">
                                      <div class="card-image valign-wrapper image-card-wrapper">
                                          <img class="image-card" src="' . $Image[0] . '">
                                      </div>
                                      <div class="card-content">
                                          <strong>' . $PostValues["Title"] . '</strong>
                                          <p>' . $PostValues["User"] . '</p>
                                      </div>
                                  </div>
                              </div>';

                        if ($Cards++ == 4) {
                            $Cards = 1;
                            echo '</div>';
                        }
                    }
                    echo '</div>';

                    if ($CurrentRow++ < $Categories->num_rows) {
                        echo '<div class="divider col s12"></div>';
                    }
                }
                ?>

                <!-- <div class="col s12">
                    <div class="divider"></div>
                    <h5>Recomendados</h5>
                    Aca va tu Engine en NODE Sponja

                    <?php //include "recommend/recommend.php"; ?>

                    <div class="divider"></div>
                    <h5>Recomendados por los articulos visitados</h5>
                    <?php //postsBySimilarTags(); ?>

                    <div class="divider"></div>
                    <h5>Recomendados por usuarios con intereses similares</h5>
                    <?php //postsBySimilarPeople(); ?>
                </div> -->
            </div>
        </div>

        <?php require "include/scripts.html"; ?>

        <script src="components/navigation/navigation.js"></script>

        <script>
            $( ".item" ).click(function() {
                window.location.href = "post?id=" + $(this).attr("id");
            });
        </script>
    </body>
</html>