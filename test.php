<?php
session_start();


?>

<!DOCTYPE html>
<html>
<head>
	<title>test page</title>
</head>
<body>
	<p>Codigo de usuario: <?= $_SESSION["UserCode"] ?></p>
	<p>Nombre de usuario: <?= $_SESSION["Name"] ?> </p>
</body>
</html>