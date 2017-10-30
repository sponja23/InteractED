<?php session_start(); ?>
<!DOCTYPE html>
<html>
    <head>
        <title>InteractED</title>

        <?php require "include/head.html"; ?>

        <?php 
            include "recommend/recommend.php";
            //$MaxValue  = 5;
        ?>

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
            <h5>Recientes</h5>
            <div class="row">
                <div class="col s12 m3">
                    <div class="card hoverable item" id="c4ca4238a0b923820dcc509a6f75849b">
                        <div class="card-image">
                            <img src="https://i.ytimg.com/vi/2OgLKz9yQ0Q/hqdefault.jpg?custom=true&w=246&h=138&stc=true&jpg444=true&jpgq=90&sp=68&sigh=IE1JFDEOZl_4r872Wlo5ydYUKjc">
                        </div>
                        <div class="card-content">
                            <strong>Así es un día normal por calles de Venezuela | NO HAY PAN</strong>
                            <p>Luisito Comunica</p>
                        </div>
                    </div>
                </div>
                <div class="col s12 m3">
                    <div class="card hoverable item" id="c4ca4238a0b923820dcc509a6f75849b">
                        <div class="card-image">
                            <img src="https://i.ytimg.com/vi/2OgLKz9yQ0Q/hqdefault.jpg?custom=true&w=246&h=138&stc=true&jpg444=true&jpgq=90&sp=68&sigh=IE1JFDEOZl_4r872Wlo5ydYUKjc">
                        </div>
                        <div class="card-content">
                            <strong>Así es un día normal por calles de Venezuela | NO HAY PAN</strong>
                            <p>Luisito Comunica</p>
                        </div>
                    </div>
                </div>
                <div class="col s12 m3">
                    <div class="card hoverable item" id="c4ca4238a0b923820dcc509a6f75849b">
                        <div class="card-image">
                            <img src="https://i.ytimg.com/vi/2OgLKz9yQ0Q/hqdefault.jpg?custom=true&w=246&h=138&stc=true&jpg444=true&jpgq=90&sp=68&sigh=IE1JFDEOZl_4r872Wlo5ydYUKjc">
                        </div>
                        <div class="card-content">
                            <strong>Así es un día normal por calles de Venezuela | NO HAY PAN</strong>
                            <p>Luisito Comunica</p>
                        </div>
                    </div>
                </div>
                <div class="col s12 m3">
                    <div class="card hoverable item" id="c4ca4238a0b923820dcc509a6f75849b">
                        <div class="card-image">
                            <img src="https://i.ytimg.com/vi/2OgLKz9yQ0Q/hqdefault.jpg?custom=true&w=246&h=138&stc=true&jpg444=true&jpgq=90&sp=68&sigh=IE1JFDEOZl_4r872Wlo5ydYUKjc">
                        </div>
                        <div class="card-content">
                            <strong>Así es un día normal por calles de Venezuela | NO HAY PAN</strong>
                            <p>Luisito Comunica</p>
                        </div>
                    </div>
                </div>
            </div>
            <li class="divider"></li>
            <h5>Recomendados por los articulos visitados</h5>
            <div class="row">
                <?php
                    //echo Recomend::GetArticles($MaxValue, 'Articles');
                postsBySimilarTags();
                ?>
            </div>
            <li class="divider"></li>
            <h5>Recomendados por usuarios con intereses similares</h5>
            <div class="row">
                <?php
                    //echo Recomend::GetArticles($MaxValue, 'Users');    
                postsBySimilarPeople();
                ?>
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