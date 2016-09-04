<?php require_once('../../../Connections/conexion.php');
	if ($_POST['type'] == 'add') { //ADD
		$insertSQL = sprintf("INSERT INTO z_videos_favorites (user, video, time) VALUES (%s, %s, %s)",
		GetSQLValueString($_SESSION['MM_Id'], "int"),
		GetSQLValueString($_POST['videoId'], "text"),
		GetSQLValueString(time(), "int"));
		mysql_select_db($database_conexion, $conexion);
		$Result1 = mysql_query($insertSQL, $conexion) or die(mysql_error());

		echo mysql_insert_id();
	} else if ($_POST['type'] == 'delete') { //DELETE
		$deleteSQL = sprintf("DELETE FROM z_videos_favorites WHERE user = %s AND id = %s",
		GetSQLValueString($_SESSION['MM_Id'], "int"),
		GetSQLValueString($_POST['videoId'], "int"));
		mysql_select_db($database_conexion, $conexion);
		$Result1 = mysql_query($deleteSQL, $conexion) or die(mysql_error());
	}
?>