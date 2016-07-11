<?php require_once('Connections/conexion.php');
	$_SESSION['indice']=2;

	//Slider
	mysql_select_db($database_conexion, $conexion);
	$query_SwiperSlider = "SELECT * FROM z_posts WHERE tipo=1 ORDER BY id DESC LIMIT 5";
	$SwiperSlider = mysql_query($query_SwiperSlider, $conexion) or die(mysql_error());
	$row_SwiperSlider = mysql_fetch_assoc($SwiperSlider);
	$totalRows_SwiperSlider = mysql_num_rows($SwiperSlider);


	//Random Notices
	mysql_select_db($database_conexion, $conexion);
	$query_RandomNotice = "SELECT * FROM z_posts WHERE tipo=1 ORDER BY rand() LIMIT 10"; 
	$RandomNotice = mysql_query($query_RandomNotice, $conexion) or die(mysql_error());
	$row_RandomNotice = mysql_fetch_assoc($RandomNotice);
	$totalRows_RandomNotice = mysql_num_rows($RandomNotice);

	$tipopost = $row_RandomNotice['tipo'];
	

	//All Notices
	mysql_select_db($database_conexion, $conexion);
	$query_AllNotices = "SELECT * FROM z_posts WHERE tipo=1 ORDER BY id DESC LIMIT 4";
	$AllNotices = mysql_query($query_AllNotices, $conexion) or die(mysql_error());
	$row_AllNotices = mysql_fetch_assoc($AllNotices);
	$totalRows_AllNotices = mysql_num_rows($AllNotices);
?>
<!DOCTYPE html>
	<?php include_once("includes/fuckoff.php"); ?>
	<head>
		<meta charset="utf-8">
		<meta name="Author" content="Diesel vs. Gasoline" />
		<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7, IE=9" />
		<title>DVSG</title>
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
				<?php include_once("includes/menuPages/menuHome.php");?>
			</div>
			<div class="innerBodyContent">
				<div class="pageBody pageHome">
					<?php include_once("pages/home/swiperSlider.php");?>
					<?php include_once("pages/home/randomNotices.php");?>
					<div class="advert">
						<?php include_once("includes/addByGoogle.php");?>
					</div>
					<div class="loadMoreNotices">
						<?php include_once("pages/home/allNotices.php");?>
					</div>
					<div class="loadMore" onclick="loadMorePost(2);">LOAD MORE</div>
					<div class="loadingMore">
						<?php include_once("images/svg/spinner.php");?>
					</div>
				</div>
				<?php include_once("includes/footer.php");?>
			</div>
			<?php include_once("includes/cookies.php");?>
		</div>
		<div class="hiddenBody"></div>
		<script type="text/javascript" src="<?php echo $urlWeb ?>scripts/swiper/swiper-slider.min.js"></script>
		<script type="text/javascript" src="<?php echo $urlWeb ?>scripts/swiper/swiper-home.min.js"></script>
		<script type="text/javascript" src="<?php echo $urlWeb ?>scripts/efectos.min.js"></script>
	</body>
	<?php include_once("includes/aplazarscripts.php");?>
</html>
<?php mysql_free_result($SwiperSlider); ?>
<?php mysql_free_result($RandomNotice); ?>
<?php mysql_free_result($AllNotices); ?>