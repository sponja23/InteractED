<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">

		<title>Test</title>

		<?php require "../include/head.html"; ?>

		<link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.min.css">
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
                                <li><a id="#create_text" class="waves-effect waves-light" onclick="openCreateDialog('text')">Crear Texto</a></li>
                                <li><a id="#create_image" class="waves-effect waves-light" onclick="openCreateDialog('image')">Crear Imagen</a></li>
                                <li><a id="#create_video" class="waves-effect waves-light" onclick="openCreateDialog('video')">Crear Video</a></li>
                                <li><a id="#create_custom" class="waves-effect waves-light" onclick="openCreateDialog('custom')">Crear Custom</a></li>
                            </ul>
                        </div>
                    </li>
                </ul>
            </li>
        </ul>

        <div id="dialogs">
            <div id="image_create_dialog">
                <div class="preview">
                    <img class="image" src="http://www.allensguide.com/img/no_image_selected.gif" width="200px" height="200px">
                </div>
                <div class="parameters">
                    <p class="flow-text param-title">Source: <input class="input src" data-content="src" type="text" name="src"></p>
                    <p class="flow-text param-title">Width: <input class="input width" data-content="width" type="text" name="width"></p>
                    <p class="flow-text param-title">Height: <input class="input height" data-content="height" type="text" name="height"></p>
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