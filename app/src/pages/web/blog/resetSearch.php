<?php require_once('../../Connections/conexion.php');
	$_SESSION['indiceBlog'] = '';
	$_SESSION['countSearchBlog'] = '';
	
	$_SESSION['blogFilter'] = null;
	unset($_SESSION['blogFilter']);

	$_SESSION['advancedSearchShortByItemBlog'] = null;
	unset($_SESSION['advancedSearchShortByItemBlog']);
	
	$_SESSION['textBlogSearch'] = null;
	unset($_SESSION['textBlogSearch']);

	$_SESSION['blogSearch'] = null;
	unset($_SESSION['blogSearch']);

	$sortByItem = 0;

	mysql_select_db($database_conexion, $conexion);
	$query_AllPosts = "SELECT * FROM z_posts WHERE tipo=3 ORDER BY id DESC LIMIT 2";
	$AllPosts = mysql_query($query_AllPosts, $conexion) or die(mysql_error());
	$row_AllPosts = mysql_fetch_assoc($AllPosts);
	$totalRows_AllPosts = mysql_num_rows($AllPosts);
?>
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