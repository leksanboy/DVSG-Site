<?php require_once('Connections/conexion.php');

	// LEFT COMBO
	mysql_select_db($database_conexion, $conexion);
		$query_LeftBox="SELECT * FROM z_cars_models GROUP by brand";
		$LeftBox = mysql_query($query_LeftBox, $conexion) or die(mysql_error());
		$row_LeftBox = mysql_fetch_assoc($LeftBox);
		$totalRows_LeftBox = mysql_num_rows($LeftBox);

	// RIGHT COMBO
 	mysql_select_db($database_conexion, $conexion);
		$query_RightBox="SELECT * FROM z_cars_models GROUP by brand";
		$RightBox = mysql_query($query_RightBox, $conexion) or die(mysql_error());
		$row_RightBox = mysql_fetch_assoc($RightBox);
		$totalRows_RightBox = mysql_num_rows($RightBox);

	// DEFAULT IMAGE
	mysql_select_db($database_conexion, $conexion);
		$query_DefaultImage = "SELECT * FROM z_cars_models ORDER BY rand()";
		$DefaultImage = mysql_query($query_DefaultImage, $conexion) or die(mysql_error());
		$row_DefaultImage = mysql_fetch_assoc($DefaultImage);
		$totalRows_DefaultImage = mysql_num_rows($DefaultImage);

	$imageDefault = explode('-:#:-', $row_DefaultImage['images']);

?>
<!DOCTYPE html>
	<?php include_once("includes/fuckoff.php"); ?>
	<head>
		<meta charset="utf-8">
		<meta name="Author" content="Diesel vs. Gasoline" />
		<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7, IE=9" />
		<title>DVSG - Versus</title>
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
				<?php include_once("includes/menuPages/menuVersus.php");?>
			</div>
			<div class="innerBodyContent">
				<div class="pageBody pageVersus">
					<?php include_once("pages/web/versus/content.php");?>
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
<?php mysql_free_result($LeftBox); ?>
<?php mysql_free_result($RightBox); ?>
<?php mysql_free_result($DefaultImage); ?>