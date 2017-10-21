<!DOCTYPE html>
<html>
    <head>
        <title>Category test</title>
    </head>
    <body>
        <button type="button" onclick="alert(JSON.stringify(category.tree))">Get tree</button>
        <br>
        <p>Query:</p>
        
        <input type="text" id="input">

        <button type="button" onclick="$('#message').html(category.getChildren($('#input').val()))">Get Children</button>
        <button type="button" onclick="$('#message').html(category.getParent($('#input').val()))">Get Parent</button>
        <button type="button" onclick="$('#message').html(category.getAbsolutePath($('#input').val()))">Get Path</button>

        <label id="message"></label>
        <br>
        <p>Add:</p>

        <p>Name: <input type="text" id="name"></p>
        <p>Parent: <input type="text" id="parent"></p>

        <button type="button" onclick="category.addCategory($('#name').val(), $('#parent').val())">Add</button>
        <button type="button" onclick="category.writeFile()">Save</button>

        <?php require "../include/scripts.html"; ?>
        <script src="category.js"></script>
        <script>
            category.loadTree();
            console.log(category.tree);
        </script>
    </body>
</html>