<?php
    session_start();
    if(!isset($_SESSION["UserCode"]))
        header("Location: ../");
    else {
        include "../include/connect.php";

        $sql = 'SELECT A.Title, A.LastEditDate, C.CategoryName FROM Articles A
                LEFT JOIN EditorRelation ER ON A.PostID = ER.PostID
                INNER JOIN Categories C ON A.CategoryID = C.CategoryID
                WHERE MD5(A.PostID) = "' . $_GET["id"] . '" AND
                (A.CreatorID = ' . $_SESSION["UserCode"] . ' OR ER.UserCode = ' . $_SESSION["UserCode"] . ' OR ' .
                $_SESSION["Level"] . ' >= 1)';

        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $_SESSION[$_GET["id"] . "-Title"] = $row["Title"];
                $_SESSION[$_GET["id"] . "-Category"] = $row["CategoryName"];
                $PageContent = "";
                if(!is_null($row["LastEditDate"])) {
                    $PageContent = file_get_contents("../post/content/" . $_GET["id"] . "/index.html");
                }
            }
        }
        else {
            header("Location: ../");
        }
        $conn->close();
    }
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Editar <?= $_SESSION[$_GET["id"] . "-Title"]?></title>

        <?php require "../include/head.html"; ?>

        <link rel="stylesheet" href="/InteractED/include/external/jquery-ui.min.css">
        <link rel="stylesheet" href="../include/colorpicker/materialize-colorpicker.min.css">
        <link rel="stylesheet" href="../css/input.css">
        <link rel="stylesheet" href="../css/font.css">
        <link rel="stylesheet" href="page_editor.css">
    </head>
    <body>
        <nav>
            <div id="side-nav" class="nav-wrapper white">
                <a href="../" class="logo blue-text">InteractED</a>
                <ul class="right">
                    <li><a onclick="savePage()"><i class="material-icons blue-text">save</i></a></li>
                    <li><a class="btn blue waves-effect waves-light" style="padding: 0 15px;"><i class="material-icons left" style="margin-right: 10px;">link</i>Compartir</a></li>
                </ul>
            </div>
        </nav>
        
        <a id="content-dropdown-activator" class="dropdown-button hide" data-activates="content-dropdown" data-constrainWidth="false"></a> 
        <a id="text-dropdown-activator" class="dropdown-button hide" data-activates="text-dropdown" data-constrainWidth="false"></a>
        <a id="image-dropdown-activator" class="dropdown-button hide" data-activates="image-dropdown" data-constrainWidth="false"></a>

        <ul id="content-dropdown" class="dropdown-content">
            <li>
                <a class="blue-text" onclick="openCreateDialog('text')">
                    <i class="material-icons blue-text">format_shapes</i>
                    Crear texto
                </a>
            </li>
            <li>
                <a class="blue-text" onclick="openCreateDialog('image')">
                    <i class="material-icons blue-text">photo</i>
                    Crear imagen
                </a>
            </li>
            <li>
                <a class="blue-text" onclick="openCreateDialog('video')">
                    <i class="material-icons blue-text">ondemand_video</i>
                    Crear video
                </a>
            </li>
            <li>
                <a class="blue-text" onclick="openCreateDialog('custom')">
                    <i class="material-icons blue-text">code</i>
                    Crear personalizado
                </a>
            </li>
            <li class="divider"></li>
            <li>
                <a class="blue-text" onclick="openEditPageDialog()">
                    <i class="material-icons blue-text">settings</i>
                    Propiedades de la pagina
                </a>
            </li>
        </ul>

        <ul id="text-dropdown" class="dropdown-content">
            <li>
                <a class="blue-text toggle">
                    <i class="material-icons blue-text" data-option="snap"></i>
                    Alinear con objetos
                </a>
            </li>
            <li>
                <a class="blue-text" onclick="openEditDialog('text')">
                    <i class="material-icons blue-text">edit</i>
                    Editar
                </a>
            </li>
            <li class="divider"></li>
            <li>
                <a class="blue-text" onclick="removeSelectedElement()">
                    <i class="material-icons blue-text">delete</i>
                    Borrar
                </a>
            </li>
        </ul>

        <ul id="image-dropdown" class="dropdown-content">
            <li>
                <a class="blue-text toggle">
                    <i class="material-icons blue-text" data-option="snap"></i>
                    Alinear con objetos
                </a>
            </li>
            <li>
                <a class="blue-text toggle">
                    <i class="material-icons blue-text" data-option="deform"></i>
                    Mantener proporciones
                </a>
            </li>
            <li class="divider"></li>
            <li>
                <a class="blue-text" onclick="removeSelectedElement()">
                    <i class="material-icons blue-text">delete</i>
                    Borrar
                </a>
            </li>
        </ul>

        <div class="fixed-action-btn">
            <a class="btn-floating btn-large blue darken-2">
                <i class="material-icons">add</i>
            </a>
            <ul>
                <li class="valign-wrapper" onclick="openCreateDialog('text')">
                    <a class="btn-floating blue darken-3 mobile-fab-button"><i class="material-icons">format_shapes</i></a>
                    <a class="btn-floating blue darken-3 mobile-fab-tip">Crear texto</a>
                </li>
                <li class="valign-wrapper" onclick="openCreateDialog('image')">
                    <a class="btn-floating blue darken-3 mobile-fab-button"><i class="material-icons">photo</i></a>
                    <a class="btn-floating blue darken-3 mobile-fab-tip">Crear imagen</a>
                </li>
                <li class="valign-wrapper" onclick="openCreateDialog('video')">
                    <a class="btn-floating blue darken-3 mobile-fab-button"><i class="material-icons">ondemand_video</i></a>
                    <a class="btn-floating blue darken-3 mobile-fab-tip">Crear video</a>
                </li>
                <li class="valign-wrapper" onclick="openCreateDialog('custom')">
                    <a class="btn-floating blue darken-3 mobile-fab-button"><i class="material-icons">code</i></a>
                    <a class="btn-floating blue darken-3 mobile-fab-tip">Crear personalizado</a>
                </li>
            </ul>
        </div>
        
        <div id="dialogs">
            <div id="edit-page-dialog" class="modal valign-modal">
                <div class="modal-content row">
                    <div class="col s12">
                        <h5 style="margin-bottom: 20px;">Editar propiedades de p&aacute;gina</h5>
                    </div>
                    <div class="input-field col s12">
                        <input id="edit-page-name" type="text" class="input">
                        <label for="edit-page-name">Nombre</label>
                    </div>
                    <div class="input-field col s6">
                        <input id="edit-page-category" type="text" class="input autocomplete">
                        <label for="edit-page-category">Categoría</label>
                    </div>
                    <div class="input-field col s6">
                        <input id="edit-page-height" type="number" class="input" min="0">
                        <label for="edit-page-height">Altura</label>
                    </div>
                </div>
                <div class="modal-footer">
                    <a class="modal-action modal-close waves-effect waves-light btn-flat" id="edit-page-cancel">Cancelar</a>
                    <a class="modal-action modal-close waves-effect waves-light btn-flat" id="edit-page-button">Guardar</a>
                </div>
            </div>
            <div id="image-create-dialog" class="modal valign-modal">
                <div class="modal-content row">
                    <h5>Crear imagen</h5>
                    <div class="valign-wrapper">
                        <div id="image-create-preview" class="valign-wrapper">
                            <img src="no_image_selected.gif" />
                        </div>
                        <p id="image-create-lengths">
                            <strong>Ancho:</strong>
                            <br>
                            <span id="image-create-width" class="input">200</span>px
                            <br><br>
                            <strong>Alto:</strong>
                            <br>
                            <span id="image-create-height" class="input" data-parameter="height">200</span>px
                        </p>
                    </div>
                    <div class="input-field col s12">
                        <input id="image-create-src" type="url" onchange="updatePreview('url')" class="input">
                        <label for="image-create-src">URL</label>
                    </div>
                    <span>O</span>
                    <div class="file-field input-field">
                        <div class="btn blue">
                            <span>Archivo</span>
                            <input type="file" name="image" id="image-create-upload-file">
                        </div>
                        <div class="file-path-wrapper">
                            <input id="image-create-upload-src" onchange="updatePreview('upload')" class="file-path input" type="text">
                        </div>
                    </div>
                    <span id="image-create-error" class="red-text"></span>
                </div>
                <div class="modal-footer">
                    <a class="modal-action modal-close waves-effect waves-light btn-flat" id="image-create-cancel">Cancelar</a>
                    <a class="modal-action modal-close waves-effect waves-light btn-flat" id="image-create-button">Crear</a>
                </div>
            </div>
            <div id="text-create-dialog" class="modal valign-modal">
                <div class="modal-content row">
                    <h5 style="margin-bottom: 20px;">Crear texto</h5>
                    <div id="text-create-properties">
                        <ul class="collapsible" data-collapsible="accordion">
                            <li>
                                <div class="collapsible-header">Borde<i class="material-icons">arrow_drop_down</i></div>
                                <div class="collapsible-body row" style="padding: 1rem;">
                                    <div id="colorpicker-wrapper" class="file-field col s4">
                                        <div id="color-box" class="btn" style="margin-top: 15px;"></div>
                                        <div class="file-path-wrapper input-field">
                                            <input id="text-create-border-color" type="text" />
                                        </div>
                                    </div>
                                    <div class="input-field col s4">
                                        <select id="text-create-border-style">
                                            <option value="" disabled>Choose your option</option>
                                            <option value="none" selected>Ninguno</option>
                                            <option value="solid">S&oacute;lido</option>
                                            <option value="dotted">Punteado</option>
                                        </select>
                                        <label>Estilo</label>
                                    </div>
                                    <div class="input-field col s4">
                                        <input id="text-create-border-width" type="number" value='0' min='0' max='12'/>
                                        <label for="text-create-border-width">Grosor</label>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                    <div class="row">
                        <textarea id="text-create-content" class="col s12" style="height: 250px"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <a class="modal-action modal-close waves-effect waves-light btn-flat" id="text-create-cancel">Cancelar</a>
                    <a class="modal-action modal-close waves-effect waves-light btn-flat" id="text-create-button">Crear</a>
                </div>
            </div>
            <div id="text-edit-dialog" class="modal valign-modal">
                <div class="modal-content row">
                    <h5 style="margin-bottom: 20px;">Editar texto</h5>
                    <div class="row">
                        <textarea id="text-edit-content" class="col s12" style="height: 250px"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <a class="modal-action modal-close waves-effect waves-light btn-flat" id="text-edit-cancel">Cancelar</a>
                    <a class="modal-action modal-close waves-effect waves-light btn-flat" id="text-edit-button">Crear</a>
                </div>
            </div>
        </div>

        <div id="content" class="container" data-type="content" onclick="unselectElement();" style="position: relative;">

        </div>

        <?php require "../include/scripts.html"; ?>

        <script src="/InteractED/include/external/jquery-ui.min.js"></script>
        <script src="../include/textboxio/textboxio.js"></script>
        <script src="../include/colorpicker/materialize-colorpicker.min.js"></script>
        <script>
            var pageName = <?= "\"" . $_SESSION[$_GET["id"] . "-Title"] . "\""?>;
            var pageCategory = <?= "\"" . $_SESSION[$_GET["id"] . "-Category"] . "\""?>;
            var postID = <?= "\"" . $_GET["id"] . "\"" ?>;
            var pageContent = <?= "'". $PageContent . "'" ?>;
        </script>
        <script src="page_editor.js"></script>

    </body>
</html>