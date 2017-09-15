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
var selectedElement = $("#content");
var dragging = false;

$(document).ready(function() {
    $("#sideNav-button").sideNav();
    $(".collapsible").collapsible();
    initDialogs();
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

function createWrapper($inner) {
    var $newElement = $("<div></div>").html($inner);
    $newElement.click(function(e) {
        showDropdown(e, this);
    });
    $newElement.draggable({
        snap: true,
        scroll: false,
    });
    $newElement.attr("id", "object-" + nextID++);
    $newElement.css({
        "width": $inner.attr("width") + "px",
        "height": $inner.attr("height") + "px",
        "float": "left"
    })
    $newElement.on({
        "mousedown" : function() {
            if(!dragging) {
                selectElement($(this));
                dragging = true;
            }
        },
        "mouseup" : function() {
            dragging = false;
        }
    });
    $newElement.appendTo($("#content"));
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

    var $element = $(elementTypes["image"].tag).attr(parameter_attributes);
    emptyParameters("#image-create-dialog");
    createWrapper($element);
}

function selectElement($element) {
    unselectElement();
    selectedElement = $element;
    $element.css("border", "20px solid blue");
}

function unselectElement() {
    $selectedElement.css("border", "none");
    selectedElement = $("#content");
}

function editButtonClick() {

}

function openEditPageDialog() {

}