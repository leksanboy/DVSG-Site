<?php
	if (!function_exists("GetSQLValueString")) {
		function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") {

			if (PHP_VERSION < 6) {
				$theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
			}

			$theValue = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($theValue) : mysql_escape_string($theValue);
			switch ($theType) {
				case "text":
					$theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
					break;    
				case "long":
				case "int":
					$theValue = ($theValue != "") ? intval($theValue) : "NULL";
					break;
				case "double":
					$theValue = ($theValue != "") ? doubleval($theValue) : "NULL";
					break;
				case "date":
					$theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
					break;
				case "defined":
					$theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
					break;
			}
			return $theValue;
		}
	}

	//Sacar datos de la web
	mysql_select_db($database_conexion, $conexion);
	$query_SacarDatosWeb = "SELECT * FROM z_datos";
	$SacarDatosWeb = mysql_query($query_SacarDatosWeb, $conexion) or die(mysql_error());
	$row_SacarDatosWeb = mysql_fetch_assoc($SacarDatosWeb);
	$totalRows_SacarDatosWeb = mysql_num_rows($SacarDatosWeb);
	$urlWeb=$row_SacarDatosWeb['url'];
	$nombreWeb=$row_SacarDatosWeb['nombre'];
	$emailAdmin=$row_SacarDatosWeb['email'];
	mysql_free_result($SacarDatosWeb);

	//FORMATO DE CARACTERES
	//header('Content-Type: text/html; charset=ISO-8859-1');
	//Importantísimo para que se muestren bien los acentos en español dentro del template!
	mysql_query("SET NAMES 'utf8'");
	
	//Usuarios online en la web
	if (isset($_SESSION['MM_Id'])) {
		$usuarioconectado= $_SESSION['MM_Id'];
	}else{
		$usuarioconectado=0;	
	}
	$ip = $_SERVER['REMOTE_ADDR'];
	mysql_select_db($database_conexion, $conexion);
	$query_UserOnlineMio = sprintf("SELECT * FROM z_online WHERE z_online.ip = %s", GetSQLValueString($ip, "text"));
	$UserOnlineMio = mysql_query($query_UserOnlineMio, $conexion) or die(mysql_error());
	$row_UserOnlineMio = mysql_fetch_assoc($UserOnlineMio);
	$totalRows_UserOnlineMio = mysql_num_rows($UserOnlineMio);

	if ($totalRows_UserOnlineMio ==0){
			
		$insertSQL = sprintf("INSERT INTO z_online (date, usuarioOnline, ip) VALUES (%s, %s, %s)",
			GetSQLValueString(time(), "text"),
			GetSQLValueString($usuarioconectado, "int"),
			GetSQLValueString($_SERVER['REMOTE_ADDR'], "text"));

		mysql_select_db($database_conexion, $conexion);
		$Result1 = mysql_query($insertSQL, $conexion) or die(mysql_error());
	}else {
		$updateSQL = sprintf("UPDATE z_online SET date = %s, usuarioOnline = %s WHERE ip=%s",
			GetSQLValueString (time(), "int"),
			GetSQLValueString ($usuarioconectado, "int"),
			GetSQLValueString($_SERVER['REMOTE_ADDR'], "text"));

		mysql_select_db($database_conexion, $conexion);
		$Result1 = mysql_query($updateSQL, $conexion) or die(mysql_error());
	}

	$time = 5 ;
	$date = time() ;
	$limite = $date-$time*60 ;
	$deleteSQL = sprintf("DELETE FROM z_online WHERE date < %s",
	GetSQLValueString($limite, "int"));
	mysql_select_db($database_conexion, $conexion);
	$Result1 = mysql_query($deleteSQL, $conexion) or die(mysql_error());
			
	mysql_select_db($database_conexion, $conexion);
	$query_UserOnlineMio = "SELECT * FROM z_online";
	$UserOnlineMio = mysql_query($query_UserOnlineMio, $conexion) or die(mysql_error());
	$row_UserOnlineMio = mysql_fetch_assoc($UserOnlineMio);
	$totalRows_UserOnlineMio = mysql_num_rows($UserOnlineMio);
	mysql_free_result($UserOnlineMio);


	// Get user avatar
	function userAvatar($idUser){
		global $database_conexion, $conexion;
		mysql_select_db($database_conexion, $conexion);
		$query_GetData = sprintf ("SELECT z_users.avatar FROM z_users WHERE z_users.id = %s", $idUser, "int");
		$GetData = mysql_query($query_GetData, $conexion) or die(mysql_error());
		$row_GetData = mysql_fetch_assoc($GetData);
		$totalRows_GetData = mysql_num_rows($GetData);
		
		return $row_GetData['avatar'];
		mysql_free_result($GetData);
	}

	// Get user name
	function userName($idUser){
		global $database_conexion, $conexion;
		mysql_select_db($database_conexion, $conexion);
		$query_GetData = sprintf ("SELECT z_users.nombre FROM z_users WHERE z_users.id = %s", $idUser, "int");
		$GetData = mysql_query($query_GetData, $conexion) or die(mysql_error());
		$row_GetData = mysql_fetch_assoc($GetData);
		$totalRows_GetData = mysql_num_rows($GetData);
		
		return $row_GetData['nombre'];
		mysql_free_result($GetData);
	}

	// Check if user like photo
	function checkLikeUserPhoto($iduser, $idphoto) {
		global $database_conexion, $conexion;
		mysql_select_db($database_conexion, $conexion);
		$query_CheckData = sprintf ("SELECT * FROM z_photos_likes WHERE user = %s AND photo = %s", 
				GetSQLValueString($iduser, "int"),
				GetSQLValueString($idphoto, "int"));
		$CheckData = mysql_query($query_CheckData, $conexion) or die(mysql_error());
		$row_CheckData = mysql_fetch_assoc($CheckData);
		$totalRows_CheckData = mysql_num_rows($CheckData);

		if ($totalRows_CheckData==0) {
			return true;
		} else{
			return false;
		}

		mysql_free_result($CheckData);
	}

	// Count likes user photo
	function countLikesUserPhoto($idphoto) {
		global $database_conexion, $conexion;
		mysql_select_db($database_conexion, $conexion);
		$query_GetCount = sprintf ("SELECT * FROM z_photos_likes WHERE photo = %s",
			GetSQLValueString($idphoto, "int"));
		$GetCount = mysql_query($query_GetCount, $conexion) or die(mysql_error());
		$row_GetCount = mysql_fetch_assoc($GetCount);
		$totalRows_GetCount = mysql_num_rows($GetCount);

		if ($totalRows_GetCount == '')
			$totalRows_GetCount = 0;

		return $totalRows_GetCount;
		mysql_free_result($GetCount);
	}

	// Count comments user photo
	function countCommentsUserPhoto($idphoto) {
		global $database_conexion, $conexion;
		mysql_select_db($database_conexion, $conexion);
		$query_GetCount = sprintf ("SELECT * FROM z_photos_comments WHERE photo = %s",
			GetSQLValueString($idphoto, "int"));
		$GetCount = mysql_query($query_GetCount, $conexion) or die(mysql_error());
		$row_GetCount = mysql_fetch_assoc($GetCount);
		$totalRows_GetCount = mysql_num_rows($GetCount);

		if ($totalRows_GetCount == '')
			$totalRows_GetCount = 0;

		return $totalRows_GetCount;
		mysql_free_result($GetCount);
	}

	//Tiempo trascurrido funcion
	function timeAgo($time) {
		$newetime = time() - $time;

	    if ($newetime < 1) {
	        return '1 second ago';
	    }

	    $a = array( 365 * 24 * 60 * 60  =>  'year',
	                 30 * 24 * 60 * 60  =>  'month',
	                      24 * 60 * 60  =>  'day',
	                           60 * 60  =>  'hour',
	                                60  =>  'minute',
	                                 1  =>  'second'
	                );
	    $a_plural = array( 'year'   => 'years',
	                       'month'  => 'months',
	                       'day'    => 'days',
	                       'hour'   => 'hours',
	                       'minute' => 'minutes',
	                       'second' => 'seconds'
	                );

	    foreach ($a as $secs => $str) {
	        $d = $newetime / $secs;

	        if ($d >= 1) {
	            $r = round($d);
	            return $r . ' ' . ($r > 1 ? $a_plural[$str] : $str) . ' ago';
	        }
	    }
	}

















	// ACTUALIZAR LIKES & COMMENTS DEL POST
	function actualiazarPost($accion, $idPost){
		global $database_conexion, $conexion;

		if ($accion == 'addLike') {
			$updateSQL = sprintf("UPDATE z_posts SET likes = (likes + %s) WHERE id=%s",
			GetSQLValueString (1, "int"),
			GetSQLValueString ($idPost, "int"));
		}else if ($accion == 'removeLike') {
			$updateSQL = sprintf("UPDATE z_posts SET likes = (likes - %s) WHERE id=%s",
			GetSQLValueString (1, "int"),
			GetSQLValueString ($idPost, "int"));
		}else if ($accion == 'addComment') {
			$updateSQL = sprintf("UPDATE z_posts SET comments = (comments + %s) WHERE id=%s",
			GetSQLValueString (1, "int"),
			GetSQLValueString ($idPost, "int"));
		}else if ($accion == 'removeComment') {
			$updateSQL = sprintf("UPDATE z_posts SET comments = (comments - %s) WHERE id=%s",
			GetSQLValueString (1, "int"),
			GetSQLValueString ($idPost, "int"));
		}

		mysql_select_db($database_conexion, $conexion);
		$Result1 = mysql_query($updateSQL, $conexion) or die(mysql_error());
	}

	// ACTUALIZAR LIKES & COMMENTS DE CARS
	function actualiazarCars($accion, $idPost){
		global $database_conexion, $conexion;

		if ($accion == 'addLike') {
			$updateSQL = sprintf("UPDATE z_cars_models SET likes = (likes + %s) WHERE id=%s",
			GetSQLValueString (1, "int"),
			GetSQLValueString ($idPost, "int"));
		}else if ($accion == 'removeLike') {
			$updateSQL = sprintf("UPDATE z_cars_models SET likes = (likes - %s) WHERE id=%s",
			GetSQLValueString (1, "int"),
			GetSQLValueString ($idPost, "int"));
		}else if ($accion == 'addComment') {
			$updateSQL = sprintf("UPDATE z_cars_models SET comments = (comments + %s) WHERE id=%s",
			GetSQLValueString (1, "int"),
			GetSQLValueString ($idPost, "int"));
		}else if ($accion == 'removeComment') {
			$updateSQL = sprintf("UPDATE z_cars_models SET comments = (comments - %s) WHERE id=%s",
			GetSQLValueString (1, "int"),
			GetSQLValueString ($idPost, "int"));
		}

		mysql_select_db($database_conexion, $conexion);
		$Result1 = mysql_query($updateSQL, $conexion) or die(mysql_error());
	}

	// MESES
	function meses ($valor){
		if ($valor=='0') return traducir(55,$_COOKIE['idioma']);  
		if ($valor=='1') return traducir(56,$_COOKIE['idioma']);  
		if ($valor=='2') return traducir(57,$_COOKIE['idioma']);  
		if ($valor=='3') return traducir(58,$_COOKIE['idioma']);  
		if ($valor=='4') return traducir(59,$_COOKIE['idioma']);  
		if ($valor=='5') return traducir(60,$_COOKIE['idioma']);  
		if ($valor=='6') return traducir(61,$_COOKIE['idioma']);  
		if ($valor=='7') return traducir(62,$_COOKIE['idioma']);  
		if ($valor=='8') return traducir(63,$_COOKIE['idioma']);  
		if ($valor=='9') return traducir(64,$_COOKIE['idioma']);  
		if ($valor=='10') return traducir(65,$_COOKIE['idioma']); 
		if ($valor=='11') return traducir(66,$_COOKIE['idioma']); 
	}

	// SACAR EL AUTOR DE LA IMAGEN
	function autor_imagen ($nombreimg){

		global $database_conexion, $conexion;
		mysql_select_db($database_conexion, $conexion);
		$query_MisPhotos = sprintf("SELECT * FROM z_my_photos WHERE id=%s",
		GetSQLValueString($nombreimg,"text"));
		$MisPhotos = mysql_query($query_MisPhotos, $conexion) or die(mysql_error());
		$row_MisPhotos = mysql_fetch_assoc($MisPhotos);
		$totalRows_MisPhotos = mysql_num_rows($MisPhotos);

		return $row_MisPhotos['autor'];
		mysql_free_result($MisPhotos);

	}
	// MESES
	function sacar_idimagen ($nombreimg){

		global $database_conexion, $conexion;
		mysql_select_db($database_conexion, $conexion);
		$query_MisPhotos = sprintf("SELECT * FROM z_my_photos WHERE nombre=%s",
			GetSQLValueString($nombreimg,"text"));
		$MisPhotos = mysql_query($query_MisPhotos, $conexion) or die(mysql_error());
		$row_MisPhotos = mysql_fetch_assoc($MisPhotos);
		$totalRows_MisPhotos = mysql_num_rows($MisPhotos);

		return $row_MisPhotos['id'];
		mysql_free_result($MisPhotos);
	}

	//Obtener id de usuario que esta online
	function onlinusers($id){

		global $database_conexion, $conexion;
		mysql_select_db($database_conexion, $conexion);
		$query_SacarUsuarioOn = sprintf("SELECT * FROM z_online WHERE z_online.usuarioOnline = %s", GetSQLValueString($id, "int"));
		$SacarUsuarioOn = mysql_query($query_SacarUsuarioOn, $conexion) or die(mysql_error());
		$row_SacarUsuarioOn = mysql_fetch_assoc($SacarUsuarioOn);
		$totalRows_SacarUsuarioOn = mysql_num_rows($SacarUsuarioOn);

		return $totalRows_SacarUsuarioOn;
		mysql_free_result($SacarUsuarioOn);

	}	
	//Url amigables
	function UrlAmigables($seo) {
		
		global $database_conexion, $conexion;
		mysql_select_db($database_conexion, $conexion);
		$query_Recordset13434 = sprintf ("SELECT z_posts.id FROM z_posts WHERE z_posts.urlamigable = '%s'" ,$seo);
		$Recordset13434 = mysql_query($query_Recordset13434, $conexion) or die(mysql_error());
		$row_Recordset13434 = mysql_fetch_assoc($Recordset13434);
		$totalRows_Recordset13434 = mysql_num_rows($Recordset13434);

		return $row_Recordset13434['id'];
		mysql_free_result($Recordset13434);
	 
	}
	//Url amigables Brands
	function UrlAmigableBrands($seo) {
		
		global $database_conexion, $conexion;
		mysql_select_db($database_conexion, $conexion);
		$query_Recordset1343456789 = sprintf ("SELECT z_cars_brands.id FROM z_cars_brands WHERE z_cars_brands.url = '%s'" ,$seo);
		$Recordset1343456789 = mysql_query($query_Recordset1343456789, $conexion) or die(mysql_error());
		$row_Recordset1343456789 = mysql_fetch_assoc($Recordset1343456789);
		$totalRows_Recordset1343456789 = mysql_num_rows($Recordset1343456789);

		return $row_Recordset1343456789['id'];
		mysql_free_result($Recordset1343456789);
	}

	//Url amigables Brands
	function UrlAmigableModel($seo) {
		
		global $database_conexion, $conexion;
		mysql_select_db($database_conexion, $conexion);
		$query_Recordset1343456789 = sprintf ("SELECT z_cars_models.id FROM z_cars_models WHERE z_cars_models.url = '%s'" ,$seo);
		$Recordset1343456789 = mysql_query($query_Recordset1343456789, $conexion) or die(mysql_error());
		$row_Recordset1343456789 = mysql_fetch_assoc($Recordset1343456789);
		$totalRows_Recordset1343456789 = mysql_num_rows($Recordset1343456789);

		return $row_Recordset1343456789['id'];
		mysql_free_result($Recordset1343456789);
	}

	//Url amigables Brands
	function GetCarsBrand($seo) {
		
		global $database_conexion, $conexion;
		mysql_select_db($database_conexion, $conexion);
		$query_Recordset1343456789 = sprintf ("SELECT z_cars_brands.brand FROM z_cars_brands WHERE z_cars_brands.id = '%s'" ,$seo);
		$Recordset1343456789 = mysql_query($query_Recordset1343456789, $conexion) or die(mysql_error());
		$row_Recordset1343456789 = mysql_fetch_assoc($Recordset1343456789);
		$totalRows_Recordset1343456789 = mysql_num_rows($Recordset1343456789);

		return $row_Recordset1343456789['brand'];
		mysql_free_result($Recordset1343456789);
	}

	//Url amigables Brands
	function GetCarsModelBrand($seo) {
		
		global $database_conexion, $conexion;
		mysql_select_db($database_conexion, $conexion);
		$query_Recordset1343456789 = sprintf ("SELECT z_cars_models.brand FROM z_cars_models WHERE z_cars_models.id = '%s'" ,$seo);
		$Recordset1343456789 = mysql_query($query_Recordset1343456789, $conexion) or die(mysql_error());
		$row_Recordset1343456789 = mysql_fetch_assoc($Recordset1343456789);
		$totalRows_Recordset1343456789 = mysql_num_rows($Recordset1343456789);

		return $row_Recordset1343456789['brand'];
		mysql_free_result($Recordset1343456789);
	}
	
	//SacarImagen($id)
	function SacarImagen($nombre_archivo1) {
		
		global $database_conexion, $conexion;
		mysql_select_db($database_conexion, $conexion);
		$query_SacarImgGet = sprintf("SELECT z_posts.imagen1 FROM z_posts WHERE z_posts.id = %s",$nombre_archivo1,"text");
		$SacarImgGet = mysql_query($query_SacarImgGet, $conexion) or die(mysql_error());
		$row_SacarImgGet = mysql_fetch_assoc($SacarImgGet);
		$totalRows_SacarImgGet = mysql_num_rows($SacarImgGet);

		return $row_SacarImgGet['imagen1'];
		mysql_free_result($SacarImgGet);
	 
	} 
	//Sacar VIDEO($id)
	function saber_videopost($nombre_archivo1) {
		
		global $database_conexion, $conexion;
		mysql_select_db($database_conexion, $conexion);
		$query_SacarImgGet = sprintf("SELECT z_posts.imagen1 FROM z_posts WHERE z_posts.id = %s",$nombre_archivo1,"text");
		$SacarImgGet = mysql_query($query_SacarImgGet, $conexion) or die(mysql_error());
		$row_SacarImgGet = mysql_fetch_assoc($SacarImgGet);
		$totalRows_SacarImgGet = mysql_num_rows($SacarImgGet);

		return $row_SacarImgGet['imagen1'];
		mysql_free_result($SacarImgGet);
	 
	}
	// Auto-links
	function auto_link($text) {

		$pattern = "/(((http[s]?:\/\/)|(www\.))(([a-z][-a-z0-9]+\.)?[a-z][-a-z0-9]+\.[a-z]+(\.[a-z]{2,2})?)\/?[a-z0-9.,_\/~#&=:;%+?-]+[a-z0-9\/#=?]{1,1})/is";
		$text = preg_replace($pattern, " <a href='$1' target='_blank'>$1</a>", $text);
		// fix URLs without protocols
		$text = preg_replace("/href='www/", "href='http://www", $text);
		return $text;

	}
	//SacarNumero de comentarios($id)
	function numcoment($numcoment) {

		global $database_conexion, $conexion;
		mysql_select_db($database_conexion, $conexion);
		$query_NumComent = sprintf ("SELECT * FROM z_coment WHERE z_coment.post = %s", GetSQLValueString($numcoment, "int"));
		$NumComent = mysql_query($query_NumComent, $conexion) or die(mysql_error());
		$row_NumComent = mysql_fetch_assoc($NumComent);
		$totalRows_NumComent = mysql_num_rows($NumComent);

		return $totalRows_NumComent;
		mysql_free_result($NumComent);

	}

	//SacarNumero de photos($id)
	function count_comm_photos($count_comm) {

		global $database_conexion, $conexion;
		mysql_select_db($database_conexion, $conexion);
		$query_NumComent = sprintf ("SELECT * FROM z_comentarios_fotos WHERE z_comentarios_fotos.name_img = %s", GetSQLValueString($count_comm, "text"));
		$NumComent = mysql_query($query_NumComent, $conexion) or die(mysql_error());
		$row_NumComent = mysql_fetch_assoc($NumComent);
		$totalRows_NumComent = mysql_num_rows($NumComent);

		return $totalRows_NumComent;
		mysql_free_result($NumComent);
	}

	function count_likes_photos($count_like) {

		global $database_conexion, $conexion;
		mysql_select_db($database_conexion, $conexion);
		$query_NumComent = sprintf ("SELECT * FROM z_images_likes WHERE z_images_likes.idimagen = %s", GetSQLValueString($count_like, "int"));
		$NumComent = mysql_query($query_NumComent, $conexion) or die(mysql_error());
		$row_NumComent = mysql_fetch_assoc($NumComent);
		$totalRows_NumComent = mysql_num_rows($NumComent);

		return $totalRows_NumComent;
		mysql_free_result($NumComent);
	}


	function count_likes_post($count_like) {

		global $database_conexion, $conexion;
		mysql_select_db($database_conexion, $conexion);
		$query_NumComent = sprintf ("SELECT * FROM z_puntos WHERE post = %s", GetSQLValueString($count_like, "int"));
		$NumComent = mysql_query($query_NumComent, $conexion) or die(mysql_error());
		$row_NumComent = mysql_fetch_assoc($NumComent);
		$totalRows_NumComent = mysql_num_rows($NumComent);

		return $totalRows_NumComent;
		mysql_free_result($NumComent);
	}

	function comprobacion_like_user_post($iduser, $idpost) {
		global $database_conexion, $conexion;
		mysql_select_db($database_conexion, $conexion);
		$query_NumComent = sprintf ("SELECT * FROM z_puntos WHERE usuario = %s AND post = %s",
				GetSQLValueString($iduser, "int"),
				GetSQLValueString($idpost, "text"));
		$NumComent = mysql_query($query_NumComent, $conexion) or die(mysql_error());
		$row_NumComent = mysql_fetch_assoc($NumComent);
		$totalRows_NumComent = mysql_num_rows($NumComent);

		if ($totalRows_NumComent==0) {

			return true;
		 	

		} else{

			return false;
		}

		mysql_free_result($NumComent);
	}

	//·····> Comprobacione Likes Cars
	function comprobacionLikesCars($iduser, $idpage) {
		global $database_conexion, $conexion;
		mysql_select_db($database_conexion, $conexion);
		$query_NumComent = sprintf ("SELECT * FROM z_cars_likes WHERE user = %s AND page = %s",
		GetSQLValueString($iduser, "int"),
		GetSQLValueString($idpage, "text"));
		$NumComent = mysql_query($query_NumComent, $conexion) or die(mysql_error());
		$row_NumComent = mysql_fetch_assoc($NumComent);
		$totalRows_NumComent = mysql_num_rows($NumComent);

		if ($totalRows_NumComent==0) {
			return true;
		} else{
			return false;
		}

		mysql_free_result($NumComent);
	}

	//·····> Count Likes Cars
	function countLikesCars($count_like) {
		global $database_conexion, $conexion;
		mysql_select_db($database_conexion, $conexion);
		$query_NumComent = sprintf ("SELECT * FROM z_cars_likes WHERE page = %s", GetSQLValueString($count_like, "int"));
		$NumComent = mysql_query($query_NumComent, $conexion) or die(mysql_error());
		$row_NumComent = mysql_fetch_assoc($NumComent);
		$totalRows_NumComent = mysql_num_rows($NumComent);

		return $totalRows_NumComent;
		mysql_free_result($NumComent);
	}

	//·····> Comprobacione Likes Posts
	function comprobacionLikesPosts($iduser, $idpage) {
		global $database_conexion, $conexion;
		mysql_select_db($database_conexion, $conexion);
		$query_NumComent = sprintf ("SELECT * FROM z_posts_likes WHERE user = %s AND page = %s",
		GetSQLValueString($iduser, "int"),
		GetSQLValueString($idpage, "text"));
		$NumComent = mysql_query($query_NumComent, $conexion) or die(mysql_error());
		$row_NumComent = mysql_fetch_assoc($NumComent);
		$totalRows_NumComent = mysql_num_rows($NumComent);

		if ($totalRows_NumComent==0) {
			return true;
		} else{
			return false;
		}

		mysql_free_result($NumComent);
	}

	//·····> Count Likes Posts
	function countLikesPosts($count_like) {
		global $database_conexion, $conexion;
		mysql_select_db($database_conexion, $conexion);
		$query_NumComent = sprintf ("SELECT * FROM z_posts_likes WHERE page = %s", GetSQLValueString($count_like, "int"));
		$NumComent = mysql_query($query_NumComent, $conexion) or die(mysql_error());
		$row_NumComent = mysql_fetch_assoc($NumComent);
		$totalRows_NumComent = mysql_num_rows($NumComent);

		return $totalRows_NumComent;
		mysql_free_result($NumComent);
	}

	//·····> Count Comments Posts
	function countCommentsPosts($count_like) {
		global $database_conexion, $conexion;
		mysql_select_db($database_conexion, $conexion);
		$query_NumComent = sprintf ("SELECT * FROM z_posts_comments WHERE page = %s", GetSQLValueString($count_like, "int"));
		$NumComent = mysql_query($query_NumComent, $conexion) or die(mysql_error());
		$row_NumComent = mysql_fetch_assoc($NumComent);
		$totalRows_NumComent = mysql_num_rows($NumComent);

		return $totalRows_NumComent;
		mysql_free_result($NumComent);
	}

	//SacarNumero de Canciones
	function numcanciones($SacarEnviadosMusica) {

		global $database_conexion, $conexion;
		mysql_select_db($database_conexion, $conexion);
		$query_SacarEnviadosMusica = sprintf ("SELECT * FROM z_muz_favoritos WHERE z_muz_favoritos.usuario = %s", GetSQLValueString($SacarEnviadosMusica, "text"));
		$SacarEnviadosMusica = mysql_query($query_SacarEnviadosMusica, $conexion) or die(mysql_error());
		$row_SacarEnviadosMusica = mysql_fetch_assoc($SacarEnviadosMusica);
		$totalRows_SacarEnviadosMusica = mysql_num_rows($SacarEnviadosMusica);

		return $totalRows_SacarEnviadosMusica;
		mysql_free_result($SacarEnviadosMusica);

	}
	//SacarNumero de Videoss
	function numvideos($SacarEnviadosMusica) {

		global $database_conexion, $conexion;
		mysql_select_db($database_conexion, $conexion);
		$query_SacarEnviadosMusica = sprintf ("SELECT * FROM z_vid_favoritos WHERE z_vid_favoritos.usuario = %s", GetSQLValueString($SacarEnviadosMusica, "text"));
		$SacarEnviadosMusica = mysql_query($query_SacarEnviadosMusica, $conexion) or die(mysql_error());
		$row_SacarEnviadosMusica = mysql_fetch_assoc($SacarEnviadosMusica);
		$totalRows_SacarEnviadosMusica = mysql_num_rows($SacarEnviadosMusica);

		return $totalRows_SacarEnviadosMusica;
		mysql_free_result($SacarEnviadosMusica);

	}
	//Url amigables
	function UrlAmigablesInvertida($seo) {
		
		global $database_conexion, $conexion;
		mysql_select_db($database_conexion, $conexion);
		$query_Recordset13434 = sprintf ("SELECT z_posts.urlamigable FROM z_posts WHERE z_posts.id = '%s'" ,$seo);
		$Recordset13434 = mysql_query($query_Recordset13434, $conexion) or die(mysql_error());
		$row_Recordset13434 = mysql_fetch_assoc($Recordset13434);
		$totalRows_Recordset13434 = mysql_num_rows($Recordset13434);

		return $row_Recordset13434['urlamigable'];
		mysql_free_result($Recordset13434);

	} 
	//Saber si ya esta en favoritos
	function ya_en_favoritos($usuario, $idpost) {

		global $database_conexion, $conexion;
		mysql_select_db($database_conexion, $conexion);
		$query_DatosFuncion = sprintf("SELECT * FROM z_favori WHERE usuario=%s AND post = %s", 
			GetSQLValueString($usuario, "int"),
			GetSQLValueString($idpost, "int"));
		$DatosFuncion = mysql_query($query_DatosFuncion, $conexion) or die(mysql_error());
		$row_DatosFuncion = mysql_fetch_assoc($DatosFuncion);
		$totalRows_DatosFuncion = mysql_num_rows($DatosFuncion);
		
		if ($totalRows_DatosFuncion=="") return true;
		else
		return false;

		mysql_free_result($DatosFuncion);

	}
	//Saber POST AMIGOS
	function saber_amigos_muro($envia,$recibe) {

		global $database_conexion, $conexion;
		mysql_select_db($database_conexion, $conexion);
		$query_SacarAmigosDentro = sprintf("SELECT * FROM z_friends WHERE (sender = %s AND receiver = %s) OR (sender = %s AND receiver = %s) AND status=1",
		$envia,$recibe,$recibe,$envia);

		$SacarAmigosDentro = mysql_query($query_SacarAmigosDentro, $conexion) or die(mysql_error());
		$row_SacarAmigosDentro = mysql_fetch_assoc($SacarAmigosDentro);
		$totalRows_SacarAmigosDentro = mysql_num_rows($SacarAmigosDentro);

	 
		if ($totalRows_SacarAmigosDentro==1) return 'ok';
		else
		return 'no';

		mysql_free_result($SacarAmigosDentro); 

	}
	//Saber si ya esta en My Videos
	function ya_en_videos_favoritos($usuario, $idpost) {

		global $database_conexion, $conexion;
		mysql_select_db($database_conexion, $conexion);
		$query_DatosFuncion = sprintf("SELECT * FROM z_vid_favoritos WHERE usuario=%s AND post = %s", 
			GetSQLValueString($usuario, "int"),
			GetSQLValueString($idpost, "int"));
		$DatosFuncion = mysql_query($query_DatosFuncion, $conexion) or die(mysql_error());
		$row_DatosFuncion = mysql_fetch_assoc($DatosFuncion);
		$totalRows_DatosFuncion = mysql_num_rows($DatosFuncion);
		
		if ($totalRows_DatosFuncion=="") return true;
		else
		return false;

		mysql_free_result($DatosFuncion);

	}
	//Saber si ya esta en My Music
	function ya_en_musica_favoritos($usuario, $nombre){

		global $database_conexion, $conexion;
		mysql_select_db($database_conexion, $conexion);
		$query_DatosFuncion = sprintf("SELECT * FROM z_muz_favoritos WHERE usuario=%s AND nombre=%s", 
			GetSQLValueString($usuario, "int"),
			GetSQLValueString($nombre, "text"));
		$DatosFuncion = mysql_query($query_DatosFuncion, $conexion) or die(mysql_error());
		$row_DatosFuncion = mysql_fetch_assoc($DatosFuncion);
		$totalRows_DatosFuncion = mysql_num_rows($DatosFuncion);
		
		if ($totalRows_DatosFuncion=="") return true;
		else
		return false;

		mysql_free_result($DatosFuncion);

	}
	//Saber si ya han votado
	function YahasVotado($idvotante, $idppost){

		global $database_conexion, $conexion;
		mysql_select_db($database_conexion, $conexion);
		$query_DatosFuncion = sprintf("SELECT * FROM z_puntos WHERE usuario = %s AND post = %s", 
			GetSQLValueString($idvotante, "int"),
			GetSQLValueString($idppost, "int"));
		$DatosFuncion = mysql_query($query_DatosFuncion, $conexion) or die(mysql_error());
		$row_DatosFuncion = mysql_fetch_assoc($DatosFuncion);
		$totalRows_DatosFuncion = mysql_num_rows($DatosFuncion);
		
		if ($totalRows_DatosFuncion =="") return true;
		else
		return false;
		
		mysql_free_result($DatosFuncion);

	}
	//Saber si ya han Pagina
	function YahasVotadoPagina($idvotante, $idppost){

		global $database_conexion, $conexion;
		mysql_select_db($database_conexion, $conexion);
		$query_DatosFuncion = sprintf("SELECT * FROM z_puntos_pagina WHERE usuario = %s AND pagina = %s", 
			GetSQLValueString($idvotante, "int"),
			GetSQLValueString($idppost, "text"));
		$DatosFuncion = mysql_query($query_DatosFuncion, $conexion) or die(mysql_error());
		$row_DatosFuncion = mysql_fetch_assoc($DatosFuncion);
		$totalRows_DatosFuncion = mysql_num_rows($DatosFuncion);
		
		if ($totalRows_DatosFuncion =="") return true;
		else
		return false;
		
		mysql_free_result($DatosFuncion);

	}
	// Quitar tildes y acentos para las keywords 
	function genera_key($keywords){

		$keywords = str_replace(' ', ', ', $keywords);
		$keywords = str_replace('?', '', $keywords);
		$keywords = str_replace('+', '', $keywords);
		$keywords = str_replace('??', '', $keywords);
		$keywords = str_replace('`', '', $keywords);
		$keywords = str_replace('!', '', $keywords);
		$keywords = str_replace('¿', '', $keywords);
		$originaleskey = 'ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÚÛÜÝÞßàáâãäåæçèéêëìíîïðñòóôõöøùúûýýþÿŔŕ';
		$modificadaskey = 'aaaaaaaceeeeiiiidnoooooouuuuybsaaaaaaaceeeeiiiidnoooooouuuyybyRr';
		$keywords = strtr($keywords, utf8_decode($originaleskey), $modificadaskey);
		$keywords = strtolower($keywords);

		return utf8_encode($keywords);

	}
	// Quitar tildes y acentos para las urls
	function limpia_espacios($cadena, $idpost){

		$cadena = str_replace(' ', '-', $cadena);
		$cadena = str_replace('?', '', $cadena);
		$cadena = str_replace('+', '', $cadena);
		$cadena = str_replace(':', '', $cadena);
		$cadena = str_replace('??', '', $cadena);
		$cadena = str_replace('`', '', $cadena);
		$cadena = str_replace('!', '', $cadena);
		$cadena = str_replace('¿', '', $cadena);
		$originales = 'ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÚÛÜÝÞßàáâãäåæçèéêëìíîïðñòóôõöøùúûýýþÿŔŕ';
		$modificadas = 'aaaaaaaceeeeiiiidnoooooouuuuybsaaaaaaaceeeeiiiidnoooooouuuyybyRr';
		$cadena = strtr($cadena, utf8_decode($originales), $modificadas);
		$cadena = strtolower($idpost."/".$cadena);

		return utf8_encode($cadena);

	}
	//Saber mensajes sin leer de cada user
	function saber_cuantos_estan_sin_leer ($idusuario) {
		
		global $database_conexion, $conexion;
		mysql_select_db($database_conexion, $conexion);
		$query_DatosFuncion = sprintf("SELECT * FROM z_messages WHERE estado=0 AND recibe = %s", GetSQLValueString($idusuario, "int"));
		$DatosFuncion = mysql_query($query_DatosFuncion, $conexion) or die(mysql_error());
		$row_DatosFuncion = mysql_fetch_assoc($DatosFuncion);
		$totalRows_DatosFuncion = mysql_num_rows($DatosFuncion);
		
		return $totalRows_DatosFuncion;
		mysql_free_result($DatosFuncion);

	}
	//Saber si hay un mensaje pendiente
	function MensajesPendientes($usuario){

		global $database_conexion, $conexion;
		mysql_select_db($database_conexion, $conexion);
		$query_DatosFuncion = sprintf("SELECT id FROM z_messages WHERE estado=0 AND recibe = %s", GetSQLValueString($usuario, "int"));
		$DatosFuncion = mysql_query($query_DatosFuncion, $conexion) or die(mysql_error());
		$row_DatosFuncion = mysql_fetch_assoc($DatosFuncion);
		$totalRows_DatosFuncion = mysql_num_rows($DatosFuncion);
		
		if ($totalRows_DatosFuncion>0) return true;
		else
		return false;

		mysql_free_result($DatosFuncion);

	}
	//Saber mensajes sin leer de cada user
	function saber_cuantos_amigos_estan_sin_confirmar ($idusuario) {
		
		global $database_conexion, $conexion;
		mysql_select_db($database_conexion, $conexion);
		$query_DatosFuncion = sprintf("SELECT * FROM z_friends WHERE status=0 AND receiver = %s", GetSQLValueString($idusuario, "int"));
		$DatosFuncion = mysql_query($query_DatosFuncion, $conexion) or die(mysql_error());
		$row_DatosFuncion = mysql_fetch_assoc($DatosFuncion);
		$totalRows_DatosFuncion = mysql_num_rows($DatosFuncion);

		return $totalRows_DatosFuncion;
		mysql_free_result($DatosFuncion);
	}

	/////Saber si hay un AMIGO pendiente
	function AmigosPendientes($usuario){

		global $database_conexion, $conexion;
		mysql_select_db($database_conexion, $conexion);
		$query_DatosFuncion = sprintf("SELECT id FROM z_friends WHERE status=0 AND receiver = %s", GetSQLValueString($usuario, "int"));
		$DatosFuncion = mysql_query($query_DatosFuncion, $conexion) or die(mysql_error());
		$row_DatosFuncion = mysql_fetch_assoc($DatosFuncion);
		$totalRows_DatosFuncion = mysql_num_rows($DatosFuncion);
		
		if ($totalRows_DatosFuncion>0) return true;
		else
		return false;

		mysql_free_result($DatosFuncion);

	}
	// Saber si hay notificaciones pendientes
	function NotificacionesPendientes($usuario){

		global $database_conexion, $conexion;
		mysql_select_db($database_conexion, $conexion);
		$query_DatosFuncion = sprintf("SELECT * FROM z_notifica WHERE autor = %s AND estado=0", GetSQLValueString($usuario, "int"));
		$DatosFuncion = mysql_query($query_DatosFuncion, $conexion) or die(mysql_error());
		$row_DatosFuncion = mysql_fetch_assoc($DatosFuncion);
		$totalRows_DatosFuncion = mysql_num_rows($DatosFuncion);
		
		if ($totalRows_DatosFuncion>0) return true;
		else
		return false;

		mysql_free_result($DatosFuncion);

	}
	//Enviar un correo de mensaje nuevo
	function notificar_por_correo ($emailderecibe,$urlWeb,$nombreWeb){
		
		$para  = $emailderecibe; 
		$titulo = 'Nueva message '.$nombreWeb;
		$mensaje ="
			<html>
			<head>
			<style type='text/css'>
			@font-face {
				font-family: 'Open Sans';
				font-style: normal;
				font-weight: 400;
				src: local('Open Sans'), local('OpenSans'), url(http://themes.googleusercontent.com/static/fonts/opensans/v6/cJZKeOuBrn4kERxqtaUH3T8E0i7KZn-EPnyo3HZu7kw.woff) format('woff');
			}
			body {
			 color: #333;
			 font-family: 'Open Sans', sans-serif;
			 margin: 0px;
			 font-size: 16px;
			}
			.pie {
			 font-size:12px;
			 color:#999797;
			}
			.centro {
			 font-size:16px;
			}
			.centro a{
			 text-decoration:none;
			 color: #0487b8;
			}
			.centro a:hover{
			 text-decoration: underline;
			 color: #0487b8;
			}
			</style>
			</head>
			<body>
			<table width='593' height='324' border='0' align='center'>
				<tr>
					<td height='23'>&nbsp;</td>
				</tr>
				<tr>
					<td height='88'><img src='http://i.imgur.com/K1Z4rP4.png' width='115' height='40' /></td>
				</tr>
				<tr>
					<td height='97' valign='top' class='centro'><h3>Nueva actividad en ".$nombreWeb."
					</h3>
				 Alguien te a mencionado en un comentario <a href='".$urlWeb."'>".$urlWeb."</a></td>
				</tr>
				<tr>
					<td height='17' ></td>
				</tr>
				<tr>
					<td height='27' class='pie'>Este email es una notificaci&oacute;n autom&aacute;tica</td>
				</tr>
			</table>
			</body>
			</html>
		";
		$cabeceras  = 'MIME-Version: 1.0' . "\r\n";
		$cabeceras .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
		$cabeceras .= "From: DvsG" . "\r\n" .
						'Reply-To: no_reply@dvsg.co' . "\r\n";

		mail($para, $titulo, $mensaje, $cabeceras);

	}
	//Sacar nombre a partir de id usuario
	function nombre ($iduser){
		
		global $database_conexion, $conexion;
		mysql_select_db($database_conexion, $conexion);
		$query_ObtenerNombre = sprintf ("SELECT z_users.nombre FROM z_users WHERE z_users.id = %s",$iduser,"int");
		$ObtenerNombre = mysql_query($query_ObtenerNombre, $conexion) or die(mysql_error());
		$row_ObtenerNombre = mysql_fetch_assoc($ObtenerNombre);
		$totalRows_ObtenerNombre = mysql_num_rows($ObtenerNombre);

		return $row_ObtenerNombre['nombre'];
		mysql_free_result($ObtenerNombre);
		
	}
	//Sacar apellido a partir de id usuario
	function apellido ($iduser){
		
		global $database_conexion, $conexion;
		mysql_select_db($database_conexion, $conexion);
		$query_ObtenerNombre = sprintf ("SELECT z_users.apellido FROM z_users WHERE z_users.id = %s",$iduser,"int");
		$ObtenerNombre = mysql_query($query_ObtenerNombre, $conexion) or die(mysql_error());
		$row_ObtenerNombre = mysql_fetch_assoc($ObtenerNombre);
		$totalRows_ObtenerNombre = mysql_num_rows($ObtenerNombre);
				
		return $row_ObtenerNombre['apellido'];
		mysql_free_result($ObtenerNombre);
		
	}
	//Sacar nombre y apellido a partir de id usuario
	function nombreapellido ($iduser){
		
		global $database_conexion, $conexion;
		mysql_select_db($database_conexion, $conexion);
		$query_ObtenerNombre = sprintf ("SELECT z_users.nombreapellido FROM z_users WHERE z_users.id = %s",$iduser,"int");
		$ObtenerNombre = mysql_query($query_ObtenerNombre, $conexion) or die(mysql_error());
		$row_ObtenerNombre = mysql_fetch_assoc($ObtenerNombre);
		$totalRows_ObtenerNombre = mysql_num_rows($ObtenerNombre);

		return $row_ObtenerNombre['nombreapellido'];
		mysql_free_result($ObtenerNombre);
		
	}
	//Sacar id a partir de id usuario
	function id_user ($iduser){
		
		global $database_conexion, $conexion;
		mysql_select_db($database_conexion, $conexion);
		$query_ObtenerNombre = sprintf ("SELECT z_users.id FROM z_users WHERE z_users.id = %s",$iduser,"int");
		$ObtenerNombre = mysql_query($query_ObtenerNombre, $conexion) or die(mysql_error());
		$row_ObtenerNombre = mysql_fetch_assoc($ObtenerNombre);
		$totalRows_ObtenerNombre = mysql_num_rows($ObtenerNombre);
				
		return $row_ObtenerNombre['id'];
		mysql_free_result($ObtenerNombre);
		
	}
	//Sacar nombre a partir de id usuario
	function city ($iduser){
		
		global $database_conexion, $conexion;
		mysql_select_db($database_conexion, $conexion);
		$query_ObtenerNombre = sprintf ("SELECT z_users.city FROM z_users WHERE z_users.id = %s",$iduser,"int");
		$ObtenerNombre = mysql_query($query_ObtenerNombre, $conexion) or die(mysql_error());
		$row_ObtenerNombre = mysql_fetch_assoc($ObtenerNombre);
		$totalRows_ObtenerNombre = mysql_num_rows($ObtenerNombre);
				
		return $row_ObtenerNombre['city'];
		mysql_free_result($ObtenerNombre);
		
	}
	//Sacar rango a partir de id usuario
	function rango_admin ($iduser){
		
		global $database_conexion, $conexion;
		mysql_select_db($database_conexion, $conexion);
		$query_ObtenerNombre = sprintf ("SELECT z_users.rango FROM z_users WHERE z_users.id = %s",$iduser,"int");
		$ObtenerNombre = mysql_query($query_ObtenerNombre, $conexion) or die(mysql_error());
		$row_ObtenerNombre = mysql_fetch_assoc($ObtenerNombre);
		$totalRows_ObtenerNombre = mysql_num_rows($ObtenerNombre);
		
		return $row_ObtenerNombre['rango'];
		mysql_free_result($ObtenerNombre);
		
	}
	//Sacar email a partir de id usuario
	function email ($iduser){
		
		global $database_conexion, $conexion;
		mysql_select_db($database_conexion, $conexion);
		$query_ObtenerNombre = sprintf ("SELECT z_users.email FROM z_users WHERE z_users.id = %s",$iduser,"int");
		$ObtenerNombre = mysql_query($query_ObtenerNombre, $conexion) or die(mysql_error());
		$row_ObtenerNombre = mysql_fetch_assoc($ObtenerNombre);
		$totalRows_ObtenerNombre = mysql_num_rows($ObtenerNombre);
		
		return $row_ObtenerNombre['email'];
		mysql_free_result($ObtenerNombre);
		
	}
	//Sacar avatar a partir de id usuario
	function avatar_user($iduser){
		
		global $database_conexion, $conexion;
		mysql_select_db($database_conexion, $conexion);
		$query_ObtenerNombre = sprintf ("SELECT z_users.avatar FROM z_users WHERE z_users.id = %s",$iduser,"int");
		$ObtenerNombre = mysql_query($query_ObtenerNombre, $conexion) or die(mysql_error());
		$row_ObtenerNombre = mysql_fetch_assoc($ObtenerNombre);
		$totalRows_ObtenerNombre = mysql_num_rows($ObtenerNombre);
		
		return $row_ObtenerNombre['avatar'];
		mysql_free_result($ObtenerNombre);
	}

	//Sacar email a partir de id usuario
	function sacararango ($iduser){

		global $database_conexion, $conexion;
		mysql_select_db($database_conexion, $conexion);
		$query_ObtenerNombre = sprintf ("SELECT z_users.rango FROM z_users WHERE z_users.id = %s",$iduser,"int");
		$ObtenerNombre = mysql_query($query_ObtenerNombre, $conexion) or die(mysql_error());
		$row_ObtenerNombre = mysql_fetch_assoc($ObtenerNombre);
		$totalRows_ObtenerNombre = mysql_num_rows($ObtenerNombre);
		
		return $row_ObtenerNombre['rango'];
		mysql_free_result($ObtenerNombre);
		
	}
	//Sacar email a partir de id usuario
	function saber_titulo ($idpost){

		global $database_conexion, $conexion;
		mysql_select_db($database_conexion, $conexion);
		$query_ObtenerNombre = sprintf ("SELECT z_posts.titulo FROM z_posts WHERE z_posts.id = %s",$idpost,"int");
		$ObtenerNombre = mysql_query($query_ObtenerNombre, $conexion) or die(mysql_error());
		$row_ObtenerNombre = mysql_fetch_assoc($ObtenerNombre);
		$totalRows_ObtenerNombre = mysql_num_rows($ObtenerNombre);
		
		return $row_ObtenerNombre['titulo'];
		mysql_free_result($ObtenerNombre);	

	}

	//SacarNumero de Visitas a los POSTS($id)
	function numerovisitas($idpost) {

		global $database_conexion, $conexion;
		mysql_select_db($database_conexion, $conexion);
		$query_ObtenerNombre = sprintf ("SELECT z_posts.visitas FROM z_posts WHERE z_posts.id = %s",$idpost,"int");
		$ObtenerNombre = mysql_query($query_ObtenerNombre, $conexion) or die(mysql_error());
		$row_ObtenerNombre = mysql_fetch_assoc($ObtenerNombre);
		$totalRows_ObtenerNombre = mysql_num_rows($ObtenerNombre);
		
		return $row_ObtenerNombre['visitas'];
		mysql_free_result($ObtenerNombre);	

	}	

	//Sacar autor mediante id del post
	function saber_autor_de_post ($idpost){
		
		global $database_conexion, $conexion;
		mysql_select_db($database_conexion, $conexion);
		$query_ObtenerNombre = sprintf ("SELECT z_posts.autor FROM z_posts WHERE z_posts.id = %s",$idpost,"int");
		$ObtenerNombre = mysql_query($query_ObtenerNombre, $conexion) or die(mysql_error());
		$row_ObtenerNombre = mysql_fetch_assoc($ObtenerNombre);
		$totalRows_ObtenerNombre = mysql_num_rows($ObtenerNombre);
		
		return $row_ObtenerNombre['autor'];
		mysql_free_result($ObtenerNombre);

	}	
	//Sacar nombre a partir de id usuario
	function recordar_sesion ($password,$username,$iduser){

		global $database_conexion, $conexion;
		setcookie("pass", $password, time() + (30 * 24 * 60 * 60),"/");  /* expira en 30 dias */
		setcookie("name", $username, time() + (30 * 24 * 60 * 60),"/");  /* expira en 30 dias */
		setcookie("identificador", $iduser, time() + (30 * 24 * 60 * 60),"/");  /* expira en 30 dias */	

	}
	//Autologin
	if (isset ($_COOKIE["pass"]) && isset ($_COOKIE["name"]) && isset ($_COOKIE["identificador"]) && !isset($_SESSION['MM_Id'])){
		mysql_select_db($database_conexion, $conexion);

		$LoginRS__query=sprintf("SELECT * FROM z_users WHERE password=%s AND nombre=%s OR password=%s AND email=%s",
		GetSQLValueString($_COOKIE["pass"], "text"),
		GetSQLValueString($_COOKIE["name"], "text"),
		GetSQLValueString($_COOKIE["pass"], "text"),
		GetSQLValueString($_COOKIE["name"], "text"));
		 
		$LoginRS = mysql_query($LoginRS__query, $conexion) or die(mysql_error());
		$row_ObtenerDeUser = mysql_fetch_assoc($LoginRS);
		$loginFoundUser = mysql_num_rows($LoginRS);
		
		if ($loginFoundUser) {
			$loginStrGroup = "";
			
			if (PHP_VERSION >= 5.1) {
				session_regenerate_id(true);
			} else {
				session_regenerate_id();
			}

			$_SESSION['MM_Username'] = $_COOKIE["name"];
			$_SESSION['MM_UserGroup'] = $loginStrGroup;
			$_SESSION['MM_Id'] = $_COOKIE["identificador"];
		}
	}

	//Rangos usuarios
	function rango ($rango){

		if ($rango=1) return "Principiante";
		if ($rango=2) return "Avanzado";
		if ($rango=3) return "Moderador";
		if ($rango=4) return "Administrador";
		
	}
	//Add_enviada
	function add_enviada($uno, $dos){

		global $database_conexion, $conexion;
		mysql_select_db($database_conexion, $conexion);
		$query_DatosFuncion = sprintf("SELECT * FROM z_friends WHERE sender = %s AND receiver = %s OR receiver = %s AND sender = %s", 
			GetSQLValueString($uno, "int"),
			GetSQLValueString($dos, "int"),
			GetSQLValueString($dos, "int"),
			GetSQLValueString($uno, "int"));
		$DatosFuncion = mysql_query($query_DatosFuncion, $conexion) or die(mysql_error());
		$row_DatosFuncion = mysql_fetch_assoc($DatosFuncion);
		$totalRows_DatosFuncion = mysql_num_rows($DatosFuncion);
	 
		if ($totalRows_DatosFuncion >0) return false;
		else
		return true;
		mysql_free_result($DatosFuncion);

	}
	// Logout the current user.
	$logoutAction = $_SERVER['PHP_SELF']."?doLogout=true";
		
	if ((isset($_SERVER['QUERY_STRING'])) && ($_SERVER['QUERY_STRING'] != "")){
		$logoutAction .="&". htmlentities($_SERVER['QUERY_STRING']);
	}

	if ((isset($_GET['doLogout'])) &&($_GET['doLogout']=="true")){
		//to fully log out a visitor we need to clear the session varialbles
		$_SESSION['MM_Username'] = NULL;
		$_SESSION['MM_UserGroup'] = NULL;
		$_SESSION['MM_Id'] = NULL;
		$_SESSION['PrevUrl'] = NULL;
		unset($_SESSION['MM_Username']);
		unset($_SESSION['MM_UserGroup']);
		unset($_SESSION['MM_Id']);
		unset($_SESSION['PrevUrl']);
		setcookie("pass", '', time() + (7 * 24 * 60 * 60),"/");  /* expira en una semana */
		setcookie("name", '', time() + (7 * 24 * 60 * 60),"/");  /* expira en una semana */
		 setcookie("identificador", '', time() + (7 * 24 * 60 * 60),"/");  /* expira en una semana */
		
		$logoutGoTo = $urlWeb;
		if ($logoutGoTo) {
			header("Location: $logoutGoTo");
			exit;
		}
	}


	if ($_COOKIE['idioma']=='' || !isset($_COOKIE['idioma'])) setcookie('idioma','english',time()*2,'/');
	//Traducciones Idiomas.
	function traducir ($tipo,$idioma){
		if ($tipo == 1){
			if (utf8_decode($idioma=='')) return "Account"; 
			if (utf8_decode($idioma=='english')) return "Account";
			if (utf8_decode($idioma=='español')) return "Cuenta";
			if (utf8_decode($idioma=='français')) return "Compte";
			if (utf8_decode($idioma=='deutsch')) return "Konto";  
			if (utf8_decode($idioma=='Русский')) return "Профиль";  
		}
		else if ($tipo == 2){
			if (utf8_decode($idioma=='')) return "Backgrounds";
			if (utf8_decode($idioma=='english')) return "Backgrounds";
			if (utf8_decode($idioma=='español')) return "Fondos";
			if (utf8_decode($idioma=='français')) return "Fond";
			if (utf8_decode($idioma=='deutsch')) return "Hintergründe";
			if (utf8_decode($idioma=='Русский')) return "Фоновое изображение";  
		}
		else if ($tipo == 3){
			if (utf8_decode($idioma=='')) return "Language";
			if (utf8_decode($idioma=='english')) return "Language";
			if (utf8_decode($idioma=='español')) return "Idiomas";
			if (utf8_decode($idioma=='français')) return "Langues";
			if (utf8_decode($idioma=='deutsch')) return "Sprachen"; 
			if (utf8_decode($idioma=='Русский')) return "Выбор языка";  
		}
		else if ($tipo == 4){
			if (utf8_decode($idioma=='')) return "Password";
			if (utf8_decode($idioma=='english')) return "Password";
			if (utf8_decode($idioma=='español')) return "Contraseña";
			if (utf8_decode($idioma=='français')) return "Mot de passe";
			if (utf8_decode($idioma=='deutsch')) return "Passwort"; 
			if (utf8_decode($idioma=='Русский')) return "Изменение пароля"; 
		}
		else if ($tipo == 5){
			if (utf8_decode($idioma=='')) return "Settings";
			if (utf8_decode($idioma=='english')) return "Settings";
			if (utf8_decode($idioma=='español')) return "Ajustes";
			if (utf8_decode($idioma=='français')) return "Paramètres";
			if (utf8_decode($idioma=='deutsch')) return "Einstellungen";  
			if (utf8_decode($idioma=='Русский')) return "Настройки";  
		}
		else if ($tipo == 6){
			if (utf8_decode($idioma=='')) return "Current password";
			if (utf8_decode($idioma=='english')) return "Current password";
			if (utf8_decode($idioma=='español')) return "Contaseña actual";
			if (utf8_decode($idioma=='français')) return "Mot de passe actuel";
			if (utf8_decode($idioma=='deutsch')) return "Aktuelles passwort"; 
			if (utf8_decode($idioma=='Русский')) return "Текущий пароль"; 
		}
		else if ($tipo == 7){
			if (utf8_decode($idioma=='')) return "New password";
			if (utf8_decode($idioma=='english')) return "New password";
			if (utf8_decode($idioma=='español')) return "Nueva contaseña";
			if (utf8_decode($idioma=='français')) return "Nouveau mot de passe";
			if (utf8_decode($idioma=='deutsch')) return "Neues passwort"; 
			if (utf8_decode($idioma=='Русский')) return "Новый пароль"; 
		}
		else if ($tipo == 8){
			if (utf8_decode($idioma=='')) return "Repeat new password";
			if (utf8_decode($idioma=='english')) return "Repeat new password";
			if (utf8_decode($idioma=='español')) return "Repetir nueva contaseña";
			if (utf8_decode($idioma=='français')) return "Répéter nouveau mot de passe";
			if (utf8_decode($idioma=='deutsch')) return "Neues passwort wiederholen"; 
			if (utf8_decode($idioma=='Русский')) return "Повторите новый пароль"; 
		}
		else if ($tipo == 9){
			if (utf8_decode($idioma=='')) return "Save changes";
			if (utf8_decode($idioma=='english')) return "Save changes";
			if (utf8_decode($idioma=='español')) return "Guardar cambios";
			if (utf8_decode($idioma=='français')) return "Enregistrer les modifications";
			if (utf8_decode($idioma=='deutsch')) return "Änderungen zu speichern";  
			if (utf8_decode($idioma=='Русский')) return "Сохранить изменения";  
		}
		else if ($tipo == 10){
			if (utf8_decode($idioma=='')) return "Change";
			if (utf8_decode($idioma=='english')) return "Change";
			if (utf8_decode($idioma=='español')) return "Cambiar";
			if (utf8_decode($idioma=='français')) return "Changement";
			if (utf8_decode($idioma=='deutsch')) return "Veränderung";  
			if (utf8_decode($idioma=='Русский')) return "Изменить"; 
		}
		else if ($tipo == 11){
			if (utf8_decode($idioma=='')) return "Background";
			if (utf8_decode($idioma=='english')) return "Background";
			if (utf8_decode($idioma=='español')) return "Imagen";
			if (utf8_decode($idioma=='français')) return "Image";
			if (utf8_decode($idioma=='deutsch')) return "Hintergrund";  
			if (utf8_decode($idioma=='Русский')) return "Фоновое";  
		}
		else if ($tipo == 12){
			if (utf8_decode($idioma=='')) return "Image";
			if (utf8_decode($idioma=='english')) return "Image";
			if (utf8_decode($idioma=='español')) return "de fondo";
			if (utf8_decode($idioma=='français')) return "de fond";
			if (utf8_decode($idioma=='deutsch')) return "bild"; 
			if (utf8_decode($idioma=='Русский')) return "изображение";  
		}
		else if ($tipo == 13){
			if (utf8_decode($idioma=='')) return "Click to change";
			if (utf8_decode($idioma=='english')) return "Click to change";
			if (utf8_decode($idioma=='español')) return "Haga clic para cambiar";
			if (utf8_decode($idioma=='français')) return "Cliquez pour changer";
			if (utf8_decode($idioma=='deutsch')) return "Klicken Sie auf Änderungen"; 
			if (utf8_decode($idioma=='Русский')) return "Нажмите, чтобы изменить";  
		}
		else if ($tipo == 14){
			if (utf8_decode($idioma=='')) return "Save";
			if (utf8_decode($idioma=='english')) return "Save";
			if (utf8_decode($idioma=='español')) return "Guardar";
			if (utf8_decode($idioma=='français')) return "Enregistrer";
			if (utf8_decode($idioma=='deutsch')) return "Speichern";  
			if (utf8_decode($idioma=='Русский')) return "Сохранить";  
		}
		else if ($tipo == 15){
			if (utf8_decode($idioma=='')) return "Close";
			if (utf8_decode($idioma=='english')) return "Close";
			if (utf8_decode($idioma=='español')) return "Cerrar";
			if (utf8_decode($idioma=='français')) return "Fermer";
			if (utf8_decode($idioma=='deutsch')) return "Schließen";  
			if (utf8_decode($idioma=='Русский')) return "Закрыть";  
		}
		else if ($tipo == 16){
			if (utf8_decode($idioma=='')) return "Main image";
			if (utf8_decode($idioma=='english')) return "Main image";
			if (utf8_decode($idioma=='español')) return "Imagen principal";
			if (utf8_decode($idioma=='français')) return "Image principale";
			if (utf8_decode($idioma=='deutsch')) return "Haupt bild"; 
			if (utf8_decode($idioma=='Русский')) return "Основное изображение"; 
		}
		else if ($tipo == 17){
			if (utf8_decode($idioma=='')) return "Forgot password?";
			if (utf8_decode($idioma=='english')) return "Forgot password?";
			if (utf8_decode($idioma=='español')) return "¿Has olvidado tu contraseña?";
			if (utf8_decode($idioma=='français')) return "Mot de passe oublié?";
			if (utf8_decode($idioma=='deutsch')) return "Passwort vergessen?";  
			if (utf8_decode($idioma=='Русский')) return "Забыли пароль?"; 
		}
		else if ($tipo == 18){
			if (utf8_decode($idioma=='')) return "This photo is your identity";
			if (utf8_decode($idioma=='english')) return "This photo is your identity";
			if (utf8_decode($idioma=='español')) return "Esta foto es su identidad";
			if (utf8_decode($idioma=='français')) return "Cette photo est votre identité";
			if (utf8_decode($idioma=='deutsch')) return "Dieses foto ist ihre identität"; 
			if (utf8_decode($idioma=='Русский')) return "Эта фотография ваша личность"; 
		}
		else if ($tipo == 19){
			if (utf8_decode($idioma=='')) return "The current password does not match.";
			if (utf8_decode($idioma=='english')) return "The current password does not match.";
			if (utf8_decode($idioma=='español')) return "La contraseña actual no coincide.";
			if (utf8_decode($idioma=='français')) return "Le mot de passe actuel ne correspond pas.";
			if (utf8_decode($idioma=='deutsch')) return "Das aktuelle Passwort stimmt nicht überein.";  
			if (utf8_decode($idioma=='Русский')) return "Текущий пароль не совпадает."; 
		}
		else if ($tipo == 20){
			if (utf8_decode($idioma=='')) return "Your new password must be confirmed properly.<br>Or the fields are empty.";
			if (utf8_decode($idioma=='english')) return "Your new password must be confirmed properly.<br>Or the fields are empty.";
			if (utf8_decode($idioma=='español')) return "Tu nueva contraseña debe ser confirmada correctamente.<br>O los campos están vacíos.";
			if (utf8_decode($idioma=='français')) return "Votre nouveau mot de passe doit être confirmé correctement.<br>Ou les champs sont vides.";
			if (utf8_decode($idioma=='deutsch')) return "Ihr neues Passwort muss richtig bestätigt werden.<br>Oder die Felder leer sind.";  
			if (utf8_decode($idioma=='Русский')) return "Новый пароль должен быть подтвержден надлежащим образом.<br>Или поля пусты.";  
		}
		else if ($tipo == 21){
			if (utf8_decode($idioma=='')) return "The new password was saved successfully.";
			if (utf8_decode($idioma=='english')) return "The new password was saved successfully.";
			if (utf8_decode($idioma=='español')) return "La nueva contraseña se ha guardado correctamente.";
			if (utf8_decode($idioma=='français')) return "Le nouveau mot de passe a bien été enregistré.";
			if (utf8_decode($idioma=='deutsch')) return "Das neue Passwort wurde erfolgreich gespeichert."; 
			if (utf8_decode($idioma=='Русский')) return "Новый пароль был успешно сохранен."; 
		}
		else if ($tipo == 22){
			if (utf8_decode($idioma=='')) return "System error.<br>Reload.";
			if (utf8_decode($idioma=='english')) return "System error.<br>Reload.";
			if (utf8_decode($idioma=='español')) return "Error del sistema.<br>Recargar.";
			if (utf8_decode($idioma=='français')) return "Défaillance du système.<br>Recharger.";
			if (utf8_decode($idioma=='deutsch')) return "Systemausfall.<br>Nachladen."; 
			if (utf8_decode($idioma=='Русский')) return "Сбой системы.<br>Перезагрузить.";  
		}
		else if ($tipo == 23){
			if (utf8_decode($idioma=='')) return "The data has been saved successfully.";
			if (utf8_decode($idioma=='english')) return "The data has been saved successfully.";
			if (utf8_decode($idioma=='español')) return "Los datos se han guardado correctamente.";
			if (utf8_decode($idioma=='français')) return "Les données ont été correctement enregistré.";
			if (utf8_decode($idioma=='deutsch')) return "Die Daten wurden erfolgreich gespeichert.";  
			if (utf8_decode($idioma=='Русский')) return "Данные были успешно сохранены."; 
		}
		else if ($tipo == 24){
			if (utf8_decode($idioma=='')) return "The fields First name or Last name are empty, fill in the empty fields.";
			if (utf8_decode($idioma=='english')) return "The fields First name or Last name are empty, fill in the empty fields.";
			if (utf8_decode($idioma=='español')) return "Los campos Nombre o Apellido están vacíos, rellena los campos vacíos.";
			if (utf8_decode($idioma=='français')) return "Les champs de Nom ou Nom de Famille sont vides, de remplir les champs vides.";
			if (utf8_decode($idioma=='deutsch')) return "Die Namen oder Nachnamen Felder leer sind, füllen Sie die leeren Felder."; 
			if (utf8_decode($idioma=='Русский')) return "В Имени или Фамилии поля пусты, заполнить пустые поля."; 
		}
		else if ($tipo == 25){
			if (utf8_decode($idioma=='')) return "Full name";
			if (utf8_decode($idioma=='english')) return "Full name";
			if (utf8_decode($idioma=='español')) return "Nombre completo";
			if (utf8_decode($idioma=='français')) return "Nom complet";
			if (utf8_decode($idioma=='deutsch')) return "Vollständiger name"; 
			if (utf8_decode($idioma=='Русский')) return "Полное имя";  
		}
		else if ($tipo == 26){
			if (utf8_decode($idioma=='')) return "Last name";
			if (utf8_decode($idioma=='english')) return "Last name";
			if (utf8_decode($idioma=='español')) return "Apellido";
			if (utf8_decode($idioma=='français')) return "Nom de famille";
			if (utf8_decode($idioma=='deutsch')) return "Nachname"; 
			if (utf8_decode($idioma=='Русский')) return "Фамилия";  
		}
		else if ($tipo == 27){
			if (utf8_decode($idioma=='')) return "My car";
			if (utf8_decode($idioma=='english')) return "My car";
			if (utf8_decode($idioma=='español')) return "Mi coche";
			if (utf8_decode($idioma=='français')) return "Ma voiture";
			if (utf8_decode($idioma=='deutsch')) return "Mein auto";  
			if (utf8_decode($idioma=='Русский')) return "Моя машина"; 
		}
		else if ($tipo == 28){
			if (utf8_decode($idioma=='')) return "Profession";
			if (utf8_decode($idioma=='english')) return "Profession";
			if (utf8_decode($idioma=='español')) return "Profesión";
			if (utf8_decode($idioma=='français')) return "Profession";
			if (utf8_decode($idioma=='deutsch')) return "Beruf";  
			if (utf8_decode($idioma=='Русский')) return "Специальность";  
		}
		else if ($tipo == 29){
			if (utf8_decode($idioma=='')) return "Country / City";
			if (utf8_decode($idioma=='english')) return "Country / City";
			if (utf8_decode($idioma=='español')) return "País / Ciudad";
			if (utf8_decode($idioma=='français')) return "Pays / Ville";
			if (utf8_decode($idioma=='deutsch')) return "Land / Stadt"; 
			if (utf8_decode($idioma=='Русский')) return "Страна / Город"; 
		}
		else if ($tipo == 30){
			if (utf8_decode($idioma=='')) return "Birthday";
			if (utf8_decode($idioma=='english')) return "Birthday";
			if (utf8_decode($idioma=='español')) return "Cumpleaños";
			if (utf8_decode($idioma=='français')) return "Anniversaire";
			if (utf8_decode($idioma=='deutsch')) return "Geburtstag"; 
			if (utf8_decode($idioma=='Русский')) return "День рождения";  
		}
		else if ($tipo == 31){
			if (utf8_decode($idioma=='')) return "Relationship status";
			if (utf8_decode($idioma=='english')) return "Relationship status";
			if (utf8_decode($idioma=='español')) return "Estado civil";
			if (utf8_decode($idioma=='français')) return "Statut de relation";
			if (utf8_decode($idioma=='deutsch')) return "Beziehungsstatus"; 
			if (utf8_decode($idioma=='Русский')) return "Семейное положение"; 
		}
		else if ($tipo == 32){
			if (utf8_decode($idioma=='')) return "About me";
			if (utf8_decode($idioma=='english')) return "About me";
			if (utf8_decode($idioma=='español')) return "Acerca de mí";
			if (utf8_decode($idioma=='français')) return "Sur moi";
			if (utf8_decode($idioma=='deutsch')) return "Über mich";  
			if (utf8_decode($idioma=='Русский')) return "Oбо мне";  
		}
		else if ($tipo == 33){
			if (utf8_decode($idioma=='')) return "Forgot your password?";
			if (utf8_decode($idioma=='english')) return "Forgot your password?";
			if (utf8_decode($idioma=='español')) return "¿Olvidaste tu contraseña?";
			if (utf8_decode($idioma=='français')) return "Mot de passe oublié?";
			if (utf8_decode($idioma=='deutsch')) return "Passwort vergessen?";  
			if (utf8_decode($idioma=='Русский')) return "Забыли ваш пароль?"; 
		}
		else if ($tipo == 34){
			if (utf8_decode($idioma=='')) return "Enter your email address";
			if (utf8_decode($idioma=='english')) return "Enter your email address";
			if (utf8_decode($idioma=='español')) return "Introduzca su e-mail";
			if (utf8_decode($idioma=='français')) return "Entrez votre adresse e-mail";
			if (utf8_decode($idioma=='deutsch')) return "Geben sie ihre e-mail-adresse";  
			if (utf8_decode($idioma=='Русский')) return "Введите адрес электронной почты";  
		}
		else if ($tipo == 35){
			if (utf8_decode($idioma=='')) return "Send";
			if (utf8_decode($idioma=='english')) return "Send";
			if (utf8_decode($idioma=='español')) return "Enviar";
			if (utf8_decode($idioma=='français')) return "Envoyer";
			if (utf8_decode($idioma=='deutsch')) return "Senden"; 
			if (utf8_decode($idioma=='Русский')) return "Oтправить";  
		}
		else if ($tipo == 36){
			if (utf8_decode($idioma=='')) return "This account does not exist.";
			if (utf8_decode($idioma=='english')) return "This account does not exist.";
			if (utf8_decode($idioma=='español')) return "Esta cuenta no existe.";
			if (utf8_decode($idioma=='français')) return "Ce profil n'existe pas.";
			if (utf8_decode($idioma=='deutsch')) return "Dieses konto ist nicht vorhanden.";  
			if (utf8_decode($idioma=='Русский')) return "Этот профиль не существует.";  
		}
		else if ($tipo == 37){
			if (utf8_decode($idioma=='')) return "We have sent you an email password recovery.";
			if (utf8_decode($idioma=='english')) return "We have sent you an email password recovery.";
			if (utf8_decode($idioma=='español')) return "Te hemos enviado un email de recuperación de contraseña.";
			if (utf8_decode($idioma=='français')) return "Nous vous avons envoyé un e-mail de récupération de mot de passe.";
			if (utf8_decode($idioma=='deutsch')) return "Wir haben ihnen eine e-mail passwort-wiederherstellung gesendet."; 
			if (utf8_decode($idioma=='Русский')) return "Мы отправили вам пароль восстановления по электронной почте."; 
		}
		else if ($tipo == 38){
			if (utf8_decode($idioma=='')) return "Favorites";
			if (utf8_decode($idioma=='english')) return "Favorites";
			if (utf8_decode($idioma=='español')) return "Favoritos";
			if (utf8_decode($idioma=='français')) return "Favoris";
			if (utf8_decode($idioma=='deutsch')) return "Favoriten";  
			if (utf8_decode($idioma=='Русский')) return "Избранные";  
		}
		else if ($tipo == 39){
			if (utf8_decode($idioma=='')) return "You have no favorites";
			if (utf8_decode($idioma=='english')) return "You have no favorites";
			if (utf8_decode($idioma=='español')) return "No tienes favoritos";
			if (utf8_decode($idioma=='français')) return "Vous n'avez pas favoris";
			if (utf8_decode($idioma=='deutsch')) return "Sie haben keine Favoriten"; 
			if (utf8_decode($idioma=='Русский')) return "У вас нет фаворитов"; 
		}
		else if ($tipo == 40){
			if (utf8_decode($idioma=='')) return "Groups";
			if (utf8_decode($idioma=='english')) return "Groups";
			if (utf8_decode($idioma=='español')) return "Grupos";
			if (utf8_decode($idioma=='français')) return "Groupes";
			if (utf8_decode($idioma=='deutsch')) return "Gruppen";  
			if (utf8_decode($idioma=='Русский')) return "Группы"; 
		}
		else if ($tipo == 41){
			if (utf8_decode($idioma=='')) return "Messages";
			if (utf8_decode($idioma=='english')) return "Messages";
			if (utf8_decode($idioma=='español')) return "Mensajes";
			if (utf8_decode($idioma=='français')) return "Messages";
			if (utf8_decode($idioma=='deutsch')) return "Nachrichten";  
			if (utf8_decode($idioma=='Русский')) return "Сообщения";  
		}
		else if ($tipo == 42){
			if (utf8_decode($idioma=='')) return "Inbox";
			if (utf8_decode($idioma=='english')) return "Inbox";
			if (utf8_decode($idioma=='español')) return "Recibidos";
			if (utf8_decode($idioma=='français')) return "Boîte de réception";
			if (utf8_decode($idioma=='deutsch')) return "Posteingang";  
			if (utf8_decode($idioma=='Русский')) return "Входящие"; 
		}
		else if ($tipo == 43){
			if (utf8_decode($idioma=='')) return "Outbox";
			if (utf8_decode($idioma=='english')) return "Outbox";
			if (utf8_decode($idioma=='español')) return "Enviados";
			if (utf8_decode($idioma=='français')) return "Messages envóyes";
			if (utf8_decode($idioma=='deutsch')) return "Gesendet"; 
			if (utf8_decode($idioma=='Русский')) return "Oтправленные"; 
		}
		else if ($tipo == 44){
			if (utf8_decode($idioma=='')) return "You have not received any messages.";
			if (utf8_decode($idioma=='english')) return "You have not received any messages.";
			if (utf8_decode($idioma=='español')) return "No ha recibido ningún mensaje.";
			if (utf8_decode($idioma=='français')) return "Vous n'avez pas reçu de messages.";
			if (utf8_decode($idioma=='deutsch')) return "Sie haben noch keine Nachrichten empfangen.";  
			if (utf8_decode($idioma=='Русский')) return "Вы не получили никаких сообщений.";  
		}
		else if ($tipo == 45){
			if (utf8_decode($idioma=='')) return "You have not sent any messages.";
			if (utf8_decode($idioma=='english')) return "You have not sent any messages.";
			if (utf8_decode($idioma=='español')) return "Usted no ha enviado ningún mensaje.";
			if (utf8_decode($idioma=='français')) return "Vous n'avez pas envoyé de messages.";
			if (utf8_decode($idioma=='deutsch')) return "Sie haben noch keine Nachrichten gesendet."; 
			if (utf8_decode($idioma=='Русский')) return "Вы не посылали никаких сообщений.";  
		}
		else if ($tipo == 46){
			if (utf8_decode($idioma=='')) return "New message";
			if (utf8_decode($idioma=='english')) return "New message";
			if (utf8_decode($idioma=='español')) return "Nuevo mensaje";
			if (utf8_decode($idioma=='français')) return "Nouveau message";
			if (utf8_decode($idioma=='deutsch')) return "Neue nachricht"; 
			if (utf8_decode($idioma=='Русский')) return "Новое сообщение";  
		}
		else if ($tipo == 47){
			if (utf8_decode($idioma=='')) return "answer";
			if (utf8_decode($idioma=='english')) return "answer";
			if (utf8_decode($idioma=='español')) return "responder";
			if (utf8_decode($idioma=='français')) return "répondre";
			if (utf8_decode($idioma=='deutsch')) return "antworten";  
			if (utf8_decode($idioma=='Русский')) return "ответить"; 
		}
		else if ($tipo == 48){
			if (utf8_decode($idioma=='')) return "new";
			if (utf8_decode($idioma=='english')) return "new";
			if (utf8_decode($idioma=='español')) return "nuevo";
			if (utf8_decode($idioma=='français')) return "nouveau";
			if (utf8_decode($idioma=='deutsch')) return "neu";  
			if (utf8_decode($idioma=='Русский')) return "новое";  
		}
		else if ($tipo == 49){
			if (utf8_decode($idioma=='')) return "Sent successfully";
			if (utf8_decode($idioma=='english')) return "Sent successfully";
			if (utf8_decode($idioma=='español')) return "Enviado correctamente";
			if (utf8_decode($idioma=='français')) return "Envoyé avec succès";
			if (utf8_decode($idioma=='deutsch')) return "Erfolgreich gesendet"; 
			if (utf8_decode($idioma=='Русский')) return "Oтправлено успешно"; 
		}
		else if ($tipo == 50){
			if (utf8_decode($idioma=='')) return "Write the message here...";
			if (utf8_decode($idioma=='english')) return "Write the message here...";
			if (utf8_decode($idioma=='español')) return "Escribe el mensaje aquí...";
			if (utf8_decode($idioma=='français')) return "Rédigez votre message ici...";
			if (utf8_decode($idioma=='deutsch')) return "Schreiben Sie die Nachricht hier ein...";  
			if (utf8_decode($idioma=='Русский')) return "Написать сообщение здесь...";  
		}
		else if ($tipo == 51){
			if (utf8_decode($idioma=='')) return "Videos";
			if (utf8_decode($idioma=='english')) return "Videos";
			if (utf8_decode($idioma=='español')) return "Videos";
			if (utf8_decode($idioma=='français')) return "Vidéos";
			if (utf8_decode($idioma=='deutsch')) return "Videos"; 
			if (utf8_decode($idioma=='Русский')) return "Видеозаписи";  
		}
		else if ($tipo == 52){
			if (utf8_decode($idioma=='')) return "video";
			if (utf8_decode($idioma=='english')) return "video";
			if (utf8_decode($idioma=='español')) return "video";
			if (utf8_decode($idioma=='français')) return "vidéo";
			if (utf8_decode($idioma=='deutsch')) return "video";  
			if (utf8_decode($idioma=='Русский')) return "видео";  
		}
		else if ($tipo == 53){
			if (utf8_decode($idioma=='')) return "Music";
			if (utf8_decode($idioma=='english')) return "Music";
			if (utf8_decode($idioma=='español')) return "Música";
			if (utf8_decode($idioma=='français')) return "Musique";
			if (utf8_decode($idioma=='deutsch')) return "Musik";  
			if (utf8_decode($idioma=='Русский')) return "Музыка"; 
		}
		else if ($tipo == 54){
			if (utf8_decode($idioma=='')) return "song";
			if (utf8_decode($idioma=='english')) return "song";
			if (utf8_decode($idioma=='español')) return "canción";
			if (utf8_decode($idioma=='français')) return "chanson";
			if (utf8_decode($idioma=='deutsch')) return "song"; 
			if (utf8_decode($idioma=='Русский')) return "песню";  
		}
		else if ($tipo == 55){
			if (utf8_decode($idioma=='')) return "Jan";
			if (utf8_decode($idioma=='english')) return "Jan";
			if (utf8_decode($idioma=='español')) return "Ene";
			if (utf8_decode($idioma=='français')) return "Jan";
			if (utf8_decode($idioma=='deutsch')) return "Jan";  
			if (utf8_decode($idioma=='Русский')) return "Янв";  
		}
		else if ($tipo == 56){
			if (utf8_decode($idioma=='')) return "Feb";
			if (utf8_decode($idioma=='english')) return "Feb";
			if (utf8_decode($idioma=='español')) return "Feb";
			if (utf8_decode($idioma=='français')) return "Fév";
			if (utf8_decode($idioma=='deutsch')) return "Feb";  
			if (utf8_decode($idioma=='Русский')) return "Фев";  
		}
		else if ($tipo == 57){
			if (utf8_decode($idioma=='')) return "Mar";
			if (utf8_decode($idioma=='english')) return "Mar";
			if (utf8_decode($idioma=='español')) return "Mar";
			if (utf8_decode($idioma=='français')) return "Mar";
			if (utf8_decode($idioma=='deutsch')) return "Mär";  
			if (utf8_decode($idioma=='Русский')) return "Мар";  
		}
		else if ($tipo == 58){
			if (utf8_decode($idioma=='')) return "Apr";
			if (utf8_decode($idioma=='english')) return "Apr";
			if (utf8_decode($idioma=='español')) return "Abr";
			if (utf8_decode($idioma=='français')) return "Avr";
			if (utf8_decode($idioma=='deutsch')) return "Apr";  
			if (utf8_decode($idioma=='Русский')) return "Апр";  
		}
		else if ($tipo == 59){
			if (utf8_decode($idioma=='')) return "May";
			if (utf8_decode($idioma=='english')) return "May";
			if (utf8_decode($idioma=='español')) return "May";
			if (utf8_decode($idioma=='français')) return "Mai";
			if (utf8_decode($idioma=='deutsch')) return "Mai";  
			if (utf8_decode($idioma=='Русский')) return "Май";  
		}
		else if ($tipo == 60){
			if (utf8_decode($idioma=='')) return "Jun";
			if (utf8_decode($idioma=='english')) return "Jun";
			if (utf8_decode($idioma=='español')) return "Jun";
			if (utf8_decode($idioma=='français')) return "Jui";
			if (utf8_decode($idioma=='deutsch')) return "Jun";  
			if (utf8_decode($idioma=='Русский')) return "Июн";  
		}
		else if ($tipo == 61){
			if (utf8_decode($idioma=='')) return "Jul";
			if (utf8_decode($idioma=='english')) return "Jul";
			if (utf8_decode($idioma=='español')) return "Jul";
			if (utf8_decode($idioma=='français')) return "Juil";
			if (utf8_decode($idioma=='deutsch')) return "Jul";  
			if (utf8_decode($idioma=='Русский')) return "Июл";  
		}
		else if ($tipo == 62){
			if (utf8_decode($idioma=='')) return "Aug";
			if (utf8_decode($idioma=='english')) return "Aug";
			if (utf8_decode($idioma=='español')) return "Ago";
			if (utf8_decode($idioma=='français')) return "Aoû";
			if (utf8_decode($idioma=='deutsch')) return "Aug";  
			if (utf8_decode($idioma=='Русский')) return "Авг";  
		}
		else if ($tipo == 63){
			if (utf8_decode($idioma=='')) return "Sep";
			if (utf8_decode($idioma=='english')) return "Sep";
			if (utf8_decode($idioma=='español')) return "Sep";
			if (utf8_decode($idioma=='français')) return "Sep";
			if (utf8_decode($idioma=='deutsch')) return "Sep";  
			if (utf8_decode($idioma=='Русский')) return "Сен";  
		}
		else if ($tipo == 64){
			if (utf8_decode($idioma=='')) return "Oct";
			if (utf8_decode($idioma=='english')) return "Oct";
			if (utf8_decode($idioma=='español')) return "Oct";
			if (utf8_decode($idioma=='français')) return "Oct";
			if (utf8_decode($idioma=='deutsch')) return "Okt";  
			if (utf8_decode($idioma=='Русский')) return "Окт";  
		}
		else if ($tipo == 65){
			if (utf8_decode($idioma=='')) return "Nov";
			if (utf8_decode($idioma=='english')) return "Nov";
			if (utf8_decode($idioma=='español')) return "Nov";
			if (utf8_decode($idioma=='français')) return "Nov";
			if (utf8_decode($idioma=='deutsch')) return "Nov";  
			if (utf8_decode($idioma=='Русский')) return "Ноя";  
		}
		else if ($tipo == 66){
			if (utf8_decode($idioma=='')) return "Dec";
			if (utf8_decode($idioma=='english')) return "Dec";
			if (utf8_decode($idioma=='español')) return "Dic";
			if (utf8_decode($idioma=='français')) return "Déc";
			if (utf8_decode($idioma=='deutsch')) return "Dez";  
			if (utf8_decode($idioma=='Русский')) return "Дек";  
		}
		else if ($tipo == 67){
			if (utf8_decode($idioma=='')) return "Upload song";
			if (utf8_decode($idioma=='english')) return "Upload song";
			if (utf8_decode($idioma=='español')) return "Subir canción";
			if (utf8_decode($idioma=='français')) return "Ajouter chanson";
			if (utf8_decode($idioma=='deutsch')) return "Hochladen song"; 
			if (utf8_decode($idioma=='Русский')) return "Загрузить песню";  
		}
		else if ($tipo == 68){
			if (utf8_decode($idioma=='')) return "The file must not violate the copyrights and intellectual property.";
			if (utf8_decode($idioma=='english')) return "The file must not violate the copyrights and intellectual property.";
			if (utf8_decode($idioma=='español')) return "El archivo no debe violar los derechos de autor y propiedad Intelectual.";
			if (utf8_decode($idioma=='français')) return "Le fichier ne doit pas violer les droits d'auteur et la propriété intellectuelle.";
			if (utf8_decode($idioma=='deutsch')) return "Darf nicht die urheberrechte und geistigen eigentumsrechte verletzen."; 
			if (utf8_decode($idioma=='Русский')) return "Файл не должен нарушать интеллектуальнyю собственность.";  
		}
		else if ($tipo == 69){
			if (utf8_decode($idioma=='')) return "The file should not exceed 128 MB.";
			if (utf8_decode($idioma=='english')) return "The file should not exceed 128 MB.";
			if (utf8_decode($idioma=='español')) return "El archivo no debe exceder de 128 MB.";
			if (utf8_decode($idioma=='français')) return "Le fichier ne doit pas dépasser 128 MB.";
			if (utf8_decode($idioma=='deutsch')) return "Sollte 128 MB nicht überschreiten.";  
			if (utf8_decode($idioma=='Русский')) return "Файл не должен превышать 128 Мб."; 
		}
		else if ($tipo == 70){
			if (utf8_decode($idioma=='')) return "Upload";
			if (utf8_decode($idioma=='english')) return "Upload";
			if (utf8_decode($idioma=='español')) return "Subir";
			if (utf8_decode($idioma=='français')) return "Ajouter";
			if (utf8_decode($idioma=='deutsch')) return "Hochladen";  
			if (utf8_decode($idioma=='Русский')) return "Загрузить";  
		}
		else if ($tipo == 71){
			if (utf8_decode($idioma=='')) return "write a title";
			if (utf8_decode($idioma=='english')) return "write a title";
			if (utf8_decode($idioma=='español')) return "escribe un título";
			if (utf8_decode($idioma=='français')) return "écrire un titre";
			if (utf8_decode($idioma=='deutsch')) return "einen titel";  
			if (utf8_decode($idioma=='Русский')) return "написать название";  
		}
		else if ($tipo == 72){
			if (utf8_decode($idioma=='')) return "My News";
			if (utf8_decode($idioma=='english')) return "My News";
			if (utf8_decode($idioma=='español')) return "Mis Noticias";
			if (utf8_decode($idioma=='français')) return "Mon Nouvelles";
			if (utf8_decode($idioma=='deutsch')) return "Meine Nachrichten";  
			if (utf8_decode($idioma=='Русский')) return "Мои Новости";  
		}
		else if ($tipo == 73){
			if (utf8_decode($idioma=='')) return "My Friends";
			if (utf8_decode($idioma=='english')) return "My Friends";
			if (utf8_decode($idioma=='español')) return "Mis Amigos";
			if (utf8_decode($idioma=='français')) return "Mes Amis";
			if (utf8_decode($idioma=='deutsch')) return "Meine Freunde";  
			if (utf8_decode($idioma=='Русский')) return "Мои Друзья";  
		}
		else if ($tipo == 74){
			if (utf8_decode($idioma=='')) return "My Photos";
			if (utf8_decode($idioma=='english')) return "My Photos";
			if (utf8_decode($idioma=='español')) return "Mis Fotos";
			if (utf8_decode($idioma=='français')) return "Mes Photos";
			if (utf8_decode($idioma=='deutsch')) return "Meine Fotos";  
			if (utf8_decode($idioma=='Русский')) return "Мои Фотографии";  
		}
		else if ($tipo == 75){
			if (utf8_decode($idioma=='')) return "My Music";
			if (utf8_decode($idioma=='english')) return "My Music";
			if (utf8_decode($idioma=='español')) return "Mi Musica";
			if (utf8_decode($idioma=='français')) return "Ma Musique";
			if (utf8_decode($idioma=='deutsch')) return "Meine Musik";  
			if (utf8_decode($idioma=='Русский')) return "Моя Музыка";  
		}
		else if ($tipo == 76){
			if (utf8_decode($idioma=='')) return "My Videos";
			if (utf8_decode($idioma=='english')) return "My Videos";
			if (utf8_decode($idioma=='español')) return "Mis Videos";
			if (utf8_decode($idioma=='français')) return "Mes Vidéos";
			if (utf8_decode($idioma=='deutsch')) return "Meine Videos";  
			if (utf8_decode($idioma=='Русский')) return "Мои Видео";  
		}
		else if ($tipo == 77){
			if (utf8_decode($idioma=='')) return "My Messages";
			if (utf8_decode($idioma=='english')) return "My Messages";
			if (utf8_decode($idioma=='español')) return "Mis Mensajes";
			if (utf8_decode($idioma=='français')) return "Mes Messages";
			if (utf8_decode($idioma=='deutsch')) return "Meine Nachrichten";  
			if (utf8_decode($idioma=='Русский')) return "Мои Сообщения";  
		}
		else if ($tipo == 78){
			if (utf8_decode($idioma=='')) return "My Groups";
			if (utf8_decode($idioma=='english')) return "My Groups";
			if (utf8_decode($idioma=='español')) return "Mis Grupos";
			if (utf8_decode($idioma=='français')) return "Mes Groupes";
			if (utf8_decode($idioma=='deutsch')) return "Meine Gruppen";  
			if (utf8_decode($idioma=='Русский')) return "Мои Группы";  
		}	
		else if ($tipo == 79){
			if (utf8_decode($idioma=='')) return "My Favorites";
			if (utf8_decode($idioma=='english')) return "My Favorites";
			if (utf8_decode($idioma=='español')) return "Mis Favoritos";
			if (utf8_decode($idioma=='français')) return "Mes Favoris";
			if (utf8_decode($idioma=='deutsch')) return "Meine Favoriten";  
			if (utf8_decode($idioma=='Русский')) return "Мои Избранные";  
		}	
		else if ($tipo == 80){
			if (utf8_decode($idioma=='')) return "My Settings";
			if (utf8_decode($idioma=='english')) return "My Settings";
			if (utf8_decode($idioma=='español')) return "Mi Configuración";
			if (utf8_decode($idioma=='français')) return "Mes Réglages";
			if (utf8_decode($idioma=='deutsch')) return "Meine Einstellungen";  
			if (utf8_decode($idioma=='Русский')) return "Мои Настройки";  
		}	
		else if ($tipo == 81){
			if (utf8_decode($idioma=='')) return "Log Out";
			if (utf8_decode($idioma=='english')) return "Log Out";
			if (utf8_decode($idioma=='español')) return "Cerrar Sessión";
			if (utf8_decode($idioma=='français')) return "Se Déconnecter";
			if (utf8_decode($idioma=='deutsch')) return "Abmelden";  
			if (utf8_decode($idioma=='Русский')) return "Выйти";  
		}	
		else if ($tipo == 82){
			if (utf8_decode($idioma=='')) return "Photos";
			if (utf8_decode($idioma=='english')) return "Photos";
			if (utf8_decode($idioma=='español')) return "Fotos";
			if (utf8_decode($idioma=='français')) return "Photos";
			if (utf8_decode($idioma=='deutsch')) return "Fotos";  
			if (utf8_decode($idioma=='Русский')) return "Фото";  
		}
		else if ($tipo == 83){
			if (utf8_decode($idioma=='')) return "Songs";
			if (utf8_decode($idioma=='english')) return "Songs";
			if (utf8_decode($idioma=='español')) return "Canciones";
			if (utf8_decode($idioma=='français')) return "Chansons";
			if (utf8_decode($idioma=='deutsch')) return "Songs";  
			if (utf8_decode($idioma=='Русский')) return "Песни";  
		}
		else if ($tipo == 84){
			if (utf8_decode($idioma=='')) return "Videos";
			if (utf8_decode($idioma=='english')) return "Videos";
			if (utf8_decode($idioma=='español')) return "Videos";
			if (utf8_decode($idioma=='français')) return "Vidéos";
			if (utf8_decode($idioma=='deutsch')) return "Videos";  
			if (utf8_decode($idioma=='Русский')) return "Видео";  
		}
		else if ($tipo == 85){
			if (utf8_decode($idioma=='')) return "views";
			if (utf8_decode($idioma=='english')) return "views";
			if (utf8_decode($idioma=='español')) return "visitas";
			if (utf8_decode($idioma=='français')) return "aufrufe";
			if (utf8_decode($idioma=='deutsch')) return "vues";  
			if (utf8_decode($idioma=='Русский')) return "просмотры";  
		}
		else if ($tipo == 86){
			if (utf8_decode($idioma=='')) return "Message";
			if (utf8_decode($idioma=='english')) return "Message";
			if (utf8_decode($idioma=='español')) return "Mensaje";
			if (utf8_decode($idioma=='français')) return "Message";
			if (utf8_decode($idioma=='deutsch')) return "Nachricht";  
			if (utf8_decode($idioma=='Русский')) return "Сообщение";  
		}
		else if ($tipo == 87){
			if (utf8_decode($idioma=='')) return "Upload video";
			if (utf8_decode($idioma=='english')) return "Upload video";
			if (utf8_decode($idioma=='español')) return "Subir vídeo";
			if (utf8_decode($idioma=='français')) return "Ajouter vidéo";
			if (utf8_decode($idioma=='deutsch')) return "Hochladen video"; 
			if (utf8_decode($idioma=='Русский')) return "Загрузить видео";  
		}
		else if ($tipo == 88){
			if (utf8_decode($idioma=='')) return "write a description";
			if (utf8_decode($idioma=='english')) return "write a description";
			if (utf8_decode($idioma=='español')) return "escribe una descripción";
			if (utf8_decode($idioma=='français')) return "écrire une description";
			if (utf8_decode($idioma=='deutsch')) return "schreiben sie eine beschreibung";  
			if (utf8_decode($idioma=='Русский')) return "написать описание";  
		}
		else if ($tipo == 89){
			if (utf8_decode($idioma=='')) return "Friends";
			if (utf8_decode($idioma=='english')) return "Friends";
			if (utf8_decode($idioma=='español')) return "Amigos";
			if (utf8_decode($idioma=='français')) return "Amis";
			if (utf8_decode($idioma=='deutsch')) return "Freunde";  
			if (utf8_decode($idioma=='Русский')) return "Друзья";  
		}
		else if ($tipo == 90){
			if (utf8_decode($idioma=='')) return "News";
			if (utf8_decode($idioma=='english')) return "News";
			if (utf8_decode($idioma=='español')) return "Noticias";
			if (utf8_decode($idioma=='français')) return "Nouvelles";
			if (utf8_decode($idioma=='deutsch')) return "Nachrichten";  
			if (utf8_decode($idioma=='Русский')) return "Новости";  
		}
		else if ($tipo == 91){
			if (utf8_decode($idioma=='')) return "Photo";
			if (utf8_decode($idioma=='english')) return "Photo";
			if (utf8_decode($idioma=='español')) return "Foto";
			if (utf8_decode($idioma=='français')) return "Photo";
			if (utf8_decode($idioma=='deutsch')) return "Foto";  
			if (utf8_decode($idioma=='Русский')) return "Фото";  
		}
		else if ($tipo == 92){
			if (utf8_decode($idioma=='')) return "Upload photo";
			if (utf8_decode($idioma=='english')) return "Upload photo";
			if (utf8_decode($idioma=='español')) return "Subir foto";
			if (utf8_decode($idioma=='français')) return "Ajouter vidéo";
			if (utf8_decode($idioma=='deutsch')) return "Hochladen foto"; 
			if (utf8_decode($idioma=='Русский')) return "Загрузить фото";  
		}
		else if ($tipo == 93){
			if (utf8_decode($idioma=='')) return "Select a photo";
			if (utf8_decode($idioma=='english')) return "Select a photo";
			if (utf8_decode($idioma=='español')) return "Seleccionar una foto";
			if (utf8_decode($idioma=='français')) return "Sélectionnez une photo";
			if (utf8_decode($idioma=='deutsch')) return "Wählen Sie ein Foto";  
			if (utf8_decode($idioma=='Русский')) return "Выберите фотографию";  
		}
		else if ($tipo == 94){
			if (utf8_decode($idioma=='')) return "Accept";
			if (utf8_decode($idioma=='english')) return "Accept";
			if (utf8_decode($idioma=='español')) return "Aceptar";
			if (utf8_decode($idioma=='français')) return "Accepter";
			if (utf8_decode($idioma=='deutsch')) return "Akzeptieren";  
			if (utf8_decode($idioma=='Русский')) return "Принять";  
		}
		else if ($tipo == 95){
			if (utf8_decode($idioma=='')) return "Decline";
			if (utf8_decode($idioma=='english')) return "Decline";
			if (utf8_decode($idioma=='español')) return "Declinar";
			if (utf8_decode($idioma=='français')) return "Refuser";
			if (utf8_decode($idioma=='deutsch')) return "Ablehnen";  
			if (utf8_decode($idioma=='Русский')) return "Oтказаться";  
		}
		else if ($tipo == 96){
			if (utf8_decode($idioma=='')) return "Find friends";
			if (utf8_decode($idioma=='english')) return "Find friends";
			if (utf8_decode($idioma=='español')) return "Buscar amigos";
			if (utf8_decode($idioma=='français')) return "Trouver des amis";
			if (utf8_decode($idioma=='deutsch')) return "Freunde finden";  
			if (utf8_decode($idioma=='Русский')) return "Найти друзей";  
		}
		else if ($tipo == 97){
			if (utf8_decode($idioma=='')) return "Are you sure you want to remove";
			if (utf8_decode($idioma=='english')) return "Are you sure you want to remove";
			if (utf8_decode($idioma=='español')) return "¿Estas seguro de que quieres eliminar a";
			if (utf8_decode($idioma=='français')) return "Êtes-vous sûr de vouloir supprimer";
			if (utf8_decode($idioma=='deutsch')) return "Sind sie sicher, sie wollen";  
			if (utf8_decode($idioma=='Русский')) return "Вы уверены, что хотите удалить";  
		}
		else if ($tipo == 98){
			if (utf8_decode($idioma=='')) return "from your friends ?";
			if (utf8_decode($idioma=='english')) return "from your friends ?";
			if (utf8_decode($idioma=='español')) return "de tus amigos?";
			if (utf8_decode($idioma=='français')) return "de vos amis ?";
			if (utf8_decode($idioma=='deutsch')) return "von ihren freunden entfernen ?";  
			if (utf8_decode($idioma=='Русский')) return "из ваших друзей ?";  
		}
		else if ($tipo == 99){
			if (utf8_decode($idioma=='')) return "No";
			if (utf8_decode($idioma=='english')) return "No";
			if (utf8_decode($idioma=='español')) return "No";
			if (utf8_decode($idioma=='français')) return "No";
			if (utf8_decode($idioma=='deutsch')) return "Nein";  
			if (utf8_decode($idioma=='Русский')) return "Нет";  
		}
		else if ($tipo == 100){
			if (utf8_decode($idioma=='')) return "Yes";
			if (utf8_decode($idioma=='english')) return "Yes";
			if (utf8_decode($idioma=='español')) return "Si";
			if (utf8_decode($idioma=='français')) return "Oui";
			if (utf8_decode($idioma=='deutsch')) return "Ja";  
			if (utf8_decode($idioma=='Русский')) return "Да";  
		}
		else if ($tipo == 101){
			if (utf8_decode($idioma=='')) return "Changes saved";
			if (utf8_decode($idioma=='english')) return "Changes saved";
			if (utf8_decode($idioma=='español')) return "Cambios guardados";
			if (utf8_decode($idioma=='français')) return "Modifications enregistrées";
			if (utf8_decode($idioma=='deutsch')) return "Änderungen gespeichert";  
			if (utf8_decode($idioma=='Русский')) return "Изменения сохранены";  
		}
		else if ($tipo == 102){
			if (utf8_decode($idioma=='')) return "Select a song";
			if (utf8_decode($idioma=='english')) return "Select a song";
			if (utf8_decode($idioma=='español')) return "Seleccionar una canción";
			if (utf8_decode($idioma=='français')) return "Sélectionnez une chanson";
			if (utf8_decode($idioma=='deutsch')) return "Wählen Sie einen song";  
			if (utf8_decode($idioma=='Русский')) return "Выберите песню";  
		}
		else if ($tipo == 103){
			if (utf8_decode($idioma=='')) return "Add tags #";
			if (utf8_decode($idioma=='english')) return "Add tags #";
			if (utf8_decode($idioma=='español')) return "Agregar etiquetas #playa,#amigos";
			if (utf8_decode($idioma=='français')) return "Ajouter des balises #";
			if (utf8_decode($idioma=='deutsch')) return "Stichworte hinzufügen #";  
			if (utf8_decode($idioma=='Русский')) return "Добавить теги #";  
		}
		else if ($tipo == 104){
			if (utf8_decode($idioma=='')) return "Select a video";
			if (utf8_decode($idioma=='english')) return "Select a video";
			if (utf8_decode($idioma=='español')) return "Seleccionar un video";
			if (utf8_decode($idioma=='français')) return "Sélectionnez une vidéo";
			if (utf8_decode($idioma=='deutsch')) return "Wählen Sie ein video";  
			if (utf8_decode($idioma=='Русский')) return "Выберите видео";  
		}

}?>