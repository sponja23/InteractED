<?php
session_start();

if (!isset($_SESSION["UserCode"]))
    header("Location: ../");

$Image = glob("../images/users/" . $_SESSION["UserCode"] . ".*");
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
                    <form id="update-account" action="update.php" method="post" enctype="multipart/form-data">
                        <div class="col s12 valign-wrapper" style="margin: 20px 0;">
                            <img id="profile-image" src=<?= '"' . $Image[0] . '"' ?> class="circle" style="width: 100px; height: 100px;">
                            <a id="change" class="btn blue waves-effect waves-light" style="margin-left: 20px;">Cambiar</a>
                            <input id="image" name="image" type="file" class="hide">
                        </div>
                        <div class="col s12 valign-wrapper" style="margin: 20px 0;">
                            <p style="padding-left: 0.75rem;">
                                <strong>Nivel: 
                                    <?php switch($_SESSION['Level'])
                                            case 0:
                                                echo "B&aacute;sico";
                                            break;
                                            case 1:
                                                echo "Edici&oacute;n";
                                            break;
                                            case 2:
                                                echo "Administrador";
                                            break;
                                            case 3:
                                                echo "Nosotros";
                                            break;
                                    ?>
                                </strong>
                            </p>
                            <a href="../levelrequest/" id="lvlRequest" class="btn blue waves-effect waves-light" style="margin-left: 45px;">Solicitar Nivel</a>
                        </div>
                        <div class="input-field col s12 m6">
                            <input id="name" name="Name" type="text">
                            <label for="name" data-error="El nombre no puede estar vac&iacute;o">Nombre</label>
                        </div>
                        <div class="input-field col s12 m6">
                            <input id="email" name="Email" type="email">
                            <label for="email" data-error="El correo electronico no puede estar vac&iacute;o">Correo electr&oacute;nico</label>
                        </div>
                        <p style="padding-left: 0.75rem;"><strong>Cambiar contrase&ntilde;a:</strong></p>
                        <div class="input-field col s12 m6">
                            <input id="password" name="Password" type="password">
                            <label for="password">Nueva contrase&ntilde;a</label>
                        </div>
                        <div class="input-field col s12 m6">
                            <input id="confirm-password" type="password">
                            <label for="confirm-password" data-error="Debe ingresar la misma contrase&ntilde;a">Confirmar contrase&ntilde;a</label>
                        </div>
                        
                        
                    </form>
                    <div class="col s12">
                        <a id="save" class="btn blue waves-effect waves-light">Guardar</a>
                    </div>
                </div>
            </div>
        </div>

        <?php require "../include/scripts.html"; ?>

        <script src="../components/navigation/navigation.js"></script>

        <script>
            $( "#change" ).click(function() {
                $( "#image" ).trigger( "click" );
            });

            $( "#image" ).change(function() {
                var src = document.getElementById("image");
                var target = document.getElementById("profile-image");
                var fr = new FileReader();

                fr.onload = function() {
                    target.src = fr.result;
                }

                fr.readAsDataURL(src.files[0]);
            });

            $( "#name" ).val(<?= '"' . $_SESSION["Name"] . '"' ?>);
            $( "#email" ).val(<?= '"' . $_SESSION["Email"] . '"' ?>);

            $( "#save" ).click(function() {
                if ($( "#name" ).val() != "") {
                    if ($( "#email" ).val() != "") {
                        if ($( "#password" ).val() == $( "#confirm-password" ).val()) {
                            $( "#update-account" ).submit();
                        }
                        else
                            $( "#confirm-password" ).addClass("validate invalid").focus();
                    }
                    else
                        $( "#email" ).addClass("validate invalid").focus();
                }
                else
                    $( "#name" ).addClass("validate invalid").focus();
            });

            $( "input" ).on('input', function() {
                if ($(this).val() != "")
                    $(this).removeClass("validate valid invalid");
            }).keypress(function(e) {
                if(e.which == 13) {
                    $( "#save" ).trigger( "click" );
                }
            });
        </script>
    </body>
</html>