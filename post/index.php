<?php
session_start();

include "../include/connect.php";

$sql = 'SELECT A.PostID, DATE_FORMAT(A.CreateDate, "%d/%m/%Y") CreateDate, A.Title, U.UserCode, U.Name FROM Articles A
        INNER JOIN Users U ON A.CreatorID = U.UserCode
        WHERE MD5(A.PostID) = "' . $_GET["id"] . '"';

$result = $conn->query($sql);

if ($result->num_rows > 0)
    while ($row = $result->fetch_assoc()) {
        $PostImage = glob("../images/posts/" . $row["PostID"] . ".*");
        $Title = $row["Title"];
        $PostID = $row["PostID"];
        $UserImage = glob("../images/users/" . $row["UserCode"] . ".*");
        $Name = $row["Name"];
        $CreateDate = $row["CreateDate"];
    }
else
    header("Location: ../");

$sql = "INSERT INTO Visited (PostID, UserCode, DateLastVisited)
        VALUES (" . $PostID . ", " . $_SESSION["UserCode"] . ", CURTIME());";

$conn->query($sql);
?>
<!DOCTYPE html>
<html>
    <head>
        <title><?= $Title ?> - InteractED</title>

        <?php require "../include/head.html"; ?>

        <link rel="stylesheet" href="styles.css">
        <link rel="stylesheet" href="../components/navigation/navigation.css">
        <link rel="stylesheet" href="../components/rating/rating.css">
        <link rel="stylesheet" href="../components/comments/comments.css">
    </head>
    <body class="grey lighten-5">
        <?php require "../components/navigation/navigation.php"; ?>

        <div class="container">
            <img id="post-image" src=<?= '"' . $PostImage[0] . '"' ?>>
            <h5 id="post-title"><?= $Title ?></h5>
            <div class="valign-wrapper">
                <img id="post-creator-image" class="circle" src=<?= '"' . $UserImage[0] . '"' ?>>
                <p>
                    <strong><?= $Name ?></strong>
                    <br>
                    Creado el <?= $CreateDate ?>
                </p>
                <?php
                $sql = 'SELECT A.PostID FROM Articles A
                        LEFT JOIN EditorRelation ER ON A.PostID = ER.PostID
                        WHERE MD5(A.PostID) = "' . $_GET["id"] . '" AND
                        (A.CreatorID = ' . $_SESSION["UserCode"] . ' OR ER.UserCode = ' . $_SESSION["UserCode"] . ' OR ' .
                        $_SESSION["Level"] . ' >= 1)';

                $result = $conn->query($sql);

                if ($result->num_rows > 0)
                    echo '<a href="../editor?id=' . $_GET["id"] . '" id="edit" class="btn blue waves-effect waves-light">Editar post</a>'
                ?>
            </div>
            <?php
            if (file_exists("content/" . $_GET["id"] . "/index.html"))
                echo file_get_contents("content/" . $_GET["id"] . "/index.html");
            ?>
            Tags: <?php
            $sql = 'SELECT TagName FROM Tags
                    WHERE MD5(PostID) = "' . $_GET["id"] . '"';

            $result = $conn->query($sql);

            while ($row = $result->fetch_assoc())
                echo '<div class="chip">' . $row["TagName"] . '</div>';
            ?>
            <?php require "../components/rating/index.html"; ?>
            <?php require "../components/comments/index.php"; ?>
        </div>

        <?php require "../include/scripts.html"; ?>

        <script src="../components/navigation/navigation.js"></script>
        <script src="../components/rating/rating.js"></script>
        <script src="../components/comments/comments.js"></script>
    </body>
</html>