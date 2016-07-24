<?php require_once('Connections/conexion.php');

	$_SESSION['indice']=0;

	//All Posts
	mysql_select_db($database_conexion, $conexion);
	if (isset($_SESSION['shopFilter'])) {
		$shopOrder = $_SESSION['shopFilter'];
	}else{
		$shopOrder = 'ORDER BY id DESC';
	}

	if (isset($_SESSION['shopSearch'])) {
		$consulta = $_SESSION['shopSearch'];
	}else{
		$consulta = 'WHERE tipo=2';
	}

	$query_AllPosts = "SELECT * FROM z_posts $consulta $shopOrder LIMIT 2";
	$AllPosts = mysql_query($query_AllPosts, $conexion) or die(mysql_error());
	$row_AllPosts = mysql_fetch_assoc($AllPosts);
	$totalRows_AllPosts = mysql_num_rows($AllPosts);

	//Select Brand on Selectores on content
	mysql_select_db($database_conexion, $conexion);
	$query_selectBrand = "SELECT brand FROM z_posts WHERE tipo=2 GROUP by brand";
	$selectBrand = mysql_query($query_selectBrand, $conexion) or die(mysql_error());
	$row_selectBrand = mysql_fetch_assoc($selectBrand);
	$totalRows_selectBrand = mysql_num_rows($selectBrand);
?>
<!DOCTYPE html>
	<?php include_once("includes/fuckoff.php"); ?>
	<head>
		<meta charset="utf-8">
		<meta name="Author" content="Diesel vs. Gasoline" />
		<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7, IE=9" />
		<title>DVSG - Shop</title>
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
				<?php include_once("includes/menuPages/menuShop.php");?>
			</div>
			<div class="innerBodyContent">
				<div class="pageBody pageShop">
					<?php include_once("pages/web/shop/content.php");?>
					<?php include_once("pages/web/shop/allPosts.php");?>
					<!-- <div class="advert"></div>

					<div class="loadMoreNotices">
						<?php #include_once("pages/home/allPosts.php");?>
					</div>
					<div class="loadMore" onclick="loadMorePost(2);">LOAD MORE</div>
					<div class="loadingMore">
						<?php #include_once("images/svg/spinner.php");?>
					</div> -->
				</div>
				<?php include_once("includes/footer.php");?>
			</div>
			<?php include_once("includes/cookies.php");?>
		</div>
		<div class="hiddenBody"></div>
		<script type="text/javascript" src="<?php echo $urlWeb ?>scripts/formhelpers/script.js"></script>
		<script type="text/javascript" src="<?php echo $urlWeb ?>scripts/efectos.min.js"></script>
	</body>
	<?php include_once("includes/aplazarscripts.php");?>
</html>
<?php mysql_free_result($AllPosts); ?>
<?php mysql_free_result($AllPostsCount); ?>
<?php mysql_free_result($selectBrand); ?>