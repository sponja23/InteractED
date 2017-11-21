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
        </style>
    </head>
    <body class="grey lighten-5">
        <?php require "components/navigation/navigation.php"; ?>

        <div class="container">
            <div class="row">
                <?php
                include "post/functions.php";
                require "recommend/recommend.php";
                ?>

                <?php postsByRatings(); ?>

                <div class="divider"></div>

                <?php
                include "include/connect.php";

                $sql = "SELECT CategoryID, CategoryName FROM Categories WHERE Implemented = 1 ORDER BY CategoryName";
                $Categories = $conn->query($sql);

                $CurrentRow = 1;

                while ($CategoryValues = $Categories->fetch_assoc()) {
                    $sql = 'SELECT A.PostID, A.Title, U.User FROM Articles A
                            INNER JOIN Users U ON A.CreatorID = U.UserCode
                            WHERE A.CategoryID = "' . $CategoryValues["CategoryID"] . '"';
                    $PostsInCategory = $conn->query($sql);

                    if ($PostsInCategory->num_rows > 0) {
                        echo '<h5 class="col s12">' . $CategoryValues["CategoryName"] . '</h5>';

                        $Cards = 1;

                        while ($PostValues = $PostsInCategory->fetch_assoc()) {
                            $Image = glob("images/posts/" . $PostValues["PostID"] . ".*");

                            if ($Cards == 1)
                                echo '<div class="row" style="margin-bottom: 0;">';

                            addVerticalCard($PostValues["PostID"], $Image[0], $PostValues["Title"], $PostValues["User"]);

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
                }
                ?>
                <h5 class="col s12">Destacados</h5>
                <div class="row" style="margin-bottom: 0;">
                    <?php postsBySimilarTags(); ?>
                </div>
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