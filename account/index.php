<?php
session_start();

if (!isset($_SESSION["UserCode"]))
    header("Location: ../");
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Mi cuenta - InteractED</title>

        <?php require "../include/head.html"; ?>

        <link rel="stylesheet" href="styles.css">
        <link rel="stylesheet" href="../components/navigation/navigation.css">
        <link rel="stylesheet" href="../css/input.css">
    </head>
    <body class="grey lighten-5">
        <?php require "../components/navigation/navigation.php"; ?>

        <div class="container">
            <div class="row">
                <div class="col s12">
                    <h1 id="title">Mi cuenta</h1>
                </div>
                <div class="card-panel black-text col s12">
                    <div style="padding-top: 10px;"></div>
                    <div class="input-field col s12 m6">
                        <input id="name" name="name" type="text">
                        <label for="name" data-error="Debe ingresar su nombre">Nombre</label>
                    </div>
                    <div class="input-field col s12 m6">
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
                    <div class="col s12">
                        <a id="save" class="btn blue waves-effect waves-light">Guardar</a>
                    </div>
                </div>
            </div>
        </div>

        <?php require "../include/scripts.html"; ?>

        <script src="../components/navigation/navigation.js"></script>

        <script>
            var Name = <?= '"' . $_SESSION["Name"] . '"' ?>;
            var Email = <?= '"' . $_SESSION["Email"] . '"' ?>;

            $( "#name" ).val(Name);
            $( "#email" ).val(Email);

            $( "#save" ).click(function() {
                var NewData = {};

                if ($( "#name" ).val() != Name && $( "#name" ).val() != "")
                    NewData.Name = $( "#name" ).val();

                if ($( "#email" ).val() != Email && $( "#email" ).val() != "")
                    NewData.Email = $( "#email" ).val();

                if ($( "#password" ).val() != "" && $( "#confirm-password" ).val() != "" &&
                    $( "#password" ).val() == $( "#confirm-password" ).val())
                    NewData.Password = $( "#password" ).val();

                if (!jQuery.isEmptyObject(NewData)) {
                    $.ajax({
                        url: "update.php",
                        type: "POST",
                        data: NewData ,
                        success: function (response) {
                            if (response == '1')
                                location.reload();
                        },
                        error: function(jqXHR, textStatus, errorThrown) {
                            console.log(textStatus, errorThrown);
                        }
                    });
                }
            });
        </script>
    </body>
</html>