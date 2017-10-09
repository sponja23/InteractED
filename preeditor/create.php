<?php
$Date = date('d/m/y h:i:s');
$Title = $_POST["title"];
$Tags = $_POST["tags"];
$Usercode= $_SESSION["UserCode"];
$sql = 'INSERT INTO articles (CreatorID, CreateDate, Title, Tags) VALUES ("' . $Usercode . '", "' . $Date . '", "' . $Title . '", "' . $Tags . '")';
$result = $conn->query($sql);
$conn->close();