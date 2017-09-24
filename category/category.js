var category_tree = {};

$(document).ready(function() {
    readFile();
});

// loads the tree into memory
function readFile() {
    $.getJSON("categories.json", function(data) {
        category_tree = data;
    });
}

// Saves the tree into the server
function writeFile() {
    $.ajax({
        url: "UploadCategories.php",
        type: "POST",
        data: { categories: JSON.stringify(category_tree) } ,
        success: function(data) {
            console.log(data);
        },
        error: function(jqXHR, textStatus, errorThrown) {
            console.log(textStatus, errorThrown);
        }
    });
}

// Returns an array with the children of a node
function getChildren(category) {
    return category_tree[category].children;
}

// Returns a string with the parent of a node
function getParent(category) {
    return category_tree[category].parent;
}

// Only used in getAbsolutePath
function getAbsolutePath_helper(category, path_so_far) {
    var parent = category_tree[category].parent;
    if(parent == "root" || parent == "none")
        return path_so_far;
    path_so_far = parent + ">" + path_so_far;
    return getAbsolutePath_helper(parent, path_so_far);
}

// Returns the absolute path of a category, for example:
// Technology>Programming>Javascript
function getAbsolutePath(category) {
    return getAbsolutePath_helper(category, category);
}

// Adds a category to the category tree
function addCategory(name, parent_name = "root") {
    if(!category_tree[parent_name])
        throw "Parent category doesn't exist";
    else if(!category_tree[name]) {
        category_tree[name] = {
            parent: parent_name,
            children: []
        };
        category_tree[parent_name].children.push(name);
    }
    else
        throw "Category already exists";
}