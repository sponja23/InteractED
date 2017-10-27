<?php
session_start();
require "../include/connect.php";
require "../include/scripts.html";

if(!isset($_SESSION["UserCode"]))
        header("Location: ../"); //Esto no se que es
    else {
    	if($_SESSION["Level"] >= 2){ //No creo que funque
			$na="Not answered";

			$sql= 'SELECT u.User,u.UserCode,l.Level, r.LevelRequested, r.Reason FROM requests r INNER JOIN users u on u.UserCode=r.UserCode INNER JOIN levels l on l.LevelCode=r.LevelRequested WHERE Answer="'.$na.'"';


			$result = $conn->query($sql);

			echo "<table>"; 

			if ($result->num_rows > 0) {
				while($row = $result->fetch_assoc()){
					echo "<tr><td>" . $row['User'] . "</td><td>" . $row['Level'] ."</td><td>" . $row['Reason'] . "</td><td>" . "<label id='Accepted-".$row['UserCode'] ."-".$row['LevelRequested']."' class='res'> Si </label> <label id='Declined-".$row['UserCode'] ."-".$row['LevelRequested']."' class='res'> No </label>" . "</td></tr>";  
				}
			}

			echo "</table>";
		}
		else{
			header("Location: ../");
		}
	}
?>

