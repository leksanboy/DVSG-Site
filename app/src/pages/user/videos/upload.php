<?php require_once('../../../Connections/conexion.php');
	$time               = time();
	$userId             = $_SESSION['MM_Id'];
	$FILE_NAME          = $userId.$time.rand(); // File name
	$FILE_NAME_MP4		= $FILE_NAME.'.mp4';
	$FILE_TITLE         = $_FILES["fileUpload"]["name"]; // File title
	$FILE_TYPE         	= $_FILES["fileUpload"]["type"]; // File type
	$fileTmpLoc         = $_FILES["fileUpload"]["tmp_name"]; // File in the PHP tmp folder
	$locationPath       = '/var/www/html/pages/user/videos/videos';

	if ($FILE_TITLE == '' || $FILE_TITLE == undefined) {
		$FILE_TITLE = 'Untitled';
	}else{
		$FILE_TITLE = $FILE_TITLE;
	}

	// if file not chosen or broken file
	if (!$fileTmpLoc) {
		echo "<svg viewBox='0 0 24 24' fill='#FFAB00'><path d='M0 0h24v24H0z' fill='none'/><path d='M1 21h22L12 2 1 21zm12-3h-2v-2h2v2zm0-4h-2v-4h2v4z'/></svg>";
		exit();
	}else{
		function timeFormat($timeInSeconds) { //as hh:mm:ss
			$timeInSeconds = round($timeInSeconds);
			$timeInSeconds = abs($timeInSeconds);
			$hours = floor($timeInSeconds / 3600) . ':';
			$minutes = floor(($timeInSeconds / 60) % 60). ':';
			$seconds = substr('00' . $timeInSeconds % 60, -2);

			if ($hours == '0:')
				$hours = '';
			return $hours . $minutes . $seconds;
		}

		// Videos 1ue no son .mp4 se gurdan con la extension mp4 y luego se transforman
		if ($FILE_TYPE != 'video/mp4') {
			$locationFolder = "videos/$FILE_NAME";
		}else{
			$locationFolder = "videos/$FILE_NAME_MP4";
		}

		// Upload video physically
		if(move_uploaded_file($fileTmpLoc, $locationFolder)){
			if ($FILE_TYPE != 'video/mp4') {
				//···> Transform video (to .mp4)
				exec("ffmpeg -i $locationPath/$FILE_NAME $locationPath/$FILE_NAME.mp4");
				
				//···> Get video duration
				$duration = exec("ffprobe $locationPath/$FILE_NAME -show_format 2>&1 | sed -n 's/duration=//p'");
				
				//···> Get video poster
				$frameTime = intval($duration/4);
				exec("ffmpeg -i $locationPath/$FILE_NAME -ss $frameTime -s 320x240 -vframes 1 $locationPath/thumbnails/$FILE_NAME.jpg");
			} else {
				//···> Get video duration
				$duration = exec("ffprobe $locationPath/$FILE_NAME_MP4 -show_format 2>&1 | sed -n 's/duration=//p'");
				
				//···> Get video poster
				$frameTime = intval($duration/4);
				exec("ffmpeg -i $locationPath/$FILE_NAME_MP4 -ss $frameTime -s 320x240 -vframes 1 $locationPath/thumbnails/$FILE_NAME.jpg");
			}
			
			//···> Transform duration to (00:00:00)
			$FILE_DURATION = timeFormat($duration);

			//Insert in videos
			$insertSQL = sprintf("INSERT INTO z_videos (title, duration, name, user) VALUES (%s, %s, %s, %s)",
			GetSQLValueString($FILE_TITLE, "text"),
			GetSQLValueString($FILE_DURATION, "text"),
			GetSQLValueString($FILE_NAME_MP4, "text"),
			GetSQLValueString($userId, "int"));
			mysql_select_db($database_conexion, $conexion);
			$Result1 = mysql_query($insertSQL, $conexion) or die(mysql_error());
			$insertId = mysql_insert_id();

			//Insert to user videos
			$insertSQL = sprintf("INSERT INTO z_videos_favorites (video, user, time) VALUES (%s, %s, %s)",
			GetSQLValueString($insertId, "int"),
			GetSQLValueString($userId, "int"),
			GetSQLValueString($time, "int"));
			mysql_select_db($database_conexion, $conexion);
			$Result1 = mysql_query($insertSQL, $conexion) or die(mysql_error());

			//Remove file from folder fisicaly (without .mp4)
			if ($FILE_TYPE != 'video/mp4') {
				unlink("videos/$FILE_NAME");
			}

			echo "<svg viewBox='0 0 48 48' fill='#09f'><path d='M18 32.34L9.66 24l-2.83 2.83L18 38l24-24-2.83-2.83z'/></svg>";
		} else {
			echo "<svg viewBox='0 0 48 48' fill='#f00'><path d='M38 12.83L35.17 10 24 21.17 12.83 10 10 12.83 21.17 24 10 35.17 12.83 38 24 26.83 35.17 38 38 35.17 26.83 24z'/></svg>";
		}
	}
?>
