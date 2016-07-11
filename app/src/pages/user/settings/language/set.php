<?php require_once('../../../../Connections/conexion.php');
	setcookie("idioma", $_POST['language'], time() + (365 * 24 * 60 * 60),"/"); 
?>