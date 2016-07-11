<?php require_once('../../Connections/conexion.php'); 
	if (comprobacionLikesCars($_SESSION['MM_Id'],$_POST['iddelmodel'])=='true'){                                 
	    $insertSQL = sprintf("INSERT INTO z_cars_likes (user, page) VALUES (%s, %s)",
		GetSQLValueString($_SESSION['MM_Id'], "int"),
		GetSQLValueString($_POST['iddelmodel'], "text"));
		mysql_select_db($database_conexion, $conexion);
		$Result1 = mysql_query($insertSQL, $conexion) or die(mysql_error());

		actualiazarCars('addLike', $_POST['iddelmodel']);
		echo 'Added';
	} else {
	    $deleteSQL = sprintf("DELETE FROM z_cars_likes WHERE user = %s AND page = %s",
		GetSQLValueString($_SESSION['MM_Id'], "int"),
		GetSQLValueString($_POST['iddelmodel'], "text"));
		mysql_select_db($database_conexion, $conexion);
		$Result1 = mysql_query($deleteSQL, $conexion) or die(mysql_error());

		actualiazarCars('removeLike', $_POST['iddelmodel']);
		echo 'Removed';
	}
?>