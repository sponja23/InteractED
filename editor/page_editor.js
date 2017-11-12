var categories;
var textCreateEditor;
var textEditEditor;

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
var onDialog = false;

var $clipboard = null;

var layerOrder = [];

$(document).ready(function() {
    if(oldPost)
        $.ajax({
            url: "load_page.php",
            type: "POST",
            data: { ID : PostID },
            success: function(content) {
                pageContent = content;
                var numObjects = $(pageContent).children().length;
                for(var i = 0; i < numObjects; i++) {
                    layerOrder.push(null);
                }
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

    loadPage();

    $(document).keydown(function(e) {
        processKey(e);
    });

    $(".collapsible").collapsible();

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
        opacity: 0.5,
        complete: function() {
            onDialog = false;
        }
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
            onDialog = false;
        }
    });

    $.ajax({
        url: "get_editors.php",
        type: "POST",
        async: true,
        dataType: "json",
        data: { 
            id: PostID
        },
        success: function(editors) {
            for(var i = 0; i < editors.length; i++)
                $("<div class='chip'>" + editors[i] + "<i class='material-icons close'>close</i></div>").insertBefore('input[placeholder="Agregar editores"]');
        }
    });

    $("#share-page-users").material_chip({
        placeholder: "Agregar editores"
    });

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
            onDialog = false;
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
            onDialog = false;
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
                items: [ 'undo', 'style', 'format', 'emphasis', 'align', 'listindent', 'tools' ]
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
            onDialog = false;
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

    // Video Dialog

    $("#video-create-dialog").modal({
        dismissible: true,
        endingTop: '50%',
        complete: function() {
            $("#video-create-dialog .input").each(function(){
                $(this).val('').blur().removeClass("valid invalid");
            });
            $("#video-create-preview").attr("src", "");
            onDialog = false;
        }
    });

    $("#video-create-button").click(function() {
        createVideo($("#video-create-embed-link").val());
    });
}

// Code: Page edit

function openDialog(id) {
    $(id).modal("open");
    onDialog = true;
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
    onDialog = true;
}

function sharePage() {
    var data = $("#share-page-users").material_chip("data");
    var users = [];
    for(var i = 0; i < data.length; i++)
        users.push(data[i].tag);
    console.log(users);
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
    var Layer = $element.css("z-index");
    var $inner = $element.children(".inner");
    $inner.css({
        "left": $element.css("left"),
        "top": $element.css("top")
    });
    var id = parseInt($element.attr("id").slice($element.attr("id").lastIndexOf('-') + 1));
    console.log(id);
    $content.append($inner);
    removeElement($selectedElement);
    $new_text.css("display", "block");
    var $new_content = $("<div></div>").append($new_text).addClass("inner-content").css("display", "inline-block");
    $inner.children(".inner-content").replaceWith($new_content);
    $inner.css({
        "width": $new_content.width() + "px",
        "height": $new_content.height() + "px",
        "z-index": Layer
    });
    $inner.attr({
        "data-old": "true"
    })
    createWrapper($inner, id);
}

// Code: Video Creation

function createVideo(embed_code, old=false, extra_css={}) {
    var $video = $(embed_code);
    var attr = {
        "data-type": "video",
        "data-snap": "true"
    };
    if(!old)
        var css = {
            "width": "368px",
            "height": "207px"
        }
    else
        var css = {
            "width": $video.attr("width"),
            "height": $video.attr("height")
        }
    $video.css(extra_css)
    .attr(attr)
    .css(css)
    .addClass("inner");
    if(old) {
        $video.attr("data-old", "true");
    }
    createWrapper($video);
}

// Code: Wrapper

function createWrapper($inner, idToUse = -1) {

    var $newElement = $("<div></div>").append($inner);

    var old = $inner.data("old");

    var id = idToUse == -1 ? nextID : idToUse;

    if(old)
        var Layer = $inner.css("z-index");

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

    if($inner.data("type") == "video") {
        $newElement.append("<div class='overlay'><i class='material-icons large blue-text'>open_with</i></div>");
        $inner.attr({
            "width": "100%",
            "height": "100%"
        })
    }


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

    if(old) {
        $newElement.css({
            "left": "0px",
            "top": "0px"
        });
        $newElement.css({
            "left": parseInt($inner.css("left").slice(0, $inner.css("left").lastIndexOf("px"))) - $newElement.offset().left + $content.offset().left,
            "top": parseInt($inner.css("top").slice(0, $inner.css("top").lastIndexOf("px"))) - $newElement.offset().top + $content.offset().top
        });
        $inner.css({
            "left": "",
            "top": ""
        });
        if(Layer != "auto")
            giveLayer($newElement, parseInt(Layer));
        else
            giveLayer($newElement);
    }
    else {
        giveLayer($newElement);
    }
    
    $inner.css("z-index", 1);

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

function removeElement($element) {
    console.log($element);
    var id = $element.attr("id");
    removeLayer($element);
    savePositions(id);
    $element.remove();
    resetPositions(id);
    changeMade = true;
}

// Code: z Index

function giveLayer($element, index = -1) {
    if(index == -1) {
        layerOrder.push("#" + $element.attr("id"));
        $element.css("z-index", layerOrder.length);
    }
    else {
        layerOrder[index - 1] = "#" + $element.attr("id");
        $element.css("z-index", index);
    }
}

function removeLayer($element) {
    layerOrder.splice(parseInt($element.css("z-index")) - 1, 1);
    updateZIndeces();
}

function updateZIndeces() {
    for(var i = 0; i < layerOrder.length; i++)
        $(layerOrder[i]).css("z-index", i + 1);
}

function pullForward($element) {
    var pos = parseInt($element.css("z-index")) - 1;
    if(pos != layerOrder.length - 1) {

        var tmp = layerOrder[pos];
        layerOrder[pos] = layerOrder[pos + 1];
        layerOrder[pos + 1] = tmp;

        $element.css("z-index", pos + 2);
        $(layerOrder[pos]).css("z-index", pos + 1);
        changeMade = true;
    }
}

function pushBackwards($element) {
    var pos = parseInt($element.css("z-index")) - 1;
    if(pos > 0) {

        var tmp = layerOrder[pos];
        layerOrder[pos] = layerOrder[pos - 1];
        layerOrder[pos - 1] = tmp;

        $element.css("z-index", pos);
        $(layerOrder[pos]).css("z-index", pos + 1);
        changeMade = true;
    }
}

// Code: Key movement

var directions = {
    left: 0,
    up: 1,
    right: 2,
    down: 3
};

function moveElement($element, direction, amount) {
    switch(direction) {
        case directions.left:
            var element_left = $element.offset().left;
            var content_left = $content.offset().left;
            if(element_left > content_left)
                if(element_left - amount < content_left)
                    $element.css({ left: "-=" + (content_left - element_left) + "px" });
                else
                    $element.css({ left: "-=" + amount + "px" });
            break;
        case directions.up:
            var element_top = $element.offset().top;
            var content_top = $content.offset().top;
            if(element_top > content_top)
                if(element_top - amount < content_top)
                    $element.css({ top: "-=" + (content_top - element_top) + "px" });
                else
                    $element.css({ top: "-=" + amount + "px" });
            break;
        case directions.right:
            var element_right = $element.offset().left + $element.outerWidth();
            var content_right = $content.offset().left + $content.outerWidth();
            if(element_right < content_right)
                if(element_right + amount > content_right)
                    $element.css({ left: "+=" + (content_right - element_right) + "px" });
                else
                    $element.css({ left: "+=" + amount + "px" });
            break;
        case directions.down:
            var element_bottom = $element.offset().top + $element.outerHeight();
            var content_bottom = $content.offset().top + $content.outerHeight();
            if(element_bottom < content_bottom)
                if(element_right + amount > content_right)
                    $element.css({ top: "+=" + (content_bottom - element_bottom) + "px" });
                else
                    $element.css({ top: "+=" + amount + "px" });
            break;
    }
    changeMade = true;
}

// Code: Copy/Paste

function copyElement($element) {
    $clipboard = $element.clone();
}

function pasteElement() {
    if($clipboard != null) {
        switch($clipboard.data("type")) {
            case "image":
                $("#image-create-width").html($clipboard.outerWidth());
                $("#image-create-height").html($clipboard.outerHeight());
                createImage($clipboard.children(".inner").attr("src"));
                $("#image-create-width").html("200");
                $("#image-create-height").html("200");
                break;
            case "text":
                createText($clipboard.find(".inner-content").clone());
                break;
            case "video":
                createVideo($clipboard.children(".inner").clone());
                break;
        }
    }
}

// Code: Key Processing

function processKey(event) {
    if(!onDialog)
        switch(event.which) {
            case 46:
                // Delete
                removeElement($selectedElement);
                break;
            case 37:
                // Arrow left
                if(event.shiftKey)
                    moveElement($selectedElement, directions.left, 20);
                else
                    moveElement($selectedElement, directions.left, 5);
                break;
            case 38:
                // Arrow up
                if(event.shiftKey)
                    moveElement($selectedElement, directions.up, 20);
                else
                    moveElement($selectedElement, directions.up, 5);
                break;
            case 39:
                // Arrow right
                if(event.shiftKey)
                    moveElement($selectedElement, directions.right, 20);
                else
                    moveElement($selectedElement, directions.right, 5);
                break;
            case 40:
                // Arrow down
                if(event.shiftKey)
                    moveElement($selectedElement, directions.down, 20);
                else
                    moveElement($selectedElement, directions.down, 5);
                break;
            case 83:
                // 's'
                if(event.ctrlKey) {
                    savePage();
                    event.preventDefault();
                }
                break;
            case 67:
                // 'c'
                if(event.ctrlKey) {
                    copyElement($selectedElement);
                    event.preventDefault();
                }
                break;
            case 86:
                // 'v'
                if(event.ctrlKey) {
                    pasteElement();
                    event.preventDefault();
                }
                break;
            case 88:
                // 'x'
                if(event.ctrlKey) {
                    copyElement($selectedElement);
                    removeElement($selectedElement);
                    event.preventDefault();
                }
        }
}

// Code: Loading

function loadPage() {
    $(pageContent).children().each(function(){
        $element = $(this);
        console.log("Loading " + $element.data("type"));
        console.log($element[0]);
        switch($element.data("type")) {
            case "image":
                createImage($element.attr("src"), true, {
                    "z-index": $element.css("z-index"),
                    "left": $element.css("left"),
                    "top": $element.css("top"),
                    "width": $element.css("width"),
                    "height": $element.css("height")
                });
                break;
            case "text":
                createText($element.children(), true, {
                    "z-index": $element.css("z-index"),
                    "left": $element.css("left"),
                    "top": $element.css("top")
                });
                break;
            case "video":
                createVideo($element, true, {
                    "z-index": $element.css("z-index"),
                    "left": $element.css("left"),
                    "top": $element.css("top")
                });
                break;
            default:

                console.log("No type detected");
        }
    });
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
                    "z-index": $(this).css("z-index"),
                    "width": $inner.width() + "px",
                    "height": $inner.height() + "px"
                });
                break;
            case "text":
                var $elem = $inner.children().clone().attr({
                    "data-type": "text",
                    "data-old": "true"
                }).css({
                    "z-index": $(this).css("z-index")
                });
                pageTranscript += $elem.text() + " ";
                break;
            case "video":
                var $elem = $inner.clone().attr({
                    "data-type": "video",
                    "data-old": "true",
                    "width": $(this).width() + "px",
                    "height": $(this).height() + "px"
                }).css({
                    "z-index": $(this).css("z-index"),
                    "width": $(this).width() + "px",
                    "height": $(this).height() + "px"
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