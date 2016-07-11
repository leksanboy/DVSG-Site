<?php require_once('../../../../Connections/conexion.php');

	if (isset($_POST["titulo"])) {

		$tiempocotejo = time();
		$contenido = $_POST['contenido'];

		$insertSQL = sprintf("UPDATE z_posts SET time=%s, titulo=%s, keywords=%s, contenido=%s, km=%s, power=%s, year=%s, fuel=%s, engine=%s, bodytype=%s, countryVal=%s, country=%s, cityVal=%s, city=%s, prefix=%s, phone=%s, price=%s, currency=%s, autor=%s WHERE id=%s",
		GetSQLValueString($tiempocotejo, "int"),
		GetSQLValueString($_POST['titulo'], "text"),
		GetSQLValueString(genera_key($_POST['titulo']), "text"),
		GetSQLValueString($contenido, "text"),
		GetSQLValueString($_POST['km'], "int"),
		GetSQLValueString($_POST['power'], "int"),
		GetSQLValueString($_POST['year'], "text"),
		GetSQLValueString($_POST['fuel'], "text"),
		GetSQLValueString($_POST['engine'], "text"),
		GetSQLValueString($_POST['bodytype'], "text"),
		GetSQLValueString($_POST['countryVal'], "text"),
		GetSQLValueString($_POST['country'], "text"),
		GetSQLValueString($_POST['cityVal'], "text"),
		GetSQLValueString($_POST['city'], "text"),
		GetSQLValueString($_POST['prefix'], "text"),
		GetSQLValueString($_POST['phone'], "text"),
		GetSQLValueString($_POST['price'], "text"),
		GetSQLValueString($_POST['currency'], "text"),
		GetSQLValueString($_SESSION['MM_Id'], "int"),
		GetSQLValueString($_POST['id'], "int"));


		mysql_select_db($database_conexion, $conexion);
		$Result1 = mysql_query($insertSQL, $conexion) or die(mysql_error());
		  
		mysql_select_db($database_conexion, $conexion);
		$query_SacarIdPost = sprintf("SELECT id FROM z_posts WHERE time=%s",$tiempocotejo,"int");
		$SacarIdPost = mysql_query($query_SacarIdPost, $conexion) or die(mysql_error());
		$row_SacarIdPost = mysql_fetch_assoc($SacarIdPost);
		$totalRows_SacarIdPost = mysql_num_rows($SacarIdPost);
		mysql_free_result($SacarIdPost);

		mysql_select_db($database_conexion, $conexion);
		$query_SacarTemp = sprintf("SELECT * FROM z_post_img_temporal WHERE usuario=%s",$_SESSION['MM_Id'],"int");
		$SacarTemp = mysql_query($query_SacarTemp, $conexion) or die(mysql_error());
		$row_SacarTemp = mysql_fetch_assoc($SacarTemp);
		$totalRows_SacarTemp = mysql_num_rows($SacarTemp);

		$nombreimagenes='';
		if ($totalRows_SacarTemp!=''){
			do {
				$nombreimagenes.=$row_SacarTemp['imagen'].'-:#:-';
			} while ($row_SacarTemp = mysql_fetch_assoc($SacarTemp));

			//BORRADO IMAGENES DE LA BASE de DATOS ASOCIADOS A LA ID
		    $deleteSQL = sprintf("DELETE FROM z_post_img_temporal WHERE usuario=%s",
		    GetSQLValueString($_SESSION['MM_Id'], "text"));
		        
		    mysql_select_db($database_conexion, $conexion);
		    $Result1 = mysql_query($deleteSQL, $conexion) or die(mysql_error());
		}

		$cantidadReal = substr ($nombreimagenes, 0, strlen($nombreimagenes) - 5);
		mysql_free_result($SacarTemp);

		$updateSQL = sprintf("UPDATE z_posts SET urlamigable=%s,imagen1=%s WHERE id=%s",
		GetSQLValueString(limpia_espacios($_POST['titulo'],$row_SacarIdPost['id']), "text"),
		GetSQLValueString($cantidadReal, "text"),
		GetSQLValueString($row_SacarIdPost['id'], "int"));

		mysql_select_db($database_conexion, $conexion);
		$Result1 = mysql_query($updateSQL, $conexion) or die(mysql_error());

		$insertGoTo = $urlWeb.UrlAmigablesInvertida($row_SacarIdPost['id']).".html";
		header('Location:'.$insertGoTo);
	}
?>