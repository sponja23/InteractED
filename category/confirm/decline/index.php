<?php 
require "../../../include/connect.php";

$sql = "DELETE FROM Categories WHERE CategoryID = '".$_GET['id']."' ";

if ($conn->query($sql) === TRUE) {
	header( "Location: ../index.php" );
} else {
    echo "Error updating record: " . $conn->error;
}

$conn->close();

?>