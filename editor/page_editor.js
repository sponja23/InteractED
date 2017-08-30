// TODO: fix draggable

var elementTypes = {};
elementTypes["image"] = {
    name: "image",
    tag: "<img>",
    createDialogID: "#image_create_dialog",
    createDialogOptions: {
        autoOpen: false,
        resizable: false,
        modal: true,
        title: "Crear Imagen",
        height: 500,
        width: 720,
        buttons: {
            "Crear": function() {
                createElement("image");
                $(this).dialog("close");
            }
        }
    },
    editDialogID: "#image_edit_dialog",
    editDialogOptions: {
        autoOpen: false,
        resizable: false,
        modal: true,
        title: "Editar Imagen",
        height: 500,
        width: 700,
        buttons: {
            "Listo": function() {
                saveElement("image");
                $(this).dialog("close");
            }
        }
    }
}

var nextID = 0;
var currentlyEditing = $("body");

$(document).ready(function() {
    $("#sideNav-button").sideNav();
    $(".collapsible").collapsible();
    initDialogs();
});

function initDialogs() {
    for(var type in elementTypes) {
        if(elementTypes.hasOwnProperty(type)) {
            $(elementTypes[type].createDialogID).dialog(elementTypes[type].createDialogOptions);
            $(elementTypes[type].editDialogID).dialog(elementTypes[type].editDialogOptions);
        }
    }
}

function openCreateDialog(type) {
    $(elementTypes[type].createDialogID).dialog("open");
    $(".button-collapse").sideNav("hide");
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
    $newElement.appendTo($("#content"));
}

function createElement(type) {
    var attributes = {};
    $(elementTypes[type].createDialogID + " > .parameters .input").each(function(){
        attributes[$(this).data("content")] = $(this).val();
    });
    attributes["data-type"] = elementTypes[type].name;
    var $element = $(elementTypes[type].tag).attr(attributes);
    console.log("Created image: ");
    for(var attribute in attributes) {
        console.log(attribute + ": " + attributes[attribute])
    }
    console.log($element.attr("width"));
    createWrapper($element);
}

function openEditDialog($object) {
    currentlyEditing = $object;
    var type = $object.data("type");
    $(elementTypes[type].editDialogID).dialog("open");
}

function saveEdit() {

}

function showDropdown(eventArgs, $father) {
    var start_x = e.pageX;
    var start_y = e.pageY;
}