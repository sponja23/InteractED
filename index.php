<?php session_start(); ?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">

        <title>InteractED</title>

        <?php require "include/head.html"; ?>

        <link rel="stylesheet" href="css/navigation.css">
        <link rel="stylesheet" href="css/font.css">

        <style>
            .item:hover {
                cursor: pointer;
                cursor: hand;
            }
        </style>
    </head>
    <body class="grey lighten-5">
        <!-- Navigation -->
        <?php require "components/navigation.php"; ?>

        <div class="container">
            <h5>Recientes</h5>
            <div class="row">
                <div class="col s12 m3">
                    <div class="card hoverable item" id="1">
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
                    <div class="card hoverable item" id="1">
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
                    <div class="card hoverable item" id="1">
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
                    <div class="card hoverable item" id="1">
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
            <h5>Recomendados</h5>
            <div class="row">
                <div class="col s12 m3">
                    <div class="card hoverable item" id="1">
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
                    <div class="card hoverable item" id="1">
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
                    <div class="card hoverable item" id="1">
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
                    <div class="card hoverable item" id="1">
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
        </div>

        <?php require "include/scripts.html"; ?>

        <script>
            $( ".item" ).click(function() {
                window.location.href = "post?id=" + $(this).attr("id");
            });
        </script>
    </body>
</html>