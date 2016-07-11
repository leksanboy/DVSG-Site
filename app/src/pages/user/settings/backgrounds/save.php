<?php require_once('../../../../Connections/conexion.php');
    $updateSQL = sprintf("UPDATE z_users SET avatar_bg1=%s, avatar_bg2=%s, avatar_bg3=%s WHERE id=%s",
        GetSQLValueString($_POST['avatar_bg1'], "text"),
        GetSQLValueString($_POST['avatar_bg2'], "text"),
        GetSQLValueString($_POST['avatar_bg3'], "text"),
        GetSQLValueString($_SESSION['MM_Id'], "int"));
    mysql_select_db($database_conexion, $conexion);
    $Result1 = mysql_query($updateSQL, $conexion) or die(mysql_error());

    echo true;
?>