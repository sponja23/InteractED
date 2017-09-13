var elementTypes = {};
elementTypes["image"] = {
    name: "image",
    tag: "<img>",
    attributes: ["src", "width", "height"],
    createDialogReady: function() {
        emptyParameters("#" + elementTypes["image"].name + "-create-dialog");
        invalidateImage();
    },
    createFunction: function() {
        createImage();
    },
    editDialogReady: function() {

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
            $("#" + elementTypes[type].name + "-create-dialog").modal({
                dismissible: true,
                endingTop: '50%',
                ready: function() {
                    elementType[type].createDialogReady();
                }
            });
            $("#" + elementTypes[type].name + "-edit-dialog").modal({
                dismissible: true,
                endingTop: '50%',
                ready: function() {
                    elementTypes[type].editDialogReady();
                }
            });
            $("#" + elementTypes[type].name + "-create-button").click(function(){
                elementTypes[type].createFunction();
            });
            $("#" + elementTypes[type].name + "-edit-button").click(function(){
                saveEdit();
            });
        }
    }
    
}

// function updatePreview(modalType) {
//     var $url = $("#image-" + modalType + "-url");
//     var $preview = $("#image-" + modalType + "-preview > img");
//     if($url.val().match(/\.(jpeg|jpg|gif|png|bmp|tiff)$/) != null) {
//         $preview.removeClass("adjust-width adjust-height");
//         $url.removeClass("invalid").addClass("valid");
//         $preview.attr("src", $url.val());

//         var tmpImg = new Image();
//         tmpImg.src = $url.val();

//         $(tmpImg).one('load',function(){
//             $("#image-" + modalType + "-dialog .width").html(tmpImg.width + "px");
//             $("#image-" + modalType + "-dialog .height").html(tmpImg.height + "px");

//             if(tmpImg.width > tmpImg.height)
//                 $preview.addClass("adjust-width");
//             else
//                 $preview.addClass("adjust-height");
//         });
//     }
//     else {
//         if($url.val() == "")
//             $url.removeClass("invalid").removeClass("valid");
//         else
//             $url.removeClass("valid").addClass("invalid");

//         $preview.attr("src", "no_image_selected.gif");
//     }
// }

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

    $("#image-" + modalType + "-preview > img").attr("src", "no_image_selected.gif");
}

function emptyParameters(id) {
    $(id + " .input").each(function(){
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

function createImage() {
    var attributes = {
        "src": $("#" + elementTypes["image"].name + "-create-src").val(),
        "width": $("#" + elementTypes["image"].name + "-create-width").html(),
        "height": $("#" + elementTypes["image"].name + "-create-height").html(),
        "data-type": elementTypes["image"].name
    };

    var $element = $(elementTypes["image"].tag).attr(attributes);
    emptyParameters("#" + elementTypes["image"].name + "-create-dialog");
    createWrapper($element);
}

function createElement(type) {
    var attributes = {};
    $("#" + elementTypes[type].name + "-create-dialog .input").each(function(){
        attributes[$(this).data("content")] = $(this).val();
    });
    attributes["data-type"] = elementTypes[type].name;
    var $element = $(elementTypes[type].tag).attr(attributes);
    console.log("Created image: ");
    for(var attribute in attributes)
        console.log(attribute + ": " + attributes[attribute])
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