var textCreateEditor;
var textEditEditor;

var pageContent;

var nextID = 0;
var $content = $("#content");
var $selectedElement = $content;
var creatingImage = false;
var changeMade = false;

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
        e.preventDefault();
        e.stopPropagation();

        unselectElement();

        var contextMenuID = "#content-dropdown";

        if(!$(contextMenuID).hasClass("active"))
            $(".dropdown-button").dropdown("close");

        $(contextMenuID + "-activator").dropdown("open");

        $(contextMenuID).css({
            display: "block",
            left: e.pageX,
            top: e.pageY
        });
    });

    loadPage();
    changeMade = false;

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

    setInterval(function() {
        if(changeMade)
            savePage(0);
        changeMade = false;
    }, 5000);

    $( "#shared-users" ).on("click", ".delete-shared-user", function() {
        var UserCode = $(this).attr("id").split('-')[1];

        $.ajax({
            url: "delete_editor.php",
            type: "POST",
            data: { PostID: PostID, UserCode: UserCode } ,
            success: function (response) {
                if (response == '1') {
                    $( "#" + UserCode ).next().remove();
                    $( "#" + UserCode ).remove();

                    if ($( "#shared-users" ).children().length == 1)
                        $( "#shared-users" ).empty();
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.log(textStatus, errorThrown);
            }
        });
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

    loadEditors();

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

    $("#custom-create-dialog").modal({
        dismissible: true,
        endingTop: '50%',
        complete: function() {
            $("#custom-create-code").val('').trigger("autoresize");
            $("#video-create-preview").attr("src", "");
            onDialog = false;
        }
    });

    $("#custom-create-button").click(function() {
        createCustom($("#custom-create-code"));
    })
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
    var Data = $("#share-page-users").material_chip("data");
    var Users = [];

    for (var i = 0; i < Data.length; i++)
        Users.push(Data[i].tag);

    $.ajax({
        url: "add_editors.php",
        type: "POST",
        data: { ID: PostID, Users: Users } ,
        success: function (response) {
            if (response == '1') {
                $( "#share-page-users .chip" ).remove();
                $( "#shared-users" ).empty();
                loadEditors();
            }
        },
        error: function(jqXHR, textStatus, errorThrown) {
            console.log(textStatus, errorThrown);
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

            if(tmpImg.width > $content.width())
                $("#image-create-message").show();
            else
                $("#image-create-message").hide();


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

    $("#image-create-message").hide();

    $("#image-create-width, #image-create-height").html("200");
    $("#image-create-preview > img").attr("src", "no_image_selected.gif");
}

function updateVideoPreview() {
    var $embedCode = $($("#video-create-embed-link").val()).attr("src");
    $("#video-create-preview").attr("src", $embedCode);
}

function updateCustomPreview() {
    var code = $("#custom-create-code").val();
    $("#custom-create-preview").html(code);
    var aspectRatio = $("#custom-create-size").val() / 100;
    $("#custom-create-preview").css("width", (aspectRatio * 300) + "px");
    $("#custom-create-preview").css("height", ((1 - aspectRatio) * 300) + "px");
}

function createImage(src, old=false, other_css={}) {
    var attributes = {
        "src": src,
        "data-type": "image",
        "data-snap": "true",
        "data-aspect-ratio": "true"
    };
    if(!old) {
        var width = parseInt($("#image-create-width").html());
        var height = parseInt($("#image-create-height").html());
        if(width > $content.width()) {
            var css = {
                "width": $content.width() + "px",
                "height": ($content.width() / width) * height + "px"
            };
        }
        else {
            var css = {
                "width": $("#image-create-width").html() + "px",
                "height": $("#image-create-height").html() + "px"
            };
        }
    }
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

// Code: Custom Creation

function createCustom(code, old=false, extra_css={}) {
    var $custom = $(code);
    var attr = {
        "data-type": "custom",
        "data-snap": "true"
    };
    if(!old) {
        var aspectRatio = $("#custom-create-size").val() / 100;
        var css = {
            "width":  (aspectRatio * 300) + "px",
            "height": ((1 - aspectRatio) * 300) + "px"
        };
    }
    else
        var css = {
            "width": $custom.css("width"),
            "height": $custom.css("height")
        };
    $custom.css(extra_css)
    .attr(attr)
    .css(css)
    .addClass("inner");
    if(old) {
        $custom.attr("data-old", "true");
    }
    createWrapper($custom);
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
        containment: "#content",
        start: function() {
            changeMade = true;
            $(".dropdown-button").dropdown("close");
            selectElement($(this));
        }
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
        "position": "absolute"
    });

    if($inner.data("type") != "text") {
        $newElement.append($("<div id='handle-" + id + "' class='handle ui-resizable-handle ui-resizable-se'></div>")).resizable({
            handles: {
                "se": "#handle-" + id
            },
            aspectRatio: $newElement.width() / $newElement.height(),
            start: function() {
                changeMade = true;
                $(".dropdown-button").dropdown("close");

                var maxWidth = $content.width() - $newElement.position().left;
                $newElement.css("max-width", maxWidth);
                $newElement.css("max-height", maxWidth / ($newElement.width() / $newElement.height()));
            }
        });

        $newElement.children(".handle").hide();
    }

    if($inner.data("type") == "video" || $inner.data("type") == "custom") {
        $newElement.append("<div class='overlay'></div>");
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
            e.stopPropagation();
            $(".dropdown-button").dropdown("close");
            selectElement($(this));
        },
        "contextmenu" : function(e) {
            e.preventDefault();
            e.stopPropagation();

            var $target = $(e.target);
            while(!$target.hasClass("object"))
                $target = $target.parent();

            selectElement($target);

            var type = $target.data("type");
            var contextMenuID = "#" + type + "-dropdown";

            if(!$(contextMenuID).hasClass("active"))
                $(".dropdown-button").dropdown("close");

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
        $selectedElement.addClass("selected-element");
        $selectedElement.children(".handle").show();
    }
}

function unselectElement() {
    if($selectedElement[0].id != "#content") {
        $selectedElement.removeClass("selected-element");
        $selectedElement.children(".handle").hide();
    }
    $selectedElement = $content;
}

function toggleSnap($element) {
    $element.attr("data-snap", !($element.attr("data-snap") == "true"));
    $element.draggable("option", "snap", !$element.draggable("option", "snap"));
}

function toggleAspectRatio($element) {
    $element.attr("data-aspect-ratio", !($element.attr("data-aspect-ratio") == "true"));
    if($element.resizable("option", "aspectRatio") != false)
        $element.resizable("option", "aspectRatio", false);
    else
        $element.resizable("option", "aspectRatio", $element.width() / $element.height());
}

function removeElement($element) {
    removeLayer($element);
    $element.remove();
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
    var pos = parseInt($element.css("z-index")) - 1;
    layerOrder.splice(pos, 1);
    updateZIndeces(pos, layerOrder.length);
}

function updateZIndeces(lower_limit, upper_limit) {
    for(var i = lower_limit; i < upper_limit; i++)
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

function takeBack($element) {
    var pos = parseInt($element.css("z-index")) - 1;
    if(pos > 0) {
        var original = layerOrder[pos];

        var tmp1 = layerOrder[0];
        var tmp2 = layerOrder[1];

        for(var i = 1; i <= pos; i++) {
            tmp2 = layerOrder[i];
            layerOrder[i] = tmp1;
            tmp1 = tmp2;
        }

        layerOrder[0] = original;
        updateZIndeces(0, pos + 1);
        changeMade = true;
    }
}

function bringFront($element) {
    var pos = parseInt($element.css("z-index")) - 1;
    if(pos < layerOrder.length) {
        var original = layerOrder[pos];

        var tmp1 = layerOrder[layerOrder.length - 1];
        var tmp2 = layerOrder[layerOrder.length - 2];

        for(var i = layerOrder.length - 1; i >= pos; i--) {
            tmp2 = layerOrder[i];
            layerOrder[i] = tmp1;
            tmp1 = tmp2;
        }

        layerOrder[layerOrder.length - 1] = original;
        updateZIndeces(pos, layerOrder.length);
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
            case "custom":
                $("#custom-create-size").val(($clipboard.outerWidth() / $clipboard.outerHeight())) // Tengo que arreglar esto
                createCustom($clipboard.children(".inner").clone());
                $("#custom-create-size").val(50);
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
                    savePage(1);
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
            case "custom":
                createCustom($element, true, {
                    "z-index": $element.css("z-index"),
                    "left": $element.css("left"),
                    "top": $element.css("top"),
                    "width": $element.css("width"),
                    "height": $element.css("height")
                });
                break;
            default:
                console.log("No type detected");
        }
    });
}

// Code: Saving

function savePage(userSaved) {
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
                    "data-extension": $inner.data("extension")
                }).css({
                    "width": $inner.width() + "px",
                    "height": $inner.height() + "px"
                });
                break;
            case "text":
                var $elem = $inner.children().clone();
                pageTranscript += $elem.text() + " ";
                break;
            case "video":
                var $elem = $inner.clone().attr({
                    "width": $(this).width() + "px",
                    "height": $(this).height() + "px"
                }).css({
                    "width": $(this).width() + "px",
                    "height": $(this).height() + "px"
                });
                break;
            case "custom":
                var $elem = $inner.clone().css({
                    "width": $(this).width() + "px",
                    "height": $(this).height() + "px"
                });
                break;
        }
        $elem.css({
            "position": "absolute",
            "z-index": $(this).css("z-index"),
            "left": ($(this).offset().left - $content.offset().left) + "px",
            "top": ($(this).offset().top - $content.offset().top) + "px"
        }).attr({
            "data-type": type,
            "data-old": "true"
        });
        var bottomPos = $(this).position().top + $(this).outerHeight(true);
        if(bottomPos > maxHeight)
            maxHeight = bottomPos;
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
                if (userSaved)
                    Materialize.toast('Guardado correctamente', 4000);
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.log(textStatus, errorThrown);
            }
        });
}

function toggleDebugSave() {
    debugSaveEnabled = !debugSaveEnabled;
    console.log("Debug save set to " + debugSaveEnabled);
}

function loadEditors() {
    $.ajax({
        url: "get_editors.php",
        type: "POST",
        data: { ID: PostID } ,
        success: function (response) {
            if (response != "") {
                $( "#shared-users" ).append('<div class="divider col s12"></div>');

                var SharedUsers = JSON.parse(response);

                for (Entry in SharedUsers) {
                    var ID = Entry;
                    var Name = SharedUsers[ID].Name;
                    var Email = SharedUsers[ID].Email;
                    var Image = "../images/users/" + ID + '.' + SharedUsers[ID].Extension;

                    addSharedUser(ID, Name, Email, Image);
                }
            }
        },
        error: function(jqXHR, textStatus, errorThrown) {
            console.log(textStatus, errorThrown);
        }
    });
}

function addSharedUser(ID, Name, Email, Image) {
    $( "#shared-users" ).append(
        '<div id="' + ID + '" class="col s12 valign-wrapper shared-user-wrapper">' +
            '<img src="' + Image + '" class="circle shared-user-image">' +
            '<p class="shared-user-text">' +
                '<strong>' + Name + '</strong>' +
                '<br>' +
                Email +
            '</p>' +
            '<i id="delete-' + ID + '" class="material-icons delete-shared-user">close</i>' +
        '</div>' +
        '<div class="divider col s12"></div>'
    );
}