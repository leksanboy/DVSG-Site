<?php require_once('../../../../Connections/conexion.php');
	$updateSQL = sprintf("UPDATE z_users SET password=%s WHERE id=%s",
		GetSQLValueString(md5($_POST['newPassword']), "text"),
		GetSQLValueString($_POST['idUser'], "int"));
	mysql_select_db($database_conexion, $conexion);
	$Result1 = mysql_query($updateSQL, $conexion) or die(mysql_error());
	
	echo true;
?>