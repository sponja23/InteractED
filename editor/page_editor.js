var elementTypes = {};
elementTypes["image"] = {
    name: "image",
    tag: "<img>",
    attributes: {
        "src" : "input",
        "width" : "label",
        "height" : "label"
    },
    createDialogClose: function() {
        console.log("close create");
        emptyParameters("#image-create-dialog");
        invalidateImage("create");
    },
    editDialogClose: function() {
        console.log("close edit");
    }
}

var nextID = 0;
var $selectedElement = $("#content");
var dragging = false, clicking_object = false;

$(document).ready(function() {
    $("#content").css({
        "height" : ($(window).height() - $("#side-nav").height()) + "px",
        "width" : "100%"
    });
    $("#side-nav-button").sideNav();
    $(".collapsible").collapsible();
    initDialogs();
});

$(window).on("resize", function() {
    $("#content").css("height", ($(window).height() - $("#side-nav").height()) + "px");
});

function initDialogs() {
    $("#edit-page-dialog").modal({
        dismissible: true,
        endingTop: "50%"
    });
    for(var type in elementTypes) {
        if(elementTypes.hasOwnProperty(type)) {
            $("#" + elementTypes[type].name + "-create-dialog").modal({
                dismissible: true,
                endingTop: '50%',
                complete: function() {
                    elementTypes[type].createDialogClose();
                }
            });
            $("#" + elementTypes[type].name + "-edit-dialog").modal({
                dismissible: true,
                endingTop: '50%',
                complete: function() {
                    elementTypes[type].editDialogReady();
                }
            });
            $("#" + elementTypes[type].name + "-create-button").click(function(){
                createElement(elementTypes[type].name);
            });
            $("#" + elementTypes[type].name + "-edit-button").click(function(){
                saveEdit();
            });
        }
    }
    
}

function updatePreview(modalType) {
    var $url = $("#image-" + modalType + "-src");
    var $preview = $("#image-" + modalType + "-preview > img");

    var tmpImg = new Image();
    tmpImg.src = $url.val();

    $(tmpImg).one('load', function() {
        if (tmpImg.width == 0 || tmpImg.height == 0)
            invalidateImage(modalType);
        else {
            $url.removeClass("invalid").addClass("valid");
            $preview.attr("src", $url.val());

            $("#image-create-width").html(tmpImg.width);
            $("#image-create-height").html(tmpImg.height);

            if(tmpImg.width > tmpImg.height)
                $preview.addClass("adjust-width").removeClass("adjust-height");
            else
                $preview.addClass("adjust-height").removeClass("adjust-width");
        }
    });

    $(tmpImg).one("error", function() {
        invalidateImage(modalType);
    });
}

function invalidateImage(modalType) {
    $url = $("#image-" + modalType + "-src");

    if($url.val() == "")
        $url.removeClass("invalid").removeClass("valid");
    else
        $url.removeClass("valid").addClass("invalid");

    $("#image-" + modalType + "-width, #image-" + modalType + "-height").html("200");
    $("#image-" + modalType + "-preview > img").attr("src", "no_image_selected.gif");
}

function emptyParameters(id) {
    $(id + " .input").each(function(){
        $(this).val('').blur().removeClass("valid invalid");
    });
}

function openCreateDialog(type) {
    $("#" + elementTypes[type].name + "-create-dialog").modal("open");
    $(".sideNav-button").sideNav("hide");
}

function createElement(type) {
    var parameter_attributes = { "data-type" : elementTypes["image"].name };

    $("#" + elementTypes[type].name + "-create-dialog .input").each(function() {
        var value;
        switch(elementTypes[type].attributes[$(this).data("parameter")]) {
            case "input":
                value = $(this).val();
                break;
            case "label":
                value = $(this).html();
                break;
            default:
                value = 0;
        }
        if(value != 0)
            parameter_attributes[$(this).data("parameter")] = value;
        else
            alert("Parameter error");
    });

    var $element = $(elementTypes["image"].tag).attr(parameter_attributes).addClass("inner");
    emptyParameters("#image-create-dialog");
    createWrapper($element);
}

function createWrapper($inner) {
    var $newElement = $("<div></div>").html($inner);
    $newElement.draggable({
        snap: true,
        scroll: false,
        containment: "#content"
    });
    $newElement.attr("id", "object-" + nextID++);
    $newElement.css({
        "width": $inner.attr("width") + "px",
        "height": $inner.attr("height") + "px",
        "float": "left",
        "position": "absolute !important"
    });
    $newElement.children().css({
        "width": "100%",
        "height": "100%"
    });
    $newElement.on({
        "click" : function(e) {
            selectElement($(e.target));
            e.stopPropagation();
        },
        "mousedown" : function(e) {
            if(!dragging) {
                selectElement($(e.target));
                dragging = true;
                e.stopPropagation();
            }
        },
        "mouseup" : function(e) {
            dragging = false;
        }
    });
    $newElement.appendTo($("#content"));
}
function selectElement($element) {
    unselectElement();
    console.log("selecting element");
    if($selectedElement.attr("id") != $element.attr("id")) {
        $selectedElement = $element;
        $selectedElement.addClass("selected");
    }
}

function unselectElement() {
    console.log("unselecting");
    if($selectedElement[0].id != "#content")
        $selectedElement.removeClass("selected");
    $selectedElement = $("#content");
}

function editButtonClick() {
    if($selectedElement[0].id != "#content")
        openEditDialog($selectedElement.data("type"));
    else
        openEditPageDialog();
}

function openEditDialog(type) {
    console.log("Editing " + type + ": " + $selectedElement.attr("id"));
}

function openEditPageDialog() {
    console.log("Editing page");
}