<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">

        <title>Edit page</title>

        <?php require "../include/head.html"; ?>

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
            <div id="image-create-dialog" class="modal valign-modal">
                <div class="modal-content row">
                    <h4>Crear Imagen</h4>
                    <div class="valign-wrapper">
                        <div id="image-create-preview" class="valign-wrapper">
                            <img src="no_image_selected.gif" />
                        </div>
                        <p><strong>Width:</strong><br><span id="image-create-width">200</span>px<br><br><strong>Height:</strong><br><span id="image-create-height">200</span>px</p>
                    </div>
                    <div class="input-field col s12">
                        <input id="image-create-src" type="url" data-content="src" onchange="updatePreview('create')" class="input">
                        <label for="image-create-url">Source</label>
                    </div>
                </div>
                <div class="modal-footer">
                    <a href="#!" class="modal-action modal-close waves-effect waves-light btn-flat" class="image-create-cancel">Cancelar</a>
                    <a href="#!" class="modal-action modal-close waves-effect waves-light btn-flat" id="image-create-button">Crear</a>
                </div>
            </div>
        </div>



            <!--

                <div class="center-align">
                    <div class="preview">
                        <div id="image-create-preview" class="valign-wrapper">
                            <img src="no_image_selected.gif" />
                        </div>
                    </div>
                    <div class="col s6 parameters">
                        <div class="input-field">
                            <input id="image-create-url" type="url" data-content="src" onchange="updatePreview('create')" class="input">
                            <label for="image-create-url">Source</label>
                        </div>
                        <div class="input-field">
                            <input id="image-create-width" type="number" data-content="width" class="input validate">
                            <label for="image-create-width">Width</label>
                        </div>
                        <div class="input-field">
                            <input id="image-create-height" type="number" data-content="height" class="input validate">
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
        -->

        <div id="content">

        </div>

        <?php require "../include/scripts.html"; ?>

        <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
        <script src="page_editor.js"></script>
    </body>
</html>