<?php require_once('../../../../Connections/conexion.php');
    $updateSQL = sprintf("UPDATE z_users SET avatar=%s, avatar_original=%s, primary_color=%s, secondary_color=%s, nombre=%s, car=%s, profession=%s, country_id=%s, country=%s, city_id=%s, city=%s, birthday=%s, relationship=%s, other=%s WHERE id=%s",
        GetSQLValueString($_POST['avatar'], "text"),
        GetSQLValueString($_POST['avatar_original'], "text"),
        GetSQLValueString($_POST['primary-color'], "text"),
        GetSQLValueString($_POST['secondary-color'], "text"),
        GetSQLValueString($_POST['nombre'], "text"),
        GetSQLValueString($_POST['car'], "text"),
        GetSQLValueString($_POST['profession'], "text"),
        GetSQLValueString($_POST['country_id'], "text"),
        GetSQLValueString($_POST['country'], "text"),
        GetSQLValueString($_POST['city_id'], "text"),
        GetSQLValueString($_POST['city'], "text"),
        GetSQLValueString($_POST['birthday'], "text"),
        GetSQLValueString($_POST['relationship'], "text"),
        GetSQLValueString($_POST['other'], "text"),
        GetSQLValueString($_SESSION['MM_Id'], "int"));
    mysql_select_db($database_conexion, $conexion);
    $Result1 = mysql_query($updateSQL, $conexion) or die(mysql_error());

    echo true;
?>