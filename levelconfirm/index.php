<?php
session_start();
require "../include/connect.php";
$na="Not answered";

$sql= 'SELECT * FROM requests WHERE Answer="'.$na.'"';
$result = $conn->query($sql);

echo "<table>"; 
if ($result->num_rows > 0) {
	while($row = $result->fetch_assoc()){
		echo "<tr><td>" . $row['UserCode'] . "</td><td>" . $row['LevelRequested'] ."</td><td>" . $row['Reason'] . "</td><td>" . "<label id='yes' class='res'> Si </label> <label id='no' class='res'> No </label>" . "</td></tr>";  
	}
}

echo "</table>";
?>

<?php require "../include/scripts.html"; ?>
<script type="text/javascript">
 	$( ".res" ).click(function() {
 		var Answer = $(this).attr('id');
 		var UserCode = $("#UserCode").val(); //ESTOS NO SE TOMAN ASI, BUSCAR COMO SE TOMAN
 		var LevelRequested = $("#LevelRequested").val(); //ESTOS NO SE TOMAN ASI, BUSCAR COMO SE TOMAN
 		$.ajax({
 			url: "send.php",
 			type: "POST",
 			data: { Answer: Answer, Level: Level } , //CAMBIAR DATOS!
 			success: function (response) {
 				//ACA
            },
            error: function(jqXHR, textStatus, errorThrown) {
            	console.log(textStatus, errorThrown);        }
            });
 		}
 	});
</script>