<!DOCTYPE html>
<html>
    <head>
        <title>InteractED</title>

        <?php require "../include/head.html"; ?>

        <link rel="stylesheet" href="../components/navigation/navigation.css">

        <style>
            #dropdown1 {
                position: absolute;
            }
        </style>
    </head>
    <body class="grey lighten-5">
        <?php require "../components/navigation/navigation.php"; ?>

        <div class="container">
            <div id="target2">
                <img id="aa" src="http://www.jqueryscript.net/images/Tiny-jQuery-Modal-Extension-For-Materialize-Framework-materializeMessages-js.jpg">
            </div>
        </div>

        <a id="imageDropdown-activator" class="dropdown-button hide" data-activates="imageDropdown" data-constrainWidth="false"></a>

        <ul id="imageDropdown" class="dropdown-content">
            <li><a href="#!" class="blue-text"><i class="material-icons blue-text">delete</i>Borrar im&aacute;gen</a></li>
        </ul>

        <?php require "../include/scripts.html"; ?>

        <script src="../components/navigation/navigation.js"></script>

        <script>
            $( ".container" ).on("contextmenu", function(e) {
                e.stopPropagation();

                var id = $( e.target ).attr( "id" );

                var dropdown = "#imageDropdown";

                var $contextMenu = $( dropdown );

                $( dropdown + "-activator" ).dropdown( "open" );

                $contextMenu.css({
                    display: "block",
                    left: e.pageX,
                    top: e.pageY
                });

                event.preventDefault();
            });
        </script>
    </body>
</html>