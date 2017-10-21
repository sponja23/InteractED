var category = {

    tree: {},

    // loads the tree into memory
    loadTree: function() {
        $.getJSON("/InteractED/category/categories.json", function(data) {
            category.tree = data;
        });
    },


    // Saves the tree into the server
    saveTree: function() {
        $.ajax({
            url: "/InteractED/category/UploadCategories.php",
            type: "POST",
            data: { categories: JSON.stringify(category.tree) } ,
            success: function(data) {
                console.log(data);
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.log(textStatus, errorThrown);
            }
        });
    },

    // Returns an array with the children of a node
    getChildren: function(name) {
        return category.tree[name].children;
    },


    // Returns a string with the parent of a node
    getParent: function(name) {
        return category.tree[name].parent;
    },

    // Only used in getAbsolutePath
    getAbsolutePath_helper: function(name, path_so_far) {
        var parent = category.tree[name].parent;
        if(parent == "root" || parent == "none")
            return path_so_far;
        path_so_far = parent + ">" + path_so_far;
        return category.getAbsolutePath_helper(parent, path_so_far);
    },

    // Returns the absolute path of a category, for example:
    // Technology>Programming>Javascript
    getAbsolutePath: function(name) {
        return category.getAbsolutePath_helper(name, name);
    },


    // Adds a category to the category tree
    addCategory: function(name, parent_name = "root") {
        if(!category.tree[parent_name])
            throw "Parent category doesn't exist";
        else if(!category.tree[name]) {
            category.tree[name] = {
                parent: parent_name,
                children: []
            };
            category.tree[parent_name].children.push(name);
        }
        else
            throw "Category already exists";
    },

    getCategories: function() {
        var result = [] 
        for(var cat in category.tree)
            if(category.tree.hasOwnProperty(cat))
                result.push(cat);
        return result;
    },

    categoryExists: function(name) {
        return category.tree.hasOwnProperty(name);
    }
}