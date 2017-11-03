<?php
session_start();
require "../include/connect.php";
require "../include/scripts.html";

if($_SESSION["Level"] >= 2){ 
	
	$sql= 'SELECT * FROM categories WHERE Implemented=0';
	$result = $conn->query($sql);
	echo "<table><thead><tr><td>Nombre</td><td>Sub-categoria de</td><td>Respuesta</td></tr></thead><tbody>"; 

	if ($result->num_rows > 0) {
		while($row = $result->fetch_assoc()){
			echo "<tr><td>" . $row['CategoryName'] . "</td><td>" . $row['CategoryParent'] ."</td><td>" . "<button class='btn waves-effect waves-light'><i class='material-icons' data-answer='1' data-category=" . $row['CategoryID']. ">check</i> </button> <button class='btn waves-effect waves-light'><i class='material-icons' data-answer='2' data-category=" . $row['CategoryID']. ">close</i></button>" . "</td></tr>";  
		}
	}
	else {
		echo "<script>alert ('No hay solicitudes a responder'); window.history.back();</script>";
	}

	echo "</tbody></table>";
}
else{
	echo "<script>window.history.back();</script>";
}
?>