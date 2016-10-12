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
	$avatar = "data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAANwAAADcAAABJRU5ErkJggg==";
	$insertSQL = sprintf("INSERT INTO z_users (name, email, password) VALUES (%s, %s, %s)",
	GetSQLValueString($_POST['name'], "text"),
	GetSQLValueString($_POST['email'], "text"),
	GetSQLValueString(md5($_POST['password']), "text"));
	mysql_select_db($database_conexion, $conexion);
	$Result1 = mysql_query($insertSQL, $conexion) or die(mysql_error());
  
	//Inicio de sesión automático
    mysql_select_db($database_conexion, $conexion);
	$query_SacarMiPerfil = sprintf("SELECT * FROM z_users WHERE name=%s AND email=%s AND password=%s",
	GetSQLValueString($_POST['name'],"text"),
	GetSQLValueString($_POST['email'],"text"),
	GetSQLValueString(md5($_POST['password']),"text"));
	
	$SacarMiPerfil = mysql_query($query_SacarMiPerfil, $conexion) or die(mysql_error());
	$row_SacarMiPerfil = mysql_fetch_assoc($SacarMiPerfil);
	$totalRows_SacarMiPerfil = mysql_num_rows($SacarMiPerfil);
	
    $_SESSION['MM_Username'] = $row_SacarMiPerfil['name'];
    $_SESSION['MM_Id'] = $row_SacarMiPerfil['id'];	
    recordar_sesion($row_SacarMiPerfil['password'],$row_SacarMiPerfil['name'],$row_SacarMiPerfil['id']);
	mysql_free_result($SacarMiPerfil);
  
  	$para=$emailAdmin;
		$titulo = 'New user in ' .$nombreWeb;
		$mensaje = $_POST['name'].'Welcome to ' .$urlWeb ;
		$cabeceras = 'From: Diesel vs. Gasoline' . "\r\n" .
			'Reply-To: mail-noreply@diesel-vs-gasoline.com' . "\r\n" .
			'X-Mailer: PHP/' . phpversion();
		
		//mail($para, $titulo, $mensaje, $cabeceras);
		
  	$para=$_POST['email'];
		$titulo = 'Welcome to ' .$nombreWeb;
		$mensaje = ' Welcome to '.$urlWeb ;
		$cabeceras = 'From: Diesel vs. Gasoline' . "\r\n" .
			'Reply-To: mail-noreply@diesel-vs-gasoline.com' . "\r\n" .
			'X-Mailer: PHP/' . phpversion();
		
		//mail($para, $titulo, $mensaje, $cabeceras);
}?>