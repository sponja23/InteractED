<?php
	session_start();
    if(!isset($_SESSION["UserCode"]))
        header("Location: ../");
    else {
    	include "../include/connect.php";
    	if($_SESSION["Level"] > 1)
    		$sql = "SELECT * FROM Articles WHERE PostID = " . $_GET["id"];
    	else
	        $sql = "SELECT A.* FROM Articles A
	                INNER JOIN EditorRelation ER ON A.PostID = ER.PostID
	                INNER JOIN Users U ON ER.UserCode = U.UserCode
	                WHERE A.PostID = " . $_GET["id"] . " AND U.UserCode = " . $_SESSION["UserCode"];
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $_SESSION[$_GET["id"] . "-Title"] = $row["Title"];
                $_SESSION[$_GET["id"] . "-Category"] = $row["Category"];
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
        
        <div class="fixed-action-btn" onclick="editButtonClick(); savePage()">
            <a href="#" class="btn-floating btn-large blue">
                <i class="large material-icons">mode_edit</i>
            </a>
        </div>
        
        <div id="dialogs">
            <div id="edit-page-dialog" class="modal valign-modal">
                <div class="modal-content row">
                    <h4>Editar propiedades de página</h4>
                    <div class="input-field col s6">
                        <input id="page-edit-name" type="text" class="input">
                        <label for="page-edit-name">Nombre</label>
                    </div>
                    <div class="input-field col s6">
                        <input id="page-edit-category" type="text" class="autocomplete">
                        <label for="page-edit-category">Categoría</label>
                    </div>
                </div>
                <div class="modal-footer">
                    <a href="#!" class="modal-action modal-close waves-effect waves-light btn-flat" id="page-edit-cancel">Cancelar</a>
                    <a href="#!" class="modal-action modal-close waves-effect waves-light btn-flat" id="page-edit-done">Guardar</a>
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
                        <input id="image-create-src" type="url" onchange="updatePreview('create')" class="input" data-parameter="src">
                        <label for="image-create-url">Source</label>
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
                    
                </div>
                <div class="modal-footer">
                    <a href="#!" class="modal-action modal-close waves-effect waves-light btn-flat" id="text-create-cancel">Cancelar</a>
                    <a href="#!" class="modal-action modal-close waves-effect waves-light btn-flat" id="text-create-button">Crear</a>
                </div>
            </div>
        </div>

        <div id="content" class="container" onclick="if(!dragging) unselectElement();">

        </div>

        <?php require "../include/scripts.html"; ?>

        <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
        <script src="../include/textboxio/textboxio.js"></script>
        <script src="../category/category.js"></script>
        <script>
        	var pageName = <?= "\"" . $_SESSION[$_GET["id"] . "-Title"] . "\""?>;
            var pageCategory = <?= "\"" . $_SESSION[$_GET["id"] . "-Category"] . "\""?>;
        </script>
        <script src="page_editor.js"></script>

    </body>
</html>