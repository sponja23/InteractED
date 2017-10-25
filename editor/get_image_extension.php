<?php
$imageSize = getimagesize($_GET["url"]);
echo image_type_to_extension($imageSize[2]);
?>