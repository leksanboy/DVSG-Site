<?php require_once('../../../../Connections/conexion.php');
	$updateSQL = sprintf("UPDATE z_messages SET is_deleted = %s WHERE id = %s AND receiver = %s",
    GetSQLValueString(1, "int"),
    GetSQLValueString($_POST['id'], "int"),
    GetSQLValueString($_SESSION['MM_Id'], "int"));
    mysql_select_db($database_conexion, $conexion);
    $Result1 = mysql_query($updateSQL, $conexion) or die(mysql_error());
?>