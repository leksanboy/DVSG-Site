<?php require_once '../../../Connections/conexion.php';
	$time               = time();
	$userId             = $_SESSION['MM_Id'];
	$content            = $_POST['content'];
	$photos             = $_POST['photos'];
	$audios             = $_POST['audios'];
	$videos             = $_POST['videos'];

	$insertSQL = sprintf("INSERT INTO z_news (user, content, photos, songs, videos, time) VALUES (%s, %s, %s, %s, %s, %s)",
	GetSQLValueString($userId, "int"),
	GetSQLValueString($content, "text"),
	GetSQLValueString($photos, "text"),
	GetSQLValueString($songs, "text"),
	GetSQLValueString($videos, "int"),
	GetSQLValueString($time, "int"));
	mysql_select_db($database_conexion, $conexion);
	$Result1 = mysql_query($insertSQL, $conexion) or die(mysql_error());
	$insertId = mysql_insert_id();

	//Audio files
	$audios = json_decode($audios);
	foreach ($audios as $key => $item){
		// print_r ($item);
		$insertSQL = sprintf("INSERT INTO z_news_files (user, post, type, file, name, title, duration, time) VALUES (%s, %s, %s, %s, %s, %s, %s, %s)",
		GetSQLValueString($userId, "int"),
		GetSQLValueString($insertId, "int"),
		GetSQLValueString("audio", "text"), //video, audio, photo
		GetSQLValueString($item->song, "int"),
		GetSQLValueString($item->name, "text"),
		GetSQLValueString($item->title, "text"),
		GetSQLValueString($item->duration, "text"),
		GetSQLValueString($time, "int"));
		mysql_select_db($database_conexion, $conexion);
		$ResultAudios = mysql_query($insertSQL, $conexion) or die(mysql_error());
	}

	//Photo files
	$photos = json_decode($photos);
	foreach ($photos as $key => $item){
		// print_r ($item);
		$insertSQL = sprintf("INSERT INTO z_news_files (user, post, type, file, name, time) VALUES (%s, %s, %s, %s, %s, %s)",
		GetSQLValueString($userId, "int"),
		GetSQLValueString($insertId, "int"),
		GetSQLValueString("photo", "text"), //video, audio, photo
		GetSQLValueString($item->photo, "int"),
		GetSQLValueString($item->name, "text"),
		GetSQLValueString($time, "int"));
		mysql_select_db($database_conexion, $conexion);
		$ResultPhotos = mysql_query($insertSQL, $conexion) or die(mysql_error());
	}

	//Video files
	$videos = json_decode($videos);
	foreach ($videos as $key => $item){
		// print_r ($item);
		$insertSQL = sprintf("INSERT INTO z_news_files (user, post, type, file, name, title, duration, time) VALUES (%s, %s, %s, %s, %s, %s, %s, %s)",
		GetSQLValueString($userId, "int"),
		GetSQLValueString($insertId, "int"),
		GetSQLValueString("video", "text"), //video, audio, photo
		GetSQLValueString($item->video, "int"),
		GetSQLValueString($item->name, "text"),
		GetSQLValueString($item->title, "text"),
		GetSQLValueString($item->duration, "text"),
		GetSQLValueString($time, "int"));
		mysql_select_db($database_conexion, $conexion);
		$ResultVideos = mysql_query($insertSQL, $conexion) or die(mysql_error());
	}

?>
