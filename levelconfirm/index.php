<html>
<head>
	<?require "../include/scripts.html";?>
	<link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="../css/input.css">
    <link rel="stylesheet" href="../css/font.css">

<script type="text/javascript">
	$(document).ready (function(){
		function charge(){
		$( '.res' ).click(function() {
	 		var Answer = $(this).attr('id').split('-');
	 		
	 		$.ajax({
	 			url: 'send.php',
	 			type: 'POST',
	 			data: { UC:Answer[1], Answer: Answer[0], Level: Answer[2] } , 
	 			success: function (response) {
	 				alert (response);
	            },
	            error: function(jqXHR, textStatus, errorThrown) {
	            	console.log(textStatus, errorThrown);        
	            }
	        });
	 		
	 	});
 		}


	 	function bring(){
	 		$.ajax({
	 			url: 'inicio.php',
	 			type: 'POST',
	 			success: function (response) {
	 				$("#div1").html(response);
	 				charge();
	            },
	            error: function(jqXHR, textStatus, errorThrown) {
	            	console.log(textStatus, errorThrown);        
	            }
	        });
	 	}
	 	bring();
	});
</script>

</head>
	<body>
			<div id="div1"></div>
	</body>
</html>
