<?php session_start(); ?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">

		<title>Titulo</title>

		<?php require "../../include/head.html"; ?>

		<link rel="stylesheet" href="../../css/navigation.css">
		<link rel="stylesheet" href="../../css/font.css">
	</head>
	<body>
		<?php require "../../components/navigation.php"; ?>

		<div class="container">
			<?php require "content.html"; ?>
		</div>

		<?php require "../../include/scripts.html"; ?>
	</body>
</html>