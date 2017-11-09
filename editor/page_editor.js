var categories;
var textCreateEditor;

var pageContent;

var nextID = 0;
var $content = $("#content");
var $selectedElement = $content;
var dragging = false;
var resizing = false;
var creatingImage = false;
var changeMade = false;
var saveInterval;
var positions;

var debugSaveEnabled = false;

$(document).ready(function() {
    if(oldPost)
        $.ajax({
            url: "load_page.php",
            type: "POST",
            data: { ID : PostID },
            success: function(content) {
                pageContent = content;
                console.log(pageContent);
                init();
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.log(textStatus, errorThrown);
            }
        });
    else
        init();
    
});

function init() {

    $content.css({
        "height" : ($(window).height() - $("#side-nav").height()) + "px"
    }).on("contextmenu", function(e) {
        e.stopPropagation();

        var contextMenuID = "#content-dropdown";

        $(contextMenuID + "-activator").dropdown("open");

        $(contextMenuID).css({
            display: "block",
            left: e.pageX,
            top: e.pageY
        });

        e.preventDefault();
    });

    $("#side-nav-button").sideNav();
    $(".collapsible").collapsible();

    $(pageContent).children().each(function(){
        $element = $(this);
        console.log("Loading " + $element.data("type"));
        console.log($element[0]);
        switch($element.data("type")) {
            case "image":
                createImage($element.attr("src"), true, {
                    "left": $element.css("left"),
                    "top": $element.css("top"),
                    "width": $element.css("width"),
                    "height": $element.css("height")
                });
                break;
            case "text":
                createText($element.children(), true, {
                    "left": $element.css("left"),
                    "top": $element.css("top")
                });
                break;
            case "video":
                createVideo($element, true, {
                    "left": $element.css("left"),
                    "top": $element.css("top")
                });
            default:
                console.log("No type detected");
        }
    });

    $.ajax({
        url: "../category/get_categories_with_images.php",
        type: "POST",
        async: true,
        context: this,
        dataType: "json",
        success: function(categories) {
            if(categories) {
                $(".autocomplete").autocomplete({
                    data: categories,
                    limit: 5,
                    minLength: 1
                });
            }
        },
        error: function(jqXHR, textStatus, errorThrown) {
            console.log(textStatus, errorThrown);
        }
    });

    initDialogs();

    saveInterval = setInterval(function() {
        if(changeMade)
            savePage();
        changeMade = false;
    }, 5000);
}

function savePositions(exception_id) {
    positions = {};
    $(".object").each(function() {
        var id = $(this).attr("id"); 
        if(id != exception_id) {
            positions[id] = $(this).offset();
        }
    });
}

function resetPositions(exception_id) {
    $(".object").each(function() {
        var id = $(this).attr("id");
        if(id != exception_id) {
            $(this).css({
                "left": "0px",
                "top": "0px"
            });
            console.log(positions[id].left - $(this).offset().left + $content.offset().left);
            $(this).css({
                "left": positions[id].left - $(this).offset().left,
                "top": positions[id].top - $(this).offset().top
            });
        }
    });
}

function initDialogs() {

    $("#colorpicker-wrapper").colorpicker({
        format: 'hex',
        component: '#color-box'
    });

    // Page Dialog

    $("#edit-page-dialog").modal({
        dismissible: true,
        endingTop: "50%",
        opacity: 0.5
    });

    $("#edit-page-name").val(pageName);
    $("#edit-page-category").val(pageCategory);
    $("#edit-page-height").val($content.height());

    $("#edit-page-button").click(editPage);

    // Share Page Dialog

    $("#share-page-dialog").modal({
    	dismissible: true,
    	endingTop: "50%",
    	complete: function() {

    	}
    });

    $("#share-page-users").material_chip();

    $("#share-page-button").click(function(e) {
    	sharePage();
    });

    // Image Dialog

    $("#image-create-dialog").modal({
        dismissible: true,
        endingTop: '50%',
        complete: function() {
            if(!creatingImage) {
                $("#image-create-dialog .input").each(function(){
                    $(this).val('').blur().removeClass("valid invalid");
                });
                invalidateImage();
            }
        } 
    });

    $("#image-create-button").click(function(e) {
        var $sourceInput = $("#image-create-src");
        var $uploadInput = $("#image-create-upload-src");
        var $errorMessage = $("#image-create-error");

        creatingImage = true;

        if($sourceInput.val() == "" && $uploadInput.val() == "") {
            $errorMessage.html("Debe ingresar una url o cargar una imagen");
        }
        else if($sourceInput.val() != "" && $uploadInput.val() != "") {
            $errorMessage.html("No puede ingresar ambas");
        }
        else if($sourceInput.val() != "") {
            createImage($sourceInput.val());
        }
        else {
            var resultingSource = "";
            var formData = new FormData();
            formData.append("image", $("#image-create-upload-file")[0].files[0]);
            $.ajax({
                url: "image_upload.php?id=" + PostID,
                type: "POST",
                processData: false,
                contentType: false,
                data: formData,
                success: function(result) {
                    createImage(result);
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log(textStatus, errorThrown);
                }
            });
        }

        e.preventDefault();
    });

    // Text Dialog

    $("#text-create-dialog").modal({
        dismissible: true,
        endingTop: '50%',
        complete: function() {
            textCreateEditor.content.set('');
            $("#text-create-dialog .collapsible").collapsible('close', 0);
            $("#text-create-border-style").val('none');
            $("#text-create-border-color").val('#000000');
            $("#text-create-border-width").val('0');
            
        }
    });

    $("#text-create-border-style").material_select();

    textCreateEditor = textboxio.replace("#text-create-content", {
        autosubmit: false,
        css : {
            stylesheets: [''],
            styles: [               
                { rule: 'p',    text: 'Párrafo' },
                { rule: 'h1',   text: 'Encabezado 1' },
                { rule: 'h2',   text: 'Encabezado 2' },
                { rule: 'h3',   text: 'Encabezado 3' },
                { rule: 'h4',   text: 'Encabezado 4' }
            ]
        },
        codeview: {
            enabled: true,
            showButton: true
        },
        images: {
            allowLocal : true
        },
        languages: ['en', 'es'],
        ui: {
            toolbar:  {
                items: [ 'undo', 'style', 'emphasis', 'align', 'listindent', 'format', 'tools' ]
            }
        }
    });

    $("#text-create-button").click(function() {
        createText($(textCreateEditor.content.get()));
    });

    $("#text-edit-dialog").modal({
        dismissible: true,
        endingTop: '50%',
        complete: function() {
            textEditEditor.content.set('');
        }
    });

    textEditEditor = textboxio.replace("#text-edit-content", {
        autosubmit: false,
        css : {
            stylesheets: [''],
            styles: [               
                { rule: 'p',  text: 'Párrafo' },
                { rule: 'h1', text: 'Encabezado 1' },
                { rule: 'h2', text: 'Encabezado 2' },
                { rule: 'h3', text: 'Encabezado 3' },
                { rule: 'h4', text: 'Encabezado 4' }
            ]
        },
        codeview: {
            enabled: true,
            showButton: true
        },
        images: {
            allowLocal : true
        },
        languages: ['en', 'es'],
        ui: {
            toolbar:  {
                items: [ 'undo', 'style', 'emphasis', 'align', 'listindent', 'format', 'tools' ]
            }
        }
    });

    $("#text-edit-button").click(function() {
        editText($selectedElement, $(textEditEditor.content.get()));
    });

    //$("#video-create-preview").css("margin-left", $("#video-create-dialog").width() - $(""));

    $("#video-create-dialog").modal({
        dismissible: true,
        endingTop: '50%',
        complete: function() {
            $("#video-create-dialog .input").each(function(){
                $(this).val('').blur().removeClass("valid invalid");
            });
            $("#video-create-preview").attr("src", "");
        }
    });

    $("#video-create-button").click(function() {
    	createVideo($("#video-create-embed-link").val());
    });
}

// Code: Page edit

function openEditPageDialog() {
    $("#edit-page-dialog").modal("open");
}

function editPage() {
    pageName = $("#edit-page-name").val();
    pageCategory = $("#edit-page-category").val();
    $content.height($("#edit-page-height").val());
    changeMade = true;
}

// Code: Add editors

function openSharePageDialog() {
	$("#share-page-dialog").modal("open");
}

function sharePage() {
	var data = $("#share-page-users").material_chip("data");
	var users = [];
	for(var chip in data)
		users.push(chip.tag);
	$.ajax({
		url: "add_editors.php",
		type: "POST",
		async: true,
		data: {
			users: users,
			id: PostID
		}
	});
}

// Code: Image creation

function updateImagePreview(method) {
    if(method == "url")
        var url = $("#image-create-src").val();
    else if(method == "upload")
        var url = URL.createObjectURL($("#image-create-upload-file")[0].files[0]);
    var $preview = $("#image-create-preview > img");
    var tmpImg = new Image();

    tmpImg.src = url;

    $(tmpImg).one('load', function() {
        if (tmpImg.width == 0 || tmpImg.height == 0)
            invalidateImage();
        else {
            if(method == "url")
                $("#image-create-src").removeClass("invalid").addClass("valid");

            $("#image-create-preview img").attr("src", url);

            $("#image-create-width").html(tmpImg.width);
            $("#image-create-height").html(tmpImg.height);

            if(tmpImg.width > tmpImg.height)
                $preview.addClass("adjust-width").removeClass("adjust-height");
            else
                $preview.addClass("adjust-height").removeClass("adjust-width");
        }
    });

    $(tmpImg).one("error", function() {
        invalidateImage();
    });
}

function invalidateImage() {
    $url = $("#image-create-src");

    if($url.val() == "")
        $url.removeClass("invalid").removeClass("valid");
    else
        $url.removeClass("valid").addClass("invalid");

    $("#image-create-width, #image-create-height").html("200");
    $("#image-create-preview > img").attr("src", "no_image_selected.gif");
}

function updateVideoPreview() {
	var $embedCode = $($("#video-create-embed-link").val()).attr("src");
	$("#video-create-preview").attr("src", $embedCode);
}

function openCreateDialog(type) {
    console.log("opening: #" + type + "-create-dialog");
    $("#" + type + "-create-dialog").modal("open");
    $(".sideNav-button").sideNav("hide");
}

function createImage(src, old=false, other_css={}) {
    var attributes = {
        "src": src,
        "data-type": "image",
        "data-snap": "true",
        "data-aspect-ratio": "true"
    };
    if(!old)
        var css = {
            "width": $("#image-create-width").html() + "px",
            "height": $("#image-create-height").html() + "px"
        };
    else
        var css = other_css;
    var $image = $("<img />")
    .attr(attributes)
    .css(css)
    .addClass("inner");
    if(old)
        $image.attr("data-old", "true");
    createWrapper($image);
    creatingImage = false;
    $("#image-create-dialog .input").each(function() {
        $(this).val('').blur().removeClass("valid invalid");
    });
    invalidateImage();
}

// Code: Text Creation

function createText($inner_text, old=false, extra_css={}) {
    var attributes = {
        "data-type": "text",
        "data-snap": true
    };;
    $inner_text.css("display", "block").attr(attributes);
    var $inner_content = $("<div></div>").append($inner_text).addClass("inner-content").css("display", "inline-block").attr(attributes);
    $content.append($inner_content);
    var css = {
        "border": $("#text-create-border-style").val() + " " + 
                  $("#text-create-border-color").val() + " " +
                  $("#text-create-border-width").val() + "px",
        "display": "inline-block",
        "padding": "0 10px",
        "width": $inner_content.width() + "px",
        "height": $inner_content.height() + "px"
    };
    var $text = $("<div></div>")
    .attr(attributes)
    .css(css)
    .append($inner_content)
    .addClass("inner");
    if(old) {
        $text.css(extra_css);
        $text.attr("data-old", "true");
    }
    createWrapper($text);
}

function editText($element, $new_text) {
    var $inner = $element.children(".inner");
    $inner.css({
        "left": $element.css("left"),
        "top": $element.css("top")
    });
    var id = parseInt($element.attr("id").slice($element.attr("id").lastIndexOf('-') + 1));
    console.log(id);
    $content.append($inner);
    removeSelectedElement();
    $new_text.css("display", "block");
    var $new_content = $("<div></div>").append($new_text).addClass("inner-content").css("display", "inline-block");
    $inner.children(".inner-content").replaceWith($new_content);
    $inner.css({
        "width": $new_content.width() + "px",
        "height": $new_content.height() + "px"
    });
    $inner.attr({
        "data-old": "true"
    })
    createWrapper($inner, id);
}

// Code: Video Creation

function createVideo(embed_code, old=false, extra_css={}) {
    var $video = $(embed_code);
    $video.css(extra_css)
    .attr("data-old", "true")
    .addClass("inner");
    createWrapper($video);
}

// Code: Wrapper

function createWrapper($inner, idToUse = -1) {

    var $newElement = $("<div></div>").append($inner);

    var id = idToUse == -1 ? nextID : idToUse;

    $newElement.draggable({
        snap: true,
        scroll: false,
        containment: "#content"
    });

    $newElement.attr({
        "id": "object-" + id,
        "data-type": $inner.data("type"),
        "data-snap": "true",
        "data-aspect-ratio": "true"
    });

    $newElement.css({
        "width": $inner.outerWidth() + "px",
        "height": $inner.outerHeight() + "px",
        "float": "left",
        "position": "absolute !important"
    });

    if($inner.data("type") != "text") {
        $newElement.append($("<div id='handle-" + id + "' class='handle ui-resizable-handle ui-resizable-se'></div>")).resizable({
            handles: {
                "se": "#handle-" + id
            },
            aspectRatio: $newElement.width() / $newElement.height(),
            start: function() {
                changeMade = true;
                savePositions($(this).attr("id"));
            },
            stop: function() {
                resetPositions($(this).attr("id"));
            }
        });

        $newElement.children(".handle").hide();
    }

    if($inner.data("type") == "video")
        $newElement.append("<div class='overlay'><i class='material-icons large'>open with</i></div>");


    $newElement.children(".inner").css({
        "width": "100%",
        "height": "100%"
    });

    $newElement.addClass("object " + $inner.data("type"));

    $newElement.on({
        "click" : function(e) {
            selectElement($(this));
            $("#" + $(this).data("type") + "-dropdown-activator").dropdown("close");
            e.stopPropagation();
        },
        "mousedown" : function(e) {
            if(!dragging) {
                selectElement($(this));
                dragging = true;
                e.stopPropagation();
            }
            if($(".handle:hover").length != 0)
                dragging = false;
            $("#" + $(this).data("type") + "-dropdown-activator").dropdown("close");
            changeMade = true;
        },
        "mouseup" : function(e) {
            dragging = false;
        },
        "contextmenu" : function(e) {
            e.stopPropagation();

            var $target = $(e.target);
            while(!$target.hasClass("object"))
            	$target = $target.parent();

            var type = $target.data("type");
            var contextMenuID = "#" + type + "-dropdown";

            $(".dropdown-button").each(function() {
                if($(this).attr("id") != contextMenuID + "-activator")
                    $(this).dropdown("close");
            });

            //alert($(e.target).data("snap"));

            $(contextMenuID + " li .toggle .material-icons").each(function() {
                var value = $target.attr("data-" + $(this).attr("data-option"));
                var icon = value == "true" ? "check_box" : "check_box_outline_blank";
                $(this).html(icon);
            });

            $(contextMenuID + "-activator").dropdown("open");

            $(contextMenuID).css({
                display: "block",
                left: e.pageX,
                top: e.pageY
            });

            e.preventDefault();
        }
    });

    $newElement.appendTo($content);
    
    if($inner.data("old")) {
        $newElement.css({
            "left": "0px",
            "top": "0px"
        });
        $newElement.css({
            "left": parseInt($inner.css("left").slice(0, $inner.css("left").lastIndexOf("px"))) - $inner.offset().left + $content.offset().left,
            "top": parseInt($inner.css("top").slice(0, $inner.css("top").lastIndexOf("px"))) - $inner.offset().top + $content.offset().top
        });
        $inner.css({
            "left": "",
            "top": ""
        });
    }
    
    selectElement($newElement);
    unselectElement();
    
    if(idToUse == -1)
        nextID++;

    changeMade = true;
}

// Code: Selection

function selectElement($element) {
    unselectElement();
    if($selectedElement.attr("id") != $element.attr("id")) {
        $selectedElement = $element;
        $selectedElement.addClass("selected");
        $selectedElement.children(".handle").show();
    }
}

function unselectElement() {
    if($selectedElement[0].id != "#content") {
        $selectedElement.removeClass("selected");
        $selectedElement.children(".handle").hide();
    }
    $selectedElement = $content;
}

function toggleSelectedSnap() {
	$selectedElement.attr("data-snap", !($selectedElement.attr("data-snap") == "true"));
	$selectedElement.draggable("option", "snap", !$selectedElement.draggable("option", "snap"));
}

function toggleSelectedAspectRatio() {
	$selectedElement.attr("data-aspect-ratio", !($selectedElement.attr("data-aspect-ratio") == "true"));
	if($selectedElement.resizable("option", "aspectRatio") != false)
		$selectedElement.resizable("option", "aspectRatio", false);
	else
		$selectedElement.resizable("option", "aspectRatio", $selectedElement.width() / $selectedElement.height());
}

function removeSelectedElement() {
    var id = $selectedElement.attr("id");
    savePositions(id);
    $selectedElement.remove();
    resetPositions(id);
    changeMade = true;
}

function openEditDialog(type) {
    console.log("opening: #" + type + "-edit-dialog");
    $("#" + type + "-edit-dialog").modal("open");
    $(".sideNav-button").sideNav("hide");
    switch(type) {
        case "text":
            textEditEditor.content.set($selectedElement.find(".inner-content").html());
    }
}

// Code: Saving

function savePage() {
    console.log("Saving...");
    var $newContent = $("<div id=content></div>");
    var maxHeight = 0;
    var pageTranscript = "";
    $content.children(".object").each(function() {
        var type = $(this).data("type");
        var id = $(this).attr("id");
        var $inner = $(this).children(".inner");
        switch(type) {
            case "image":
                var $elem = $("<img />").attr({
                    "src": $inner.attr("src"),
                    "data-type": $inner.data("type"),
                    "data-extension": $inner.data("extension"),
                    "data-old": "true"
                }).css({
                    "width": $inner.width() + "px",
                    "height": $inner.height() + "px"
                });
                console.log($elem.attr("src"));
                break;
            case "text":
                var $elem = $inner.children().clone().attr({
                    "data-type": "text",
                    "data-old": "true"
                });
                pageTranscript += $elem.text() + " ";
                break;
            case "video":
                var $elem = $inner.clone().attr({
                    "data-type": "video",
                    "data-old": "true",
                    "width": $inner.width() + "px",
                    "height": $inner.height() + "px"
                });
                break;
        }
        $elem.css({
            "position": "absolute",
            "left": ($(this).offset().left - $content.offset().left) + "px",
            "top": ($(this).offset().top - $content.offset().top) + "px"
        });
        var bottomPos = $(this).position().top + $(this).outerHeight(true);
        if(bottomPos > maxHeight)
            maxHeight = bottomPos;
        console.log(type + ": ");
        console.log($elem[0]);
        $newContent.append($elem);
    });

    $newContent.css({
        "position": "relative",
        //"width": $("#content").width() + "px",
        "height": maxHeight + "px"
    });

    var dataSaved = {
        id: PostID,
        content: $newContent[0].outerHTML,
        transcript: pageTranscript,
        name: pageName,
        category: pageCategory
    };

    if(debugSaveEnabled)
        console.log(dataSaved);
    else
        $.ajax({
            url: "save_page.php",
            type: "POST",
            data: dataSaved,
            success: function(result) {
                console.log(result);
                console.log("saved");
            },
            error: function() {
                console.log("saving error");
            }
        });
}

function toggleDebugSave() {
    debugSaveEnabled = !debugSaveEnabled;
    console.log("Debug save set to " + debugSaveEnabled);
}