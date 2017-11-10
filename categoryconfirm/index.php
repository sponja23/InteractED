<?php session_start(); ?>
<!DOCTYPE html>
<html>
<head>
	<?
	require "../include/scripts.html";
	require "../include/head.html"; 
	?>
    <link rel="stylesheet" href="../components/navigation/navigation.css">
	<link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="../css/input.css">
    <link rel="stylesheet" href="../css/font.css">
</head>
<body>
	<?php require "../components/navigation/navigation.php" ?>
	<div class="container" id="table"></div>
</body>
<script src="../components/navigation/navigation.js"></script>
<script type="text/javascript">
	$(document).ready (function(){
		function send(){
			$( ".change-status" ).click(function() {
				$.ajax({
					url: 'send.php',
					type: 'POST',
					data: { Answer: $(this).data("answer"),  CategoryID: $(this).data("category") } , 
					success: function (response) {
						if (response === "0"){
							location.reload();
						}
						else{
							var loc = "confirmed/?id=";
							window.location.href = loc.concat(response);

						}
					},
					error: function(jqXHR, textStatus, errorThrown) {
						console.log(textStatus, errorThrown);        
					}
				});
			});
 		}


	 	function bring(){
	 		$.ajax({
	 			url: 'load.php',
	 			type: 'POST',
	 			success: function (response) {
	 				$("#table").html(response);
	 				send();
	            },
	            error: function(jqXHR, textStatus, errorThrown) {
	            	console.log(textStatus, errorThrown);        
	            }
	        });
	 	}
	 	bring();
	});
</script>
</html>
