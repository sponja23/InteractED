<!DOCTYPE html>
<html>
<head>
	<title>Category test</title>
</head>
<body>
	<p>Query:</p>
	<input type="text" id="input">
	<button type="button" onclick="$('#message').html(getChildren($('#input').val()))">Get Children</button>
	<button type="button" onclick="$('#message').html(getParent($('#input').val()))">Get Parent</button>
	<button type="button" onclick="$('#message').html(getAbsolutePath($('#input').val()))">Get Path</button>
	<label id="message"></label>
	<br>
	<p>Add:</p>
	<p>Name: <input type="text" id="name"></p>
	<p>Parent: <input type="text" id="parent"></p>
	<button type="button" onclick="addCategory($('#name').val(), $('#parent').val())">Add</button>
	<button type="button" onclick="writeFile()">Save</button>
</body>
<?php require "../include/scripts.html"; ?>
<script src="category.js"></script>
</html>