<?php session_start(); ?>
<!DOCTYPE html>
<html>
    <head>
        <title>Mi cuenta - InteractED</title>

        <?php require "../include/head.html"; ?>

        <link rel="stylesheet" href="../components/navigation/navigation.css">
        <link rel="stylesheet" href="../css/input.css">
    </head>
    <body class="grey lighten-5">
        <?php require "../components/navigation/navigation.php"; ?>

        <div class="container">
            <div class="row">
                <div class="card-panel black-text col s12">
                    <div class="input-field col s12 m6">
                        <input id="user" name="user" type="text">
                        <label for="user" data-error="Debe ingresar un nombre de usuario">Usuario</label>
                    </div>
                    <div class="input-field col s12 m6">
                        <input id="name" name="name" type="text">
                        <label for="name" data-error="Debe ingresar su nombre">Nombre</label>
                    </div>
                    <div class="input-field col s12">
                        <input id="email" name="email" type="email">
                        <label for="email">Correo electr&oacute;nico</label>
                    </div>
                    <p style="padding-left: 0.75rem;"><strong>Cambiar contrase&ntilde;a:</strong></p>
                    <div class="input-field col s12 m6">
                        <input id="password" name="password" type="password">
                        <label for="password" data-error="Debe ingresar una contrase&ntilde;a">Nueva contrase&ntilde;a</label>
                    </div>
                    <div class="input-field col s12 m6">
                        <input id="confirm-password" type="password">
                        <label for="confirm-password" data-error="Debe volver a ingresar la contrase&ntilde;a">Confirmar contrase&ntilde;a</label>
                    </div>
                </div>
            </div>
        </div>

        <?php require "../include/scripts.html"; ?>

        <script src="../components/navigation/navigation.js"></script>
    </body>
</html>