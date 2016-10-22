<?php require_once('../../../Connections/conexion.php');
if (isset ($_POST["email"])){
	mysql_select_db($database_conexion, $conexion);
	$query_Recordset1 = sprintf("SELECT email FROM z_users WHERE email=%s",
	GetSQLValueString($_POST["email"], "text")); 
	
	$Recordset1 = mysql_query($query_Recordset1, $conexion) or die(mysql_error());
	$row_Recordset1 = mysql_fetch_assoc($Recordset1);
	$totalRows_Recordset1 = mysql_num_rows($Recordset1);
}

if ($totalRows_Recordset1!=0){
	echo 'false';
} else {
	$avatar = $urlWeb.'images/user/default_avatar.jpg';
	$insertSQL = sprintf("INSERT INTO z_users (name, avatar, email, password) VALUES (%s, %s, %s, %s)",
	GetSQLValueString($_POST['name'], "text"),
	GetSQLValueString($avatar, "text"),
	GetSQLValueString($_POST['email'], "text"),
	GetSQLValueString(md5($_POST['password']), "text"));
	mysql_select_db($database_conexion, $conexion);
	$Result1 = mysql_query($insertSQL, $conexion) or die(mysql_error());
	$insertId = mysql_insert_id();
  
	//Inicio de sesión automático
    mysql_select_db($database_conexion, $conexion);
	$query_GetUserData = sprintf("SELECT * FROM z_users WHERE name=%s AND email=%s AND password=%s",
	GetSQLValueString($_POST['name'],"text"),
	GetSQLValueString($_POST['email'],"text"),
	GetSQLValueString(md5($_POST['password']),"text"));
	$GetUserData = mysql_query($query_GetUserData, $conexion) or die(mysql_error());
	$row_GetUserData = mysql_fetch_assoc($GetUserData);
	$totalRows_GetUserData = mysql_num_rows($GetUserData);

    $_SESSION['MM_Id'] = $row_GetUserData['id'];	
    recordar_sesion($row_GetUserData['password'],$row_GetUserData['name'],$row_GetUserData['id']);
	mysql_free_result($GetUserData);

	//Welcome mail
	emailWelcome($insertId, $_POST['name'], $_POST['email']);
}?>