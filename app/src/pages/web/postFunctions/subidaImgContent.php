<?php require_once('../../Connections/conexion.php');

	//Insertar registro
	$insertSQL = sprintf("INSERT INTO z_img_text (nombre) VALUES (%s)",
	GetSQLValueString($_POST['dataImagenContent'], "text"));

	mysql_select_db($database_conexion, $conexion);
	$Result1 = mysql_query($insertSQL, $conexion) or die(mysql_error());


	//CONSULTA BASE DATOS
	mysql_select_db($database_conexion, $conexion);
	$query_DatosImg = sprintf("SELECT * FROM z_img_text WHERE nombre=%s",
	GetSQLValueString($_POST['dataImagenContent'],"text"));

	$DatosImg = mysql_query($query_DatosImg, $conexion) or die(mysql_error());
	$row_DatosImg = mysql_fetch_assoc($DatosImg);
	$totalRows_DatosImg = mysql_num_rows($DatosImg);

?>
<div id="image<?php echo $row_DatosImg['id'] ?>">
	<span class="deleteImage" onclick="postOperations(2,'<?php echo $row_DatosImg['id'] ?>')" contenteditable="false">
		<?php include("../../images/svg/remove.php");?>
	</span>
	<img src="<?php echo $row_DatosImg['nombre'] ?>"/>
</div>
<br>
<?php  mysql_free_result($DatosImg);?>