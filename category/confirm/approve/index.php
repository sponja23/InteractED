<?php 
require "../../../include/connect.php";

$sql = "UPDATE Categories SET Implemented = 1 WHERE CategoryID = '".$_GET['id']."' ";

if ($conn->query($sql) === TRUE) {
	header( "Location: ../index.php" );
} else {
    echo "Error updating record: " . $conn->error;
}

$conn->close();
?>