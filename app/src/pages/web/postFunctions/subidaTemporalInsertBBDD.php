<?php require_once('../../Connections/conexion.php');
	$updateSQL = sprintf("UPDATE z_post_img_temporal SET imagen=%s WHERE id=%s",
	GetSQLValueString($_POST['valor'], "text"),
	GetSQLValueString($_POST['id'], "int"));
	mysql_select_db($database_conexion, $conexion);
	$Result1 = mysql_query($updateSQL, $conexion) or die(mysql_error());
?>