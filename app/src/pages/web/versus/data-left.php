<?php require_once('../../Connections/conexion.php');

 	mysql_select_db($database_conexion, $conexion);
		$query_GetLeftData=sprintf("SELECT * FROM z_cars_models WHERE brand=%s AND year=%s AND model=%s",
		GetSQLValueString($_POST['brand'], "text"),
		GetSQLValueString($_POST['year'], "text"),
		GetSQLValueString($_POST['model'], "text"));
		$GetLeftData = mysql_query($query_GetLeftData, $conexion) or die(mysql_error());
		$row_GetLeftData = mysql_fetch_assoc($GetLeftData);
		$totalRows_GetLeftData = mysql_num_rows($GetLeftData);

	$imageLeftBox = explode('-:#:-', $row_GetLeftData['images']);

?>
<script type="text/javascript">
	$('#versusBoxImageLeft img').attr("src", "<?php echo $imageLeftBox[0];?>");
	$('.imageBoxLeft img').css({"-webkit-filter":"none","opacity":"1"});
	$('.imageBoxLeft').css("background","none");
	$('.vs').css("background","rgba(21, 25, 29, 0.5)");

	$('.dataBox .maxSpeedLeft').html("<?php echo $row_GetLeftData['maxSpeed'];?>");
	$('.dataBox .acceleration100Left').html("<?php echo $row_GetLeftData['acceleration100'];?>");
	$('.dataBox .acceleration1000Left').html("<?php echo $row_GetLeftData['acceleration1000'];?>");
	$('.dataBox .urbanConsumptionLeft').html("<?php echo $row_GetLeftData['urbanConsumption'];?>");
	$('.dataBox .extraUrbanConsumptionLeft').html("<?php echo $row_GetLeftData['extraUrbanConsumption'];?>");
	$('.dataBox .averageConsumptionLeft').html("<?php echo $row_GetLeftData['averageConsumption'];?>");
	$('.dataBox .emissionsLeft').html("<?php echo $row_GetLeftData['emissions'];?>");
</script>
<?php mysql_free_result($GetLeftData);?>