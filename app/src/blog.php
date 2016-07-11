<?php require_once('Connections/conexion.php');

	$_SESSION['indiceBlog']=0;

	//All Posts
	mysql_select_db($database_conexion, $conexion);
	if (isset($_SESSION['blogFilter'])) {
		$blogOrder = $_SESSION['blogFilter'];
	}else{
		$blogOrder = 'ORDER BY id';
	}
	
	if (isset($_SESSION['blogSearch'])) {
		$consultaBlog = $_SESSION['blogSearch'];
	} else{
		$consultaBlog = 'WHERE tipo=3';
	}

	$query_AllPosts = "SELECT * FROM z_posts $consultaBlog $blogOrder LIMIT 2";
	$AllPosts = mysql_query($query_AllPosts, $conexion) or die(mysql_error());
	$row_AllPosts = mysql_fetch_assoc($AllPosts);
	$totalRows_AllPosts = mysql_num_rows($AllPosts);
?>
<!DOCTYPE html>
	<?php include_once("includes/fuckoff.php"); ?>
	<head>
		<meta charset="utf-8">
		<meta name="Author" content="Diesel vs. Gasoline" />
		<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7, IE=9" />
		<title>DVSG - Blog</title>
		<?php include_once("includes/favicons.php"); ?>
	 	<link rel="stylesheet" type="text/css" href="<?php echo $urlWeb ?>styles/desktop.min.css"/>
	 	<link rel="stylesheet" type="text/css" href="<?php echo $urlWeb ?>styles/mobile.min.css"/>
	 	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
	</head>
	<body>
		<?php include_once("includes/analyticstracking.php");?>
		<?php include_once("includes/browsehappy.php");?>
		<div class="innerBody">
			<?php include_once("includes/leftBlockRight.php"); ?>
			<div class="header">
				<?php include_once("includes/headerEffect.php");?>
				<?php include_once("includes/headerButtons.php");?>
				<?php include_once("includes/searchNotices/buscador.php");?>
				<?php include_once("includes/menuPages/menuBlog.php");?>
			</div>
			<div class="innerBodyContent">
				<div class="pageBody pageBlog">
					<?php include_once("pages/blog/content.php");?>
					<?php include_once("pages/blog/allPosts.php");?>
				</div>
				<?php include_once("includes/footer.php");?>
			</div>
			<?php include_once("includes/cookies.php");?>
		</div>
		<div class="hiddenBody"></div>
		<script type="text/javascript" src="<?php echo $urlWeb ?>scripts/efectos.min.js"></script>
	</body>
	<?php include_once("includes/aplazarscripts.php");?>
</html>
<?php mysql_free_result($AllPosts); ?>
<?php mysql_free_result($AllPostsCount); ?>