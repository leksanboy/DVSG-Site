<?php require_once('../../Connections/conexion.php');
	
	//BORRADO BASE DATOS
    $deleteSQL = sprintf("DELETE FROM z_img_text WHERE id=%s",
    GetSQLValueString($_POST['id'], "text"));
        
    mysql_select_db($database_conexion, $conexion);
    $Result1 = mysql_query($deleteSQL, $conexion) or die(mysql_error());
?>