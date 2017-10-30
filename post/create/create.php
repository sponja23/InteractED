<?php
session_start();

if (!getimagesize($_FILES["image"]["tmp_name"]))
    $Code = 1;
else {
    require "../../include/connect.php";

    $Title = $_POST["title"];
    $Category = $_POST["category"];

    $CreateArticle = 'INSERT INTO Articles (CreatorID, CreateDate, Title, CategoryID)
                      VALUES (' . $_SESSION["UserCode"] . ', CURDATE(), "' . $Title . '", (SELECT CategoryID FROM Categories WHERE CategoryName = "' . $Category . '"))';

    if ($conn->query($CreateArticle) === TRUE) {
        $PostID = $conn->insert_id;

        $File = "../../images/posts/" . $PostID . "." . pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION);

        if (!file_exists("../../images/posts/"))
            mkdir("../../images/posts/");

        if (move_uploaded_file($_FILES["image"]["tmp_name"], $File)) {
            $UploadTags = "INSERT INTO Tags (PostID, TagName) VALUES";

            foreach ($_POST["tags"] as $Tag)
                $UploadTags .= ' (' . $PostID . ', "' . $Tag . '"),';

            $UploadTags = substr($UploadTags, 0, -1);

            if ($conn->query($UploadTags) === TRUE)
                echo '<script>window.location.replace("../../editor?id=' . md5($PostID) . '");</script>';
            else
                $Code = 2;
        }
        else {
            $Code = 2;
            $sql = "DELETE FROM Articles WHERE PostID = " . $PostID;
            $conn->query($sql);
        }
    }
    else
        echo $conn->error;

    $conn->close();
}

switch ($Code) {
    case 1:
        $Error = "Imagen invalida, por favor suba una imagen valida";
        break;
    case 2:
        $Error = "Error al registrarse, por favor intente de nuevo";
        break;
    default:
        $Error = "Error desconocido, por favor intente de nuevo";
}
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Resultado - Registrarse</title>

        <?php require "../../include/head.html"; ?>

        <link rel="stylesheet" href="../../components/navigation/navigation.css">
    </head>
    <body class="grey lighten-5">
        <?php require "../../components/navigation/navigation.php"; ?>

        <div class="container">
            <div class="card-panel center-align">
                <span><?= $Error ?></span>
            </div>
        </div>

        <?php require "../../include/scripts.html"; ?>

        <script src="../../components/navigation/navigation.js"></script>
    </body>
</html>