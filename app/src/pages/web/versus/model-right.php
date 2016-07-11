<?php require_once('../../Connections/conexion.php');

 	mysql_select_db($database_conexion, $conexion);
		$query_GetModalRight=sprintf("SELECT * FROM z_cars_models WHERE brand=%s AND year=%s",
		GetSQLValueString($_POST['brand'], "text"),
		GetSQLValueString($_POST['year'], "text"));
		$GetModalRight = mysql_query($query_GetModalRight, $conexion) or die(mysql_error());
		$row_GetModalRight = mysql_fetch_assoc($GetModalRight);
		$totalRows_GetModalRight = mysql_num_rows($GetModalRight);

?>
<form>
	<select onchange="javascript: vercom3(this.value,'<?php echo $_POST['brand']?>','<?php echo $_POST['year']?>');" name="modelo_3">
		<option value="" selected="selected">Select a model</option>
		<?php do{?>    
			<option value="<?php echo $row_GetModalRight['model']?>"><?php echo $row_GetModalRight['model']?></option>
		<?php }while  ($row_GetModalRight = mysql_fetch_assoc($GetModalRight));?>    
	</select>
	<svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 306 306" xml:space="preserve">
		<polygon points="270.3,58.65 153,175.95 35.7,58.65 0,94.35 153,247.35 306,94.35"/>
	</svg>
</form>
<script type="text/javascript">
	function vercom3(model,brand,year){
		$.ajax({
		    type: 'POST',
		    url: url+'pages/versus/data-right.php',
		    data: 'model='+model+'&brand='+brand+'&year='+year,
		    success: function(response){
		    	$('#versusResponseRight').html(response);
		    }
		});
	}
</script>
<?php mysql_free_result($GetModalRight);?>