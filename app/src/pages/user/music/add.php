<?php require_once('../../../Connections/conexion.php');
	if ($_POST['type'] == 'add') { //ADD
		$insertSQL = sprintf("INSERT INTO z_music_favorites (user, song, position) VALUES (%s, %s, %s)",
		GetSQLValueString($_SESSION['MM_Id'], "int"),
		GetSQLValueString($_POST['id'], "text"),
		GetSQLValueString($_POST['position'], "text"));
		mysql_select_db($database_conexion, $conexion);
		$Result1 = mysql_query($insertSQL, $conexion) or die(mysql_error());
	} else if ($_POST['type'] == 'delete') { //DELETE
		$deleteSQL = sprintf("DELETE FROM z_music_favorites WHERE user = %s AND song = %s AND position = %s",
		GetSQLValueString($_SESSION['MM_Id'], "int"),
		GetSQLValueString($_POST['id'], "int"),
		GetSQLValueString($_POST['position'], "int"));
		mysql_select_db($database_conexion, $conexion);
		$Result1 = mysql_query($deleteSQL, $conexion) or die(mysql_error());
	}
?>