<?php
	session_start();
    if(!isset($_SESSION["UserCode"]))
        header("Location: ../");
    else {
    	include "../include/connect.php";
    	if($_SESSION["Level"] > 1)
    		$sql = "SELECT * FROM Articles WHERE MD5(PostID) = '" . $_GET["id"] . "'";
    	else
	        $sql = "SELECT A.* FROM Articles A
	                INNER JOIN EditorRelation ER ON A.PostID = ER.PostID
	                INNER JOIN Users U ON ER.UserCode = U.UserCode
	                WHERE MD5(A.PostID) = '" . $_GET["id"] . "' AND U.UserCode = " . $_SESSION["UserCode"];
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $_SESSION[$_GET["id"] . "-Title"] = $row["Title"];
                $_SESSION[$_GET["id"] . "-Category"] = $row["Category"];
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

        <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.min.css">
        <link rel="stylesheet" href="../include/colorpicker/materialize-colorpicker.min.css">
        <link rel="stylesheet" href="../css/input.css">
        <link rel="stylesheet" href="../css/font.css">
        <link rel="stylesheet" href="page_editor.css">
    </head>
    <body>
        <nav>
            <div id="side-nav" class="nav-wrapper white">
                <ul id="nav-mobile" class="left">
                    <li><a href="#" data-activates="slide-out" id="side-nav-button" style="float: left;"><i class="material-icons blue-text">menu</i></a></li>
                </ul>
                <a href="../" class="logo blue-text">InteractED</a>
            </div>
        </nav>

        <ul id="slide-out" class="side-nav">
            <li><div class="flow-text" style="text-align: center; padding: 5px 0">Edit Page</div></li>
            <li><a id="edit-page" class="waves-effect waves-light" onclick="openEditPageDialog()">Propiedades de la página</a></li>
            <li class="no-padding">
                <ul class="collapsible collapsible-accordion">
                    <li>
                        <a class="collapsible-header">Crear...<i class="material-icons">arrow_drop_down</i></a>
                        <div class="collapsible-body">
                            <ul>
                                <li><a id="create-text" class="waves-effect waves-light" onclick="openCreateDialog('text')">Crear Texto</a></li>
                                <li><a id="create-image" class="waves-effect waves-light" onclick="openCreateDialog('image')">Crear Imagen</a></li>
                                <li><a id="create-video" class="waves-effect waves-light" onclick="openCreateDialog('video')">Crear Video</a></li>
                                <li><a id="create-custom" class="waves-effect waves-light" onclick="openCreateDialog('custom')">Crear Custom</a></li>
                            </ul>
                        </div>
                    </li>
                </ul>
            </li>
        </ul>
        
        <div class="fixed-action-btn" onclick="editButtonClick()">
            <a href="#" class="btn-floating btn-large blue darken-2">
                <i class="large material-icons">settings</i>
            </a>
            <ul id="btn-list">
                <li id="btn-edit"><a class="btn-floating blue darken-3"><i class="material-icons">mode_edit</i></a></li>
                <li id="btn-remove"><a class="btn-floating blue darken-3"><i class="material-icons">delete</i></a></li>
            </ul>
        </div>
        
        <div id="dialogs">
            <div id="edit-page-dialog" class="modal valign-modal">
                <div class="modal-content row">
                    <h4>Editar propiedades de página</h4>
                    <br><br>
                    <div style="display: block; margin: 0 auto; padding: 0 10%">
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
                </div>
                <div class="modal-footer">
                    <a href="#!" class="modal-action modal-close waves-effect waves-light btn-flat" id="edit-page-cancel">Cancelar</a>
                    <a href="#!" class="modal-action modal-close waves-effect waves-light btn-flat" id="edit-page-button">Guardar</a>
                </div>
            </div>
            <div id="image-create-dialog" class="modal valign-modal">
                <div class="modal-content row">
                    <h4>Crear Imagen</h4>
                    <div class="valign-wrapper">
                        <div id="image-create-preview" class="valign-wrapper">
                            <img src="no_image_selected.gif" />
                        </div>
                        <p id="image-create-lengths"><strong>Width:</strong><br><span id="image-create-width" class="input" data-parameter="width">200</span>px<br><br><strong>Height:</strong><br><span id="image-create-height" class="input" data-parameter="height">200</span>px</p>
                    </div>
                    <div class="input-field col s12">
                        <input id="image-create-src" type="url" onchange="updatePreview()" class="input" data-parameter="src">
                        <label for="image-create-src">Source</label>
                    </div>
                </div>
                <div class="modal-footer">
                    <a href="#!" class="modal-action modal-close waves-effect waves-light btn-flat" id="image-create-cancel">Cancelar</a>
                    <a href="#!" class="modal-action modal-close waves-effect waves-light btn-flat" id="image-create-button">Crear</a>
                </div>
            </div>
            <div id="image-edit-dialog" class="modal valign-modal">
                <div class="modal-content row">
                    <h4>Editar Imagen</h4>

                </div>
                <div class="modal-footer">
                    <a href="#!" class="modal-action modal-close waves-effect waves-light btn-flat" id="image-edit-cancel">Cancelar</a>
                    <a href="#!" class="modal-action modal-close waves-effect waves-light btn-flat" id="image-edit-button">Guardar</a>
                </div>
            </div>
            <div id="text-create-dialog" class="modal valign-modal">
                <div class="modal-content row">
                    <h4>Crear Texto</h4>
                    <div id="text-create-properties" class="section row">
                        <ul class="collapsible popout row s12" data-collapsible="accordion">
                            <li>
                                <a class="collapsible-header">Borde<i class="material-icons">arrow_drop_down</i></a>
                                <div class="collapsible-body row">
                                    <div class="input-field col s4">
                                        <select id="text-create-border-style">
                                            <option value="none" selected>Ninguno</option>
                                            <option value="solid">Sólido</option>
                                            <option value="dotted">Punteado</option>
                                        </select>
                                        <label for="text-create-border-style">Estilo</label>
                                    </div>
                                    <div class="input-field col s4">
                                        <input id="text-create-border-color" type="text" />
                                        <label for="text-create-border-color">Color</label>
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
                    <a href="#!" class="modal-action modal-close waves-effect waves-light btn-flat" id="text-create-cancel">Cancelar</a>
                    <a href="#!" class="modal-action modal-close waves-effect waves-light btn-flat" id="text-create-button">Crear</a>
                </div>
            </div>
        </div>

        <div id="content" class="container" data-type="content" onclick="if(!dragging) unselectElement();">

        </div>

        <?php require "../include/scripts.html"; ?>

        <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
        <script src="../include/textboxio/textboxio.js"></script>
        <script src="../include/colorpicker/materialize-colorpicker.min.js"></script>
        <script src="../category/category.js"></script>
        <script>
        	var pageName = <?= "\"" . $_SESSION[$_GET["id"] . "-Title"] . "\""?>;
            var pageCategory = <?= "\"" . $_SESSION[$_GET["id"] . "-Category"] . "\""?>;
            var postID = <?= "\"" . $_GET["id"] . "\"" ?>;
            var pageContent = <?= "'". $PageContent . "'" ?>;
        </script>
        <script src="page_editor.js"></script>

    </body>
</html>