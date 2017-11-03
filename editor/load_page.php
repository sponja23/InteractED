<?php
	
	echo file_get_contents("/InteractED/post/content/" . md5($_POST["ID"]) . "/index.php");

?>