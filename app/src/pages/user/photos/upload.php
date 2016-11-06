<?php require_once('../../../Connections/conexion.php');
	$time               = time();
    $userId             = $_SESSION['MM_Id'];
	$FILE_NAME 			= $userId.$time.rand(); // File name
    $FILE_TITLE 		= $_FILES["fileUpload"]["name"]; // File title
	$fileTmpLoc 		= $_FILES["fileUpload"]["tmp_name"]; // File in the PHP tmp folder

	if ($FILE_TITLE == '' || $FILE_TITLE == undefined) {
		$FILE_TITLE = 'Untitled';
	}else{
		$FILE_TITLE = $FILE_TITLE;
	}

	if (!$fileTmpLoc) { // if file not chosen
	    echo "<svg viewBox='0 0 24 24' fill='#FFAB00'><path d='M0 0h24v24H0z' fill='none'/><path d='M1 21h22L12 2 1 21zm12-3h-2v-2h2v2zm0-4h-2v-4h2v4z'/></svg>";
	    exit();
	}

	if(move_uploaded_file($fileTmpLoc, "photos/$FILE_NAME")){
		//Insert in photos
	    $insertSQL = sprintf("INSERT INTO z_photos (name, user, time) VALUES (%s, %s, %s)",
		GetSQLValueString($FILE_NAME, "text"),
		GetSQLValueString($userId, "int"),
		GetSQLValueString($time, "text"));
		mysql_select_db($database_conexion, $conexion);
		$Result1 = mysql_query($insertSQL, $conexion) or die(mysql_error());
		$insertId = mysql_insert_id();

		//Insert to user photos
		$insertSQL = sprintf("INSERT INTO z_photos_favorites (photo, user, time) VALUES (%s, %s, %s)",
		GetSQLValueString($insertId, "int"),
		GetSQLValueString($userId, "int"),
        GetSQLValueString($time, "int"));
		mysql_select_db($database_conexion, $conexion);
		$Result1 = mysql_query($insertSQL, $conexion) or die(mysql_error());

	    echo "<svg viewBox='0 0 48 48' fill='#09f'><path d='M18 32.34L9.66 24l-2.83 2.83L18 38l24-24-2.83-2.83z'/></svg>";
	} else {
	    echo "<svg viewBox='0 0 48 48' fill='#f00'><path d='M38 12.83L35.17 10 24 21.17 12.83 10 10 12.83 21.17 24 10 35.17 12.83 38 24 26.83 35.17 38 38 35.17 26.83 24z'/></svg>";
	}
?>