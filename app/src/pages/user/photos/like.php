<?php require_once('../../../Connections/conexion.php');
	if (checkLikeUserPhoto($_SESSION['MM_Id'], $_POST['photoId']) == true){                    
	    $insertSQL = sprintf("INSERT INTO z_photos_likes (user, photo) VALUES (%s, %s)",
			GetSQLValueString($_SESSION['MM_Id'], "int"),
			GetSQLValueString($_POST['photoId'], "int"));
		mysql_select_db($database_conexion, $conexion);
		$Result1 = mysql_query($insertSQL, $conexion) or die(mysql_error());
		
		echo 'like';
	} else {
	    $deleteSQL = sprintf("DELETE FROM z_photos_likes WHERE user = %s AND photo = %s",
			GetSQLValueString($_SESSION['MM_Id'], "int"),
			GetSQLValueString($_POST['photoId'], "int"));
		mysql_select_db($database_conexion, $conexion);
		$Result1 = mysql_query($deleteSQL, $conexion) or die(mysql_error());

		echo 'unlike';
} ?>