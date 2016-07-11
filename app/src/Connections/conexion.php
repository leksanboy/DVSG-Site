<?php if (!isset($_SESSION)) { session_start(); }
# FileName="Connection_php_mysql.htm"
# Type="MYSQL"
# HTTP="true"

//TAMBIEN ES PARA GOOGLE CLOUD
$hostname_conexion = "localhost";
$database_conexion = "DVSG-Site";	//cambiar por DVSG -/- "DVSG-Site"
$username_conexion = "root";
$password_conexion = "root";	//cambiar pass por "Rafalskyy1991" -/- "root"

$conexion = mysql_pconnect($hostname_conexion, $username_conexion, $password_conexion) or trigger_error(mysql_error(),E_USER_ERROR); 

if (is_file("includes/funciones.php")){
	include_once("includes/funciones.php");
	}
	else if(is_file("../includes/funciones.php"))
	{
	include_once("../includes/funciones.php");
	}
	else if(is_file("../../includes/funciones.php"))
	{
	include_once("../../includes/funciones.php");
	}
	else if(is_file("../../../includes/funciones.php"))
	{
	include_once("../../../includes/funciones.php");
	}
	else if(is_file("../../../../includes/funciones.php"))
	{
	include_once("../../../../includes/funciones.php");
}
?>