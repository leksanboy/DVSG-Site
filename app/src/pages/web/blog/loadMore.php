<?php require_once('../../Connections/conexion.php');

	mysql_select_db($database_conexion, $conexion);
	$_SESSION['indiceBlog'] = $_SESSION['indiceBlog'] + $_POST['paginado'];
	$loadPosts = $_SESSION['indiceBlog'];

	if (isset($_SESSION['blogFilter'])) {
		if ($_SESSION['blogFilter']) {
			$blogFilter = $_SESSION['blogFilter'];
		}else{
			$blogFilter = "ORDER BY id DESC";
		}

		if ($_SESSION['blogSearch'] == '') {
			$consultaBlog = 'WHERE tipo=3';
		}else{
			$consultaBlog = $_SESSION['blogSearch'];
		}
	} else if (isset($_SESSION['blogSearch'])) {
		$consultaBlog = $_SESSION['blogSearch'];
		$blogFilter = 'ORDER BY id DESC';
	}else{
		$consultaBlog = 'WHERE tipo=3';
		$blogFilter = "ORDER BY id DESC";
	}
	
	$query_AllPosts = "SELECT * FROM z_posts $consultaBlog $blogFilter LIMIT $loadPosts,2";
		
	$AllPosts = mysql_query($query_AllPosts, $conexion) or die(mysql_error());
	$row_AllPosts = mysql_fetch_assoc($AllPosts);
	$totalRows_AllPosts = mysql_num_rows($AllPosts);

?>
<?php if ($totalRows_AllPosts!='') { ?>
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
<?php } ?>
<?php mysql_free_result($AllPosts); ?>