<?php require_once('../../Connections/conexion.php'); 

	$_SESSION['indiceBlog']=0;
	$consultaBlog ='';

	//Vaciar la sesion de sort by(del filtro);
	if (isset($_SESSION['blogFilter'])) {
		$_SESSION['blogFilter'] = null;
		unset($_SESSION['blogFilter']);
	}
	if (isset($_SESSION['advancedSearchShortByItemBlog'])) {
		$_SESSION['advancedSearchShortByItemBlog'] = null;
		unset($_SESSION['advancedSearchShortByItemBlog']);
		$sortByItem = 0;
	}
	
	if(isset($_POST['searchBlog']) && $_POST['searchBlog'] !=''){
		$partes = explode(" ", $_POST['searchBlog']);
		$cantidad = count($partes);

		if ($cantidad>1){
			for ($i=0; $i < $cantidad; $i++) {
				$consultaBlog.="titulo LIKE ".GetSQLValueString("%" . $partes[$i] . "%", "text").' AND ';
			}
		} else {
			$consultaBlog.="titulo LIKE ".GetSQLValueString("%" . $partes[0] . "%", "text").' AND ';
		}
	}

	$consultaBlog = substr ($consultaBlog, 0, strlen($consultaBlog) - 5);

	if ($consultaBlog != '') {
		$consultaBlog = 'WHERE tipo=3 AND '.$consultaBlog;
	}else{
		$consultaBlog = 'WHERE tipo=3';
	}

	//Datos
	mysql_select_db($database_conexion, $conexion);
	$query_AllPosts = "SELECT * FROM z_posts $consultaBlog ORDER BY id DESC LIMIT 2";
	$AllPosts = mysql_query($query_AllPosts, $conexion) or die(mysql_error());
	$row_AllPosts = mysql_fetch_assoc($AllPosts);
	$totalRows_AllPosts = mysql_num_rows($AllPosts);

	//Count
	mysql_select_db($database_conexion, $conexion);
	$query_AllPostsCount = "SELECT * FROM z_posts $consultaBlog";
	$AllPostsCount = mysql_query($query_AllPostsCount, $conexion) or die(mysql_error());
	$row_AllPostsCount = mysql_fetch_assoc($AllPostsCount);
	$totalRows_AllPostsCount = mysql_num_rows($AllPostsCount);

	$_SESSION['countSearchBlog'] = $totalRows_AllPostsCount;
	$_SESSION['blogSearch'] = $consultaBlog;
	$_SESSION['textBlogSearch'] = $_POST['searchBlog'];
?>
<?php if ($totalRows_AllPosts !='') { ?>
	<?php if($_SESSION['blogSearch'] !='') {?>
		<div class="results">
			<?php echo $_SESSION['countSearchBlog'] ?> Results found
		</div>
	<?php } ?>
	<?php do { ?>
		<div class="postContainer" onclick="location.href='<?php echo $urlWeb ?><?php echo $row_AllPosts['urlamigable']; ?>.html'">
			<div class="title"><?php echo $row_AllPosts['titulo']; ?></div>
			<div class="information">
				<div class="date">
					<?php echo $row_AllPosts['fecha']; ?>
				</div>
				<div class="data">
					<?php include("../../images/svg/likes.php");?>
					<?php echo $row_AllPosts['likes']; ?>
					<?php include("../../images/svg/views.php");?>
					<?php echo $row_AllPosts['visitas']; ?>
					<?php include("../../images/svg/comments.php");?>
					<?php echo $row_AllPosts['comments']; ?>
				</div>
			</div>
		</div>
	<?php } while ($row_AllPosts = mysql_fetch_assoc($AllPosts));
} else{ ?>
	<div class="errorContainer">
		<div class="circleOne">
			<div class="text">404</div>
			<div class="subText">not results</div>
		</div>
	</div>
<?php }?>
<?php mysql_free_result($AllPosts); ?>
<?php mysql_free_result($AllPostsCount); ?>