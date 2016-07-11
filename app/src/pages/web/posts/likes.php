<?php require_once('../../Connections/conexion.php'); 
	if (comprobacionLikesPosts($_SESSION['MM_Id'],$_POST['iddelpost'])=='true'){                                 
	    $insertSQL = sprintf("INSERT INTO z_posts_likes (user, page) VALUES (%s, %s)",
		GetSQLValueString($_SESSION['MM_Id'], "int"),
		GetSQLValueString($_POST['iddelpost'], "text"));
		mysql_select_db($database_conexion, $conexion);
		$Result1 = mysql_query($insertSQL, $conexion) or die(mysql_error());
		
		actualiazarPost('addLike', $_POST['iddelpost']);
		echo 'Added';
	} else {
	    $deleteSQL = sprintf("DELETE FROM z_posts_likes WHERE user = %s AND page = %s",
		GetSQLValueString($_SESSION['MM_Id'], "int"),
		GetSQLValueString($_POST['iddelpost'], "text"));
		mysql_select_db($database_conexion, $conexion);
		$Result1 = mysql_query($deleteSQL, $conexion) or die(mysql_error());

		actualiazarPost('removeLike', $_POST['iddelpost']);
		echo 'Removed';
	}
?>