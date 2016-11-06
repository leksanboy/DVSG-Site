<?php require_once('../../../Connections/conexion.php');
	if ($_POST['type'] == 'add') { //ADD
		$updateSQL = sprintf("UPDATE z_photos_favorites SET is_deleted = %s WHERE id = %s AND user = %s",
	    GetSQLValueString(0, "int"),
	    GetSQLValueString($_POST['id'], "int"),
	    GetSQLValueString($_SESSION['MM_Id'], "int"));
	    mysql_select_db($database_conexion, $conexion);
	    $Result1 = mysql_query($updateSQL, $conexion) or die(mysql_error());
	} else if ($_POST['type'] == 'delete') { //DELETE
		$updateSQL = sprintf("UPDATE z_photos_favorites SET is_deleted = %s WHERE id = %s AND user = %s",
	    GetSQLValueString(1, "int"),
	    GetSQLValueString($_POST['id'], "int"),
	    GetSQLValueString($_SESSION['MM_Id'], "int"));
	    mysql_select_db($database_conexion, $conexion);
	    $Result1 = mysql_query($updateSQL, $conexion) or die(mysql_error());
	}
?>