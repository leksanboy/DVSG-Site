<?php require_once('../../../Connections/conexion.php');
	$userId = $_SESSION['MM_Id'];
	$photoId = $_POST['photoId'];

	if (checkLikeUserPhoto($userId, $photoId) == true){                    
	    $insertSQL = sprintf("INSERT INTO z_photos_likes (user, photo) VALUES (%s, %s)",
			GetSQLValueString($userId, "int"),
			GetSQLValueString($photoId, "int"));
		mysql_select_db($database_conexion, $conexion);
		$Result1 = mysql_query($insertSQL, $conexion) or die(mysql_error());
		
		echo 'like';
	} else {
	    $deleteSQL = sprintf("DELETE FROM z_photos_likes WHERE user = %s AND photo = %s",
			GetSQLValueString($userId, "int"),
			GetSQLValueString($photoId, "int"));
		mysql_select_db($database_conexion, $conexion);
		$Result1 = mysql_query($deleteSQL, $conexion) or die(mysql_error());

		echo 'unlike';
} ?>