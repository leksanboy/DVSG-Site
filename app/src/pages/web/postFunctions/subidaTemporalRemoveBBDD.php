<?php require_once('../../Connections/conexion.php');

	//BORRADO IMAGENES DE LA BASE DE DATOS
    $deleteSQL = sprintf("DELETE FROM z_post_img_temporal WHERE id=%s",
    GetSQLValueString($_POST['idtemporal'], "int"));
    mysql_select_db($database_conexion, $conexion);
    $Result1 = mysql_query($deleteSQL, $conexion) or die(mysql_error());
?>