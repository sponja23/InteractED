<?php session_start(); ?>
<!DOCTYPE html>
<html>
    <head>
        <title>Add New Category/title>

        <?php require "../../include/head.html"; ?>

        <link rel="stylesheet" href="../../components/navigation/navigation.css">
    </head>
    <body class="grey lighten-5">
        <?php require "../../components/navigation/navigation.php"; ?>

        <div class="container">
            <div class="card-panel center-align">
                <span><?php
                if (!getimagesize($_FILES["image"]["tmp_name"]))
                    $Code = 1;
                else {
                    require "../../include/connect.php";

                    $Category = $_POST["Category"];

                    $sql = 'INSERT INTO categories (Category)
                            VALUES ("' . $Category . '")';

                    if ($conn->query($sql) === TRUE) {
                        $sql = 'SELECT CategoryID FROM categories WHERE CategoryName ="' . $Category . '"';
                        $result = $conn->query($sql);

                        if ($result->num_rows > 0) {
                            while($row = $result->fetch_assoc()) {
                                $File = "../../images/categories/" . $row['CategoryID'] . "." . pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION);
                            }

                        if (!file_exists("../../images/categories/"))
                            mkdir("../../images/categories/");

                        if (move_uploaded_file($_FILES["image"]["tmp_name"], $File))
                            $Code = 3;
                        else {
                            $Code = 2;
                            $sql = "DELETE FROM Categorys WHERE CategoryCode=" . $conn->insert_id;
                            $conn->query($sql);
                        }
                    }
                    else
                        $Code = 2;
                }

                switch ($Code) {
                    case 1:
                        echo "Imagen invalida, por favor suba una imagen valida";
                        break;
                    case 2:
                        echo "Error al registrarse, por favor intente de nuevo";
                        break;
                    case 3:
                        
                        break;
                    default:
                        echo "Error desconocido, por favor intente de nuevo";
                }
                ?></span>
            </div>
        </div>

        <?php require "../../include/scripts.html"; ?>

        <script src="../../components/navigation/navigation.js"></script>
    </body>
</html>