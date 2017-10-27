<HTML>
<HEAD></HEAD>
<BODY>

    <label>Eliga el nivel al que desea cambiar</label>
	<!-- Lograr que no te deje seleccionar en el que se encuentra -->
	<select id="level">
		<option value="0">B&aacute;sico</option>
		<option value="1">Edici&oacute;n</option>
		<option value="2">Administrador</option>
	</select>
	<label>Explique el motivo de esta solicitud</label>
	<input type="text" name="reason" id="reason">
	<label id="cancel">Cancelar</label>
	<label id= "send">Enviar</label>
</BODY>
</HTML>


<?php require "../include/scripts.html"; ?>
<script type="text/javascript">
 	$( "#send" ).click(function() {
 		var Reason = $("#reason").val();
 		if (Reason!=""){
 			var Level= $("#level option:selected").val()
 			$.ajax({
                        url: "send.php",
                        type: "POST",
                        data: { Reason: Reason, Level: Level } ,
                        success: function (response) {
                            if (response=="0")
                            {
                               window.history.back();
                               alert ("Ya estas en ese nivel"); 
                            }
                            else if (response=="1"){
                            	window.history.back();
                            	alert ("Se ha enviado la solicitud correctamente");
                            }
                            else if (response=="2"){
                            	window.history.back();
                            	alert ("Ha habido un error al enviar la solicitud");
                            }
                        },
                        error: function(jqXHR, textStatus, errorThrown) {
                            console.log(textStatus, errorThrown);
                        }
                    });
 		}
 		else{
 			$("#reason").focus();
 		}
 	});
 	$( "#cancel" ).click(function() {
 		window.history.back();
 	});
</script>