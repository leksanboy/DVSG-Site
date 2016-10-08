<?php require_once('../../../Connections/conexion.php');
	//Delete post
	$deleteSQL = sprintf("DELETE FROM z_news WHERE id = %s",
	GetSQLValueString($_POST['id'], "int"));
	mysql_select_db($database_conexion, $conexion);
	$Result1 = mysql_query($deleteSQL, $conexion) or die(mysql_error());

	//Delete post files
	$deleteSQL = sprintf("DELETE FROM z_news_files WHERE post = %s",
	GetSQLValueString($_POST['id'], "int"));
	mysql_select_db($database_conexion, $conexion);
	$Result1 = mysql_query($deleteSQL, $conexion) or die(mysql_error());

	//Delete post comments
	$deleteSQL = sprintf("DELETE FROM z_news_comments WHERE post = %s",
	GetSQLValueString($_POST['id'], "int"));
	mysql_select_db($database_conexion, $conexion);
	$Result1 = mysql_query($deleteSQL, $conexion) or die(mysql_error());
?>