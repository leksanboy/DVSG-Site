<?php require_once('Connections/conexion.php');
	if (!isset ($_SESSION['MM_Id'])){
		header("Location: " . $urlWeb );
	}
	if (!isset ($_GET['id'])){
		header("Location: " . $urlWeb );
	}

	$iddelpost = $_GET['id'];

	mysql_select_db($database_conexion, $conexion);
    $query_GetPost = sprintf("SELECT * FROM z_posts WHERE id=%s",$iddelpost,"int");
    $GetPost = mysql_query($query_GetPost, $conexion) or die(mysql_error());
    $row_GetPost = mysql_fetch_assoc($GetPost);
    $totalRows_GetPost = mysql_num_rows($GetPost);

	if ($row_GetPost['autor']!=$_SESSION['MM_Id']){
		header("Location: " . $urlWeb );
	}

	$deleteSQL = sprintf("DELETE FROM z_posts WHERE id=%s",
    GetSQLValueString($iddelpost, "text"));	        
    mysql_select_db($database_conexion, $conexion);
    $Result1 = mysql_query($deleteSQL, $conexion) or die(mysql_error());

    header('Location:'.$urlWeb);
?>