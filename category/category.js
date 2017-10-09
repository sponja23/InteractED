var category = {

    tree: {},

    // loads the tree into memory
    loadTree: function() {
        $.getJSON("/InteractED/category/categories.json", function(data) {
            this.tree = data;
        });
    },


    // Saves the tree into the server
    saveTree: function() {
        $.ajax({
            url: "/InteractED/category/UploadCategories.php",
            type: "POST",
            data: { categories: JSON.stringify(this.tree) } ,
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
        return this.tree[name].children;
    },


    // Returns a string with the parent of a node
    getParent: function(name) {
        return this.tree[name].parent;
    },

    // Only used in getAbsolutePath
    getAbsolutePath_helper: function(name, path_so_far) {
        var parent = this.tree[name].parent;
        if(parent == "root" || parent == "none")
            return path_so_far;
        path_so_far = parent + ">" + path_so_far;
        return getAbsolutePath_helper(parent, path_so_far);
    },

    // Returns the absolute path of a category, for example:
    // Technology>Programming>Javascript
    getAbsolutePath: function(name) {
        return getAbsolutePath_helper(name, name);
    },


    // Adds a category to the category tree
    addCategory: function(name, parent_name = "root") {
        if(!this.tree[parent_name])
            throw "Parent category doesn't exist";
        else if(!this.tree[name]) {
            this.tree[name] = {
                parent: parent_name,
                children: []
            };
            this.tree[parent_name].children.push(name);
        }
        else
            throw "Category already exists";
    },

    getCategories: function() {
        var result = [] 
        for(var cat in this.tree)
            if(this.tree.hasOwnProperty(cat))
                result.push(cat);
        return result;
    },

    categoryExists: function(name) {
        return this.tree.hasOwnProperty(name);
    }
}