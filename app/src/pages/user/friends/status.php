<?php require_once('../../../Connections/conexion.php');
	if ($_POST['status'] == 0) { //New one
		$insertSQL = sprintf("INSERT INTO z_friends (status, sender, receiver) VALUES (%s, %s, %s)",
		GetSQLValueString(1, "int"),
		GetSQLValueString($_POST['sender'], "int"),
		GetSQLValueString($_POST['receiver'], "int"));
		mysql_select_db($database_conexion, $conexion);
		$Result1 = mysql_query($insertSQL, $conexion) or die(mysql_error());
	} else if ($_POST['status'] == 1) { //Cancel request / Remove from friends
		$deleteSQL = sprintf("DELETE FROM z_friends WHERE
								(sender = %s AND receiver = %s) 
								OR 
								(sender = %s AND receiver = %s)",
		$_POST['sender'], $_POST['receiver'], $_POST['receiver'], $_POST['sender']);
		mysql_select_db($database_conexion, $conexion);
		$Result1 = mysql_query($deleteSQL, $conexion) or die(mysql_error());
	} else if ($_POST['status'] == 2) { //Accept request
	    $updateSQL = sprintf("UPDATE z_friends SET status = 2 WHERE 
	    						(sender = %s AND receiver = %s)",
        $_POST['sender'], $_POST['receiver']);
	    mysql_select_db($database_conexion, $conexion);
	    $Result1 = mysql_query($updateSQL, $conexion) or die(mysql_error());
	}
?>