<!DOCTYPE html>
<html>
    <head>
        <title>Registrarse</title>

        <?php require "../include/head.html"; ?>

        <link rel="stylesheet" href="styles.css">
        <link rel="stylesheet" href="../css/input.css">
        <link rel="stylesheet" href="../css/font.css">
    </head>
    <body>
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 810" preserveAspectRatio="xMinYMin slice" class="background">
            <path fill="#efefee" d="M592.66 0c-15 64.092-30.7 125.285-46.598 183.777C634.056 325.56 748.348 550.932 819.642 809.5h419.672C1184.518 593.727 1083.124 290.064 902.637 0H592.66z"></path>
            <path fill="#f6f6f6" d="M545.962 183.777c-53.796 196.576-111.592 361.156-163.49 490.74 11.7 44.494 22.8 89.49 33.1 134.883h404.07c-71.294-258.468-185.586-483.84-273.68-625.623z"></path>
            <path fill="#f7f7f7" d="M153.89 0c74.094 180.678 161.088 417.448 228.483 674.517C449.67 506.337 527.063 279.465 592.56 0H153.89z"></path>
            <path fill="#fbfbfc" d="M153.89 0H0v809.5h415.57C345.477 500.938 240.884 211.874 153.89 0z"></path>
            <path fill="#ebebec" d="M1144.22 501.538c52.596-134.583 101.492-290.964 134.09-463.343 1.2-6.1 2.3-12.298 3.4-18.497 0-.2.1-.4.1-.6 1.1-6.3 2.3-12.7 3.4-19.098H902.536c105.293 169.28 183.688 343.158 241.684 501.638v-.1z"></path>
            <path fill="#e1e1e1" d="M1285.31 0c-2.2 12.798-4.5 25.597-6.9 38.195C1321.507 86.39 1379.603 158.98 1440 257.168V0h-154.69z"></path>
            <path fill="#e7e7e7" d="M1278.31,38.196C1245.81,209.874 1197.22,365.556 1144.82,499.838L1144.82,503.638C1185.82,615.924 1216.41,720.211 1239.11,809.6L1439.7,810L1439.7,256.768C1379.4,158.78 1321.41,86.288 1278.31,38.195L1278.31,38.196z"></path>
        </svg>

        <div class="valign-wrapper background">
            <div class="row">
                <div class="col s12 m6 offset-m3">
                    <div class="card-panel black-text">
                        <span id="logo" class="blue-text">InteractED</span>
                        <h1 id="title">Registrarse</h1>
                        <form id="register" action="result/index.php" method="post" enctype="multipart/form-data">
                            <div class="file-field input-field">
                                <div class="btn blue">
                                    <span>Imagen</span>
                                    <input type="file" name="image">
                                </div>
                                <div class="file-path-wrapper">
                                    <input id="image-path" class="file-path" type="text">
                                </div>
                            </div>
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
                            <div class="input-field col s12 m6">
                                <input id="password" name="password" type="password">
                                <label for="password" data-error="Debe ingresar una contrase&ntilde;a">Contrase&ntilde;a</label>
                            </div>
                            <div class="input-field col s12 m6">
                                <input id="confirm-password" type="password">
                                <label for="confirm-password" data-error="Debe volver a ingresar la contrase&ntilde;a">Confirmar contrase&ntilde;a</label>
                            </div>
                            <label id="error-message" class="red-text">&nbsp;</label>
                            <br><br>
                            <a id="cancel" class="btn-flat blue-text waves-effect">Cancelar</a>
                            <a id="register-button" class="btn blue waves-effect waves-light right">Registrarse</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <?php require "../include/scripts.html"; ?>

        <script>
            $( "#register-button" ).click(function() {
                // if ($( "#image-path" ).val() == "")
                // {
                //     $( "#error-message" ).html("Debe seleccionar una imagen");
                // }
                /*else*/ if ($( "#user" ).val() == "")
                {
                    $( "#user" ).addClass("validate invalid")/*.focus()*/;
                }
                /*else*/ if ($( "#name" ).val() == "")
                {
                    $( "#name" ).addClass("validate invalid")/*.focus()*/;
                }
                /*else*/ if ($( "#email" ).val() == "")
                {
                    $( "#email + label" ).attr( "data-error", "Debe ingresar un correo electronico" );
                    $( "#email" ).addClass("validate invalid")/*.focus()*/;
                }
                else if (!validateEmail($( "#email" ).val()))
                {
                    $( "#email + label" ).attr( "data-error", "Debe ingresar un correo electronico valido" );
                    $( "#email" ).addClass("validate invalid")/*.focus()*/;
                }
                /*else*/ if ($( "#password" ).val() == "")
                {
                    $( "#password" ).addClass("validate invalid")/*.focus()*/;
                }
                /*else*/ if ($( "#confirm-password" ).val() == "")
                {
                    $( "#confirm-password" ).addClass("validate invalid")/*.focus()*/;
                }
                /*else*/ if ($( "#password" ).val() != $( "#confirm-password" ).val())
                {
                    $( "#error-message" ).html("Las contraseñas no coinciden");
                }
                else
                {
                    $.ajax({
                        url: "check.php",
                        type: "POST",
                        data: { User: $( "#user" ).val(), Email: $( "#email" ).val() } ,
                        success: function (response) {
                            if (response == '0')
                                $( "#register" ).submit();
                            else if (response == '1')
                                $( "#error-message" ).html("Ya esta en uso ese usuario, debe elegir otro");
                            else if (response == '2')
                                $( "#error-message" ).html("Ya esta en uso ese email, debe usar otro");
                        },
                        error: function(jqXHR, textStatus, errorThrown) {
                            console.log(textStatus, errorThrown);
                        }
                    });
                }
            });

            $( "input" ).on('input', function() {
                $( "#error-message" ).html("&nbsp;");

                if ($(this).val() != "")
                    $(this).removeClass("validate valid invalid");
            }).keypress(function(e) {
                if(e.which == 13) {
                    $( "#register-button" ).trigger( "click" );
                }
            });

            $( "#cancel" ).click(function() {
                window.history.back();
            });

            function validateEmail(email) {
                var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
                return re.test(email);
            }
        </script>
    </body>
</html>