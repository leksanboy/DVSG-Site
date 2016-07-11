<?php require_once('../Connections/conexion.php');

if (isset($_POST['nombre'])) {
	$loginUsername=$_POST['nombre'];
	$password=md5($_POST['password']);  

   
	mysql_select_db($database_conexion, $conexion);
	$query_signInAccess = sprintf("SELECT nombre, password, id, rango FROM z_users WHERE password=%s AND email=%s AND rango>0",
	GetSQLValueString($password, "text"),
	GetSQLValueString($loginUsername, "text"),
	GetSQLValueString($password, "text"),
	GetSQLValueString($loginUsername, "text")); 
		
	$signInAccess = mysql_query($query_signInAccess, $conexion) or die(mysql_error());
	$row_signInAccess = mysql_fetch_assoc($signInAccess);
	$totalRows_signInAccess = mysql_num_rows($signInAccess);

	$updateSQL = sprintf("UPDATE z_users SET ultima=now() WHERE id=%s",
	GetSQLValueString($row_signInAccess['id'], "int"));

	mysql_select_db($database_conexion, $conexion);
	$Result1 = mysql_query($updateSQL, $conexion) or die(mysql_error());

	if ($totalRows_signInAccess==1){
		$_SESSION['MM_Username'] = $loginUsername;
		$_SESSION['MM_Id'] = $row_signInAccess['id'];

		if (isset ($_POST['recordar']) && $_POST['recordar']=="on"){
			recordar_sesion($password,$_SESSION['MM_Username'],$_SESSION['MM_Id']);
		}	
	}

else echo 'false';

mysql_free_result($signInAccess); 
}?>