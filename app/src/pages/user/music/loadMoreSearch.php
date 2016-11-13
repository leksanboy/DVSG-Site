<?php require_once('../../../Connections/conexion.php');
	$userId = $_GET['userId'];
	$cuantity = $_GET['cuantity'];
	$searchValue = $_GET['searchValue'];
	$_SESSION['loadMoreSearchSongs'.$userId] = $_SESSION['loadMoreSearchSongs'.$userId] + $cuantity;

	//Load more
	mysql_select_db($database_conexion, $conexion);
	$query_loadMoreSearch = sprintf("SELECT id, title, name, duration
										FROM z_music 
										WHERE title LIKE %s ORDER BY title DESC LIMIT %s, 10", 
	GetSQLValueString("%" . $searchValue . "%", "text"),
	GetSQLValueString($_SESSION['loadMoreSearchSongs'.$userId], "int"));
	$loadMoreSearch = mysql_query($query_loadMoreSearch, $conexion) or die(mysql_error());
	$totalRows_loadMoreSearch = mysql_num_rows($loadMoreSearch);
	
	if ($totalRows_loadMoreSearch != 0){
		$array = array();

		while($row = mysql_fetch_assoc($loadMoreSearch)){
			$_SESSION['counterSongsSearch'.$userId] = $_SESSION['counterSongsSearch'.$userId] + 1;

			$a = new stdClass;
			$a->id=$row['id'];
			$a->counter=$_SESSION['counterSongsSearch'.$userId];
			$a->name=$row['title'];
			$a->file=$row['name'];
			$a->duration=$row['duration'];
			array_push($array, $a);
		}

		echo json_encode($array);
	}
?>