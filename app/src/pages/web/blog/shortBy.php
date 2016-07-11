<?php require_once('../../Connections/conexion.php');
	$_SESSION['indiceBlog'] = 0;

	if (isset($_SESSION['blogSearch'])) {
		$consultaBlog = $_SESSION['blogSearch'];
	}else{
		$consultaBlog = 'WHERE tipo=3';
	}

	//Datos
	mysql_select_db($database_conexion, $conexion);
	if ($_POST['valor']=='like') {
		$query_AllPosts = "SELECT * FROM z_posts $consultaBlog ORDER BY likes DESC LIMIT 2";
		$blogOrder = "ORDER BY likes DESC";
		$_SESSION['advancedSearchShortByItemBlog'] = 1;
	}else if ($_POST['valor']=='view') {
		$query_AllPosts = "SELECT * FROM z_posts $consultaBlog ORDER BY visitas DESC LIMIT 2";
		$blogOrder = "ORDER BY visitas DESC";
		$_SESSION['advancedSearchShortByItemBlog'] = 2;
	}else if ($_POST['valor']=='comment') {
		$query_AllPosts = "SELECT * FROM z_posts $consultaBlog ORDER BY comments DESC LIMIT 2";
		$blogOrder = "ORDER BY comments DESC";
		$_SESSION['advancedSearchShortByItemBlog'] = 3;
	}
	
	$AllPosts = mysql_query($query_AllPosts, $conexion) or die(mysql_error());
	$row_AllPosts = mysql_fetch_assoc($AllPosts);
	$totalRows_AllPosts = mysql_num_rows($AllPosts);

	$_SESSION['blogFilter'] = $blogOrder;
?>
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
<?php } while ($row_AllPosts = mysql_fetch_assoc($AllPosts)); ?>
<?php mysql_free_result($AllPosts); ?>