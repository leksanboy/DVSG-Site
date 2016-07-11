<?php require_once('../../Connections/conexion.php');

 	mysql_select_db($database_conexion, $conexion);
		$query_GetRightData=sprintf("SELECT * FROM z_cars_models WHERE brand=%s AND year=%s AND model=%s",
		GetSQLValueString($_POST['brand'], "text"),
		GetSQLValueString($_POST['year'], "text"),
		GetSQLValueString($_POST['model'], "text"));
		$GetRightData = mysql_query($query_GetRightData, $conexion) or die(mysql_error());
		$row_GetRightData = mysql_fetch_assoc($GetRightData);
		$totalRows_GetRightData = mysql_num_rows($GetRightData);

	$imageRightBox = explode('-:#:-', $row_GetRightData['images']);

?>
<script type="text/javascript">
	$('#versusBoxImageRight img').attr("src", "<?php echo $imageRightBox[0];?>");
	$('.imageBoxRight img').css({"-webkit-filter":"none","opacity":"1"});
	$('.imageBoxRight').css("background","none");
	$('.vs').css("background","rgba(21, 25, 29, 0.5)");

	$('.dataBox .maxSpeedRight').html("<?php echo $row_GetRightData['maxSpeed'];?>");
	$('.dataBox .acceleration100Right').html("<?php echo $row_GetRightData['acceleration100'];?>");
	$('.dataBox .acceleration1000Right').html("<?php echo $row_GetRightData['acceleration1000'];?>");
	$('.dataBox .urbanConsumptionRight').html("<?php echo $row_GetRightData['urbanConsumption'];?>");
	$('.dataBox .extraUrbanConsumptionRight').html("<?php echo $row_GetRightData['extraUrbanConsumption'];?>");
	$('.dataBox .averageConsumptionRight').html("<?php echo $row_GetRightData['averageConsumption'];?>");
	$('.dataBox .emissionsRight').html("<?php echo $row_GetRightData['emissions'];?>");
</script>
<?php mysql_free_result($GetRightData);?>