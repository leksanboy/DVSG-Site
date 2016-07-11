<?php require_once('../../Connections/conexion.php');
	
	mysql_select_db($database_conexion, $conexion);
		$query_GetYearRight=sprintf("SELECT * FROM z_cars_models WHERE brand=%s GROUP by year",
		GetSQLValueString($_POST['brand'], "text"));
		$GetYearRight = mysql_query($query_GetYearRight, $conexion) or die(mysql_error());
		$row_GetYearRight = mysql_fetch_assoc($GetYearRight);
		$totalRows_GetYearRight = mysql_num_rows($GetYearRight);

?>
<form>
	<select onchange="javascript: vercom2(this.value,'<?php echo $_POST['brand']?>');" name="modelo_2">
		<option value="" selected="selected">Select a year</option>
		<?php do{?>    
			<option value="<?php echo $row_GetYearRight['year']?>"><?php echo $row_GetYearRight['year']?></option>
		<?php }while  ($row_GetYearRight = mysql_fetch_assoc($GetYearRight));?>    
	</select>
	<svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 306 306" xml:space="preserve">
		<polygon points="270.3,58.65 153,175.95 35.7,58.65 0,94.35 153,247.35 306,94.35"/>
	</svg>
</form>
<script type="text/javascript">
	function vercom2(valor,brand){
		$.ajax({
		    type: 'POST',
		    url: url+'pages/versus/model-right.php',
		    data: 'year='+valor+'&brand='+brand,
		    success: function(htmlres){
		    	$('#versusSelectModelRight').html(htmlres);
		    	$('#versusSelectModelRight').css({"pointer-events":"visible","opacity":"1"});
		    }
		});
	}
</script>
<?php mysql_free_result($GetYearRight);?>