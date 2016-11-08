<?php require_once('../../../Connections/conexion.php');
	$userId = $_GET['userId'];
	$cuantity = $_GET['cuantity'];
	$_SESSION['loadMoreSongs'.$userId] = $_SESSION['loadMoreSongs'.$userId] + $cuantity;

	//Load more
	mysql_select_db($database_conexion, $conexion);
	$query_loadMoreSongs = sprintf("SELECT f.id, f.song, m.name, m.title, m.duration 
									FROM z_music_favorites f INNER JOIN z_music m ON m.id = f.song 
									WHERE f.user = $userId AND f.is_deleted = 0 ORDER BY f.date DESC LIMIT %s, 10",
	GetSQLValueString($_SESSION['loadMoreSongs'.$userId], "int"));
	$loadMoreSongs = mysql_query($query_loadMoreSongs, $conexion) or die(mysql_error());
	$totalRows_loadMoreSongs = mysql_num_rows($loadMoreSongs);
	
	if ($totalRows_loadMoreSongs != 0){
		$array = array();

		while($row = mysql_fetch_assoc($loadMoreSongs)){
			$_SESSION['counterSongs'.$userId] = $_SESSION['counterSongs'.$userId] + 1;

		    $a = new stdClass;
		    $a->id=$row['id'];
		    $a->song=$row['song'];
		    $a->counter=$_SESSION['counterSongs'.$userId];
		    $a->name=$row['title'];
		    $a->file=$row['name'];
		    $a->duration=$row['duration'];
		    array_push($array, $a);
		}

		echo json_encode($array);
	}
?>