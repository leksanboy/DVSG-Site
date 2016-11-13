<?php require_once('../../../../Connections/conexion.php');
	$id = $_POST['id'];
	$session = $_SESSION['MM_Id'];
	$category = $_POST['category'];

	if ($category == 'inbox') {
		$updateSQL = sprintf("UPDATE z_messages SET is_deleted_receiver = %s WHERE id = %s AND receiver = %s",
	    GetSQLValueString(1, "int"),
	    GetSQLValueString($id, "int"),
	    GetSQLValueString($session, "int"));
	    mysql_select_db($database_conexion, $conexion);
	    $Result1 = mysql_query($updateSQL, $conexion) or die(mysql_error());
	} else if ($category == 'outbox') {
		$updateSQL = sprintf("UPDATE z_messages SET is_deleted_sender = %s WHERE id = %s AND sender = %s",
	    GetSQLValueString(1, "int"),
	    GetSQLValueString($id, "int"),
	    GetSQLValueString($session, "int"));
	    mysql_select_db($database_conexion, $conexion);
	    $Result1 = mysql_query($updateSQL, $conexion) or die(mysql_error());
	}
?>