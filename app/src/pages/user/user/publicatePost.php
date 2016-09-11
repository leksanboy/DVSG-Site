<?php require_once '../../../Connections/conexion.php';
	$time               = time();
	$userId             = $_SESSION['MM_Id'];
	$content            = $_POST['content'];
	$photos             = $_POST['photos'];
	$songs              = $_POST['songs'];
	$videos             = $_POST['videos'];

	$insertSQL = sprintf("INSERT INTO z_news (user, content, photos, songs, videos, time) VALUES (%s, %s, %s, %s, %s, %s)",
	GetSQLValueString($userId, "int"),
	GetSQLValueString($content, "text"),
	GetSQLValueString($photos, "text"),
	GetSQLValueString($songs, "text"),
	GetSQLValueString($videos, "int"),
	GetSQLValueString($time, "int"));
	mysql_select_db($database_conexion, $conexion);
	$Result1 = mysql_query($insertSQL, $conexion) or die(mysql_error());
	$insertId = mysql_insert_id();
?>
