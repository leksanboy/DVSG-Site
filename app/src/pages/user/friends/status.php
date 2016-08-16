<?php require_once('../../../Connections/conexion.php');
    $updateSQL = sprintf("UPDATE z_friends SET status = %s WHERE id = %s",
        GetSQLValueString($_POST['statusPost'], "text"),
        GetSQLValueString($_POST['idPost'], "text"));
    mysql_select_db($database_conexion, $conexion);
    $Result1 = mysql_query($updateSQL, $conexion) or die(mysql_error());

    echo true;
?>