<?php require_once('../../../../Connections/conexion.php');
	$time = time();
	
	$insertSQL = sprintf("INSERT INTO z_messages (sender, receiver, message, time) VALUES (%s, %s, %s, %s)",
	GetSQLValueString($_SESSION['MM_Id'], "int"),
	GetSQLValueString($_POST['destinatario'], "int"),
	GetSQLValueString($_POST['mensaje'], "text"),
	GetSQLValueString($time, "int"));
	mysql_select_db($database_conexion, $conexion);
	$Result1 = mysql_query($insertSQL, $conexion) or die(mysql_error());

// $emailReceptor=email($_POST['destinatario']);
// notificar_por_correo($emailReceptor,$urlWeb,$nombreWeb);
?>