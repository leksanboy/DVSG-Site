<?php require_once('../../../../Connections/conexion.php');
	$updateSQL = sprintf("UPDATE z_mensajes SET estado = '1' WHERE id=%s",
	GetSQLValueString($_POST['idLeido'], "int"));
	mysql_select_db($database_conexion, $conexion);
	$Result1 = mysql_query($updateSQL, $conexion) or die(mysql_error());
?>