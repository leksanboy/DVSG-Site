<?php require_once('../../../../Connections/conexion.php');
	$insertSQL = sprintf("INSERT INTO z_mensajes (envia, recibe, mensaje) VALUES (%s, %s, %s)",
	GetSQLValueString($_SESSION['MM_Id'], "int"),
	GetSQLValueString($_POST['destinatario'], "int"),
	GetSQLValueString($_POST['mensaje'], "text"));
	mysql_select_db($database_conexion, $conexion);
	$Result1 = mysql_query($insertSQL, $conexion) or die(mysql_error());

// $emailReceptor=email($_POST['destinatario']);
// notificar_por_correo($emailReceptor,$urlWeb,$nombreWeb);
?>