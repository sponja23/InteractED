var elementTypes = {};
elementTypes["image"] = {
    name: "image",
    tag: "<img>"
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
            $("#" + elementTypes[type].name + "-create-dialog").modal({
                dismissible: true,
                endingTop: '50%'
            });
            $("#" + elementTypes[type].name + "-edit-dialog").modal({
                dismissible: true
            });
            $("#" + elementTypes[type].name + "-create-button").click(function(){
                createElement(elementTypes[type].name);
            });
            $("#" + elementTypes[type].name + "-edit-button").click(function(){
                saveEdit();
            });
            $("." + elementTypes[type].name + "-create-cancel").click(function(){
                emptyParameters("#" + elementTypes[type].name + "-create-dialog");
            });
            $("." + elementTypes[type].name + "-edit-cancel").click(function(){
                emptyParameters("#" + elementTypes[type].name + "-edit-dialog");
            });

        }
    }
}

function emptyParameters(id) {
    $(id + " .parameters .input").each(function(){
        $(this).val('').blur();
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
    $newElement.appendTo($("#content"));
}

function createElement(type) {
    var attributes = {};
    $("#" + elementTypes[type].name + "-create-dialog .parameters .input").each(function(){
        attributes[$(this).data("content")] = $(this).val();
    });
    attributes["data-type"] = elementTypes[type].name;
    var $element = $(elementTypes[type].tag).attr(attributes);
    console.log("Created image: ");
    for(var attribute in attributes) {
        console.log(attribute + ": " + attributes[attribute])
    }
    emptyParameters("#" + elementTypes[type].name + "-create-dialog");
    createWrapper($element);
}



function openEditDialog($object) {
    currentlyEditing = $object;
    var type = $object.data("type");
    $("#" + elementTypes[type].name + "-edit-dialog").modal("open");
}

function saveEdit() {

}

function showDropdown(eventArgs, $father) {
    var start_x = e.pageX;
    var start_y = e.pageY;
}