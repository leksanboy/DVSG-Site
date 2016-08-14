<?php require_once('../../../Connections/conexion.php');

	$FILE_NAME             = 		$_SESSION['MM_Id'].'_'.time().'_'.rand(); // File name
    $FILE_TITLE            =        $_FILES["fileUpload"]["name"]; // File title
	$fileTmpLoc            = 		$_FILES["fileUpload"]["tmp_name"]; // File in the PHP tmp folder
    $locationPath          =        '/var/www/html/pages/user/videos/videos';

    if ($FILE_TITLE == '' || $FILE_TITLE == undefined) {
        $FILE_TITLE = 'Untitled';
    }else{
        $FILE_TITLE = $FILE_TITLE;
    }

    if (!$fileTmpLoc) { // if file not chosen
        echo "ERROR: Size";
        exit();
    }

    function formatTime($timeInSeconds) { //as hh:mm:ss
        $timeInSeconds = round($timeInSeconds);
        $timeInSeconds = abs($timeInSeconds);
        $hours = floor($timeInSeconds / 3600) . ':';
        $minutes = floor(($timeInSeconds / 60) % 60). ':';
        $seconds = substr('00' . $timeInSeconds % 60, -2);
        if ($hours == '0:')
            $hours = '';
        return $hours . $minutes . $seconds;
    }

    if(move_uploaded_file($fileTmpLoc, "videos/$FILE_NAME")){
        exec("ffmpeg -i $locationPath/$FILE_NAME $locationPath/$FILE_NAME.mp4");
        $duration = exec("ffprobe $locationPath/$FILE_NAME -show_format 2>&1 | sed -n 's/duration=//p'");
        $FILE_DURATION = formatTime($duration);
        $FILE_NAME_MP4 = $FILE_NAME.'.mp4';

		//Insert in videos
	    $insertSQL = sprintf("INSERT INTO z_videos (title, duration, name, user) VALUES (%s, %s, %s, %s)",
		GetSQLValueString($FILE_TITLE, "text"),
		GetSQLValueString($FILE_DURATION, "text"),
		GetSQLValueString($FILE_NAME_MP4, "text"),
		GetSQLValueString($_SESSION['MM_Id'], "int"));
		mysql_select_db($database_conexion, $conexion);
		$Result1 = mysql_query($insertSQL, $conexion) or die(mysql_error());

		//Get Inserted videos id
		mysql_select_db($database_conexion, $conexion);
		$query_userData = sprintf("SELECT id FROM z_videos WHERE z_videos.name = %s", 
		GetSQLValueString($FILE_NAME_MP4, "text"));
		$userData = mysql_query($query_userData, $conexion) or die(mysql_error());
		$row_userData = mysql_fetch_assoc($userData);
		$totalRows_userData = mysql_num_rows($userData);

		//Insert to user videos
		$insertSQL = sprintf("INSERT INTO z_videos_favorites (video, user) VALUES (%s, %s)",
		GetSQLValueString($row_userData['id'], "int"),
		GetSQLValueString($_SESSION['MM_Id'], "int"));
		mysql_select_db($database_conexion, $conexion);
		$Result1 = mysql_query($insertSQL, $conexion) or die(mysql_error());

        //Remove file from folder fisicaly (without .mp4)
        unlink("videos/$FILE_NAME");

	    echo "<svg viewBox='0 0 48 48' fill='#09f'><path d='M18 32.34L9.66 24l-2.83 2.83L18 38l24-24-2.83-2.83z'/></svg>";
	} else {
	    echo "<svg viewBox='0 0 48 48' fill='#f00'><path d='M38 12.83L35.17 10 24 21.17 12.83 10 10 12.83 21.17 24 10 35.17 12.83 38 24 26.83 35.17 38 38 35.17 26.83 24z'/></svg>";
	}

?>