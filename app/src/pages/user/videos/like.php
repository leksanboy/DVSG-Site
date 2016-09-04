<?php require_once('../../../Connections/conexion.php');
	$userId = $_SESSION['MM_Id'];
	$videoId = $_POST['videoId'];

	if (checkLikeUserVideo($userId, $videoId) == true){                    
	    $insertSQL = sprintf("INSERT INTO z_videos_likes (user, video) VALUES (%s, %s)",
			GetSQLValueString($userId, "int"),
			GetSQLValueString($videoId, "int"));
		mysql_select_db($database_conexion, $conexion);
		$Result1 = mysql_query($insertSQL, $conexion) or die(mysql_error());
		
		echo 'like';
	} else {
	    $deleteSQL = sprintf("DELETE FROM z_videos_likes WHERE user = %s AND video = %s",
			GetSQLValueString($userId, "int"),
			GetSQLValueString($videoId, "int"));
		mysql_select_db($database_conexion, $conexion);
		$Result1 = mysql_query($deleteSQL, $conexion) or die(mysql_error());

		echo 'unlike';
} ?>