<?php require_once('../../../Connections/conexion.php');
	$deleteSQL = sprintf("DELETE FROM z_music_favorites WHERE id = %s",
	GetSQLValueString($_POST['id'], "int"));
	mysql_select_db($database_conexion, $conexion);
	$Result1 = mysql_query($deleteSQL, $conexion) or die(mysql_error());
?>