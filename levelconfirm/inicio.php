<?php
session_start();
require "../include/connect.php";
require "../include/scripts.html";

if($_SESSION["Level"] >= 2){ 
	$na="Not answered";

	$sql= 'SELECT u.User,u.UserCode,l.Level, r.LevelRequested, r.Reason FROM requests r INNER JOIN users u on u.UserCode=r.UserCode INNER JOIN levels l on l.LevelCode=r.LevelRequested WHERE Answer="'.$na.'"';
	$result = $conn->query($sql);
	echo "<table><thead><tr><td>Usuario</td><td>Nivel</td><td>Motivo</td><td>Respuesta</td></tr></thead><tbody>"; 

	if ($result->num_rows > 0) {
		while($row = $result->fetch_assoc()){
			echo "<tr><td>" . $row['User'] . "</td><td>" . $row['Level'] ."</td><td>" . $row['Reason'] . "</td><td>" . "<i class='materialize-icons' data-answer='Accepted' data-user=" . $row['UserCode'] . " data-levelrequested=" . $row['LevelRequested'] . ">check</i> <i class='materialize-icons' data-answer='Declined' data-user=" . $row['UserCode'] . " data-levelrequested=" . $row['LevelRequested'] . " class='btn'>close</i>" . "</td></tr>";  
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

