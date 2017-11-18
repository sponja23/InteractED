<?php session_start(); ?>
<!DOCTYPE html>
<html>
    <head>
        <title>InteractED</title>

        <?php 
        require "../../include/head.html"; 
        require "../../include/connect.php"
        ;?>

        <link rel="stylesheet" href="../../components/navigation/navigation.css">

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
        <?php require "../../components/navigation/navigation.php"; ?>

        <div class="container">
            <h5>Categorias en Proceso</h5>
            <table>
            <?php
                echo '<a style="padding-right:5px" href="../index.php" class="btn-floating btn-large waves-effect waves-light blue"><i class="material-icons">backspace</i></a>';

                include "unconfirmed.php";
                if($_SESSION["Level"] >= 2)
                    getUnconfirmed();
                else 
                    echo "<script>window.history.back();</script>";

            ?>
            </table>

        <?php require "../../include/scripts.html"; ?>

        <script src="../../components/navigation/navigation.js"></script>

        <script>
        
        </script>
    </body>
</html>