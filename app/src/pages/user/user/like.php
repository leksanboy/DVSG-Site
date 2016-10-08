<?php require_once('../../../Connections/conexion.php');
	$userId = $_SESSION['MM_Id'];
	$postId = $_POST['postId'];

	if (checkLikeUserNews($userId, $postId) == true){                    
	    $insertSQL = sprintf("INSERT INTO z_news_likes (user, post) VALUES (%s, %s)",
			GetSQLValueString($userId, "int"),
			GetSQLValueString($postId, "int"));
		mysql_select_db($database_conexion, $conexion);
		$Result1 = mysql_query($insertSQL, $conexion) or die(mysql_error());
		
		echo 'like';
	} else {
	    $deleteSQL = sprintf("DELETE FROM z_news_likes WHERE user = %s AND post = %s",
			GetSQLValueString($userId, "int"),
			GetSQLValueString($postId, "int"));
		mysql_select_db($database_conexion, $conexion);
		$Result1 = mysql_query($deleteSQL, $conexion) or die(mysql_error());

		echo 'unlike';
} ?>