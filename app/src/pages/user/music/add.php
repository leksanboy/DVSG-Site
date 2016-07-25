<?php require_once('../../../Connections/conexion.php');
	$insertSQL = sprintf("INSERT INTO z_music_favorites (user, song) VALUES (%s, %s)",
	GetSQLValueString($_SESSION['MM_Id'], "int"),
	GetSQLValueString($_POST['id'], "text"));
	mysql_select_db($database_conexion, $conexion);
	$Result1 = mysql_query($insertSQL, $conexion) or die(mysql_error());
?>