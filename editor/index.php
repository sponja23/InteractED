<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">

        <title>Test</title>

        <?php require "../include/head.html"; ?>

        <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.min.css">
        <link rel="stylesheet" href="../css/input.css">
        <link rel="stylesheet" href="../css/font.css">
        <link rel="stylesheet" href="page_editor.css">
    </head>
    <body>
        <nav>
            <div class="nav-wrapper white">
                <ul id="nav-mobile" class="left">
                    <li><a href="#" data-activates="slide-out" id="sideNav-button" style="float: left;"><i class="material-icons blue-text">menu</i></a></li>
                </ul>
                <a href="../" class="logo blue-text">InteractED</a>
            </div>
        </nav>

        <ul id="slide-out" class="side-nav">
            <li><div class="flow-text" style="text-align: center; padding: 5px 0">Edit Page</div></li>
            <li class="no-padding">
                <ul class="collapsible collapsible-accordion">
                    <li>
                        <a class="collapsible-header">Crear...<i class="material-icons">arrow_drop_down</i></a>
                        <div class="collapsible-body">
                            <ul>
                                <li><a id="#create-text" class="waves-effect waves-light" onclick="openCreateDialog('text')">Crear Texto</a></li>
                                <li><a id="#create-image" class="waves-effect waves-light" onclick="openCreateDialog('image')">Crear Imagen</a></li>
                                <li><a id="#create-video" class="waves-effect waves-light" onclick="openCreateDialog('video')">Crear Video</a></li>
                                <li><a id="#create-custom" class="waves-effect waves-light" onclick="openCreateDialog('custom')">Crear Custom</a></li>
                            </ul>
                        </div>
                    </li>
                </ul>
            </li>
        </ul>

        <div id="dialogs">
            <div id="image-create-dialog" class="modal">
                <div class="modal-content">
                <div class="row">
                    <h2>Crear Imagen</h2>
                </div>
                <div class="row valign-wrapper center-align">
                    <div class="col s6 preview">
                        <img src="http://www.allensguide.com/img/no_image_selected.gif" width="200px" height="200px"/>
                    </div>
                    <div class="col s6 parameters">
                        <div class="input-field">
                            <input id="image-create-url" type="text" data-content="src" class="input">
                            <label for="image-create-url">Source</label>
                        </div>
                        <br>
                        <div class="input-field">
                            <input id="image-create-width" type="text" data-content="width" class="input">
                            <label for="image-create-width">Width</label>
                        </div>
                        <br>
                        <div class="input-field">
                            <input id="image-create-height" type="text" data-content="height" class="input">
                            <label for="image-create-height">Height</label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <a href="#!" class="modal-action modal-close waves-effect waves-light btn-flat" class="image-create-cancel">Cancelar</a>
                    <a href="#!" class="modal-action modal-close waves-effect waves-light btn-flat" id="image-create-button">Crear</a>
                </div>
            </div>
        </div>

        <div id="content">

        </div>

        <?php require "../include/scripts.html"; ?>

        <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
        <script src="page_editor.js"></script>
    </body>
</html>