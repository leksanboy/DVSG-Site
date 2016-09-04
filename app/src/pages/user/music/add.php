<?php require_once('../../../Connections/conexion.php');
	if ($_POST['type'] == 'add') { //ADD
		$insertSQL = sprintf("INSERT INTO z_music_favorites (user, song, time) VALUES (%s, %s, %s)",
		GetSQLValueString($_SESSION['MM_Id'], "int"),
		GetSQLValueString($_POST['songId'], "text"),
		GetSQLValueString(time(), "text"));
		mysql_select_db($database_conexion, $conexion);
		$Result1 = mysql_query($insertSQL, $conexion) or die(mysql_error());

		echo mysql_insert_id();
	} else if ($_POST['type'] == 'delete') { //DELETE
		$deleteSQL = sprintf("DELETE FROM z_music_favorites WHERE user = %s AND id = %s",
		GetSQLValueString($_SESSION['MM_Id'], "int"),
		GetSQLValueString($_POST['songId'], "int"));
		mysql_select_db($database_conexion, $conexion);
		$Result1 = mysql_query($deleteSQL, $conexion) or die(mysql_error());
	}
?>