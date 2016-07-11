<?php require('Connections/conexion.php');

	$iddelbrand = UrlAmigableBrands($_GET['marca']);

	//Get Brand data
	mysql_select_db($database_conexion, $conexion);
	$query_GetBrandModel = sprintf("SELECT * FROM z_cars_brands WHERE id=%s",$iddelbrand,"int");
	$GetBrandModel = mysql_query($query_GetBrandModel, $conexion) or die(mysql_error());
	$row_GetBrandModel = mysql_fetch_assoc($GetBrandModel);
	$totalRows_GetBrandModel = mysql_num_rows($GetBrandModel);

	$carbrand = GetCarsBrand($iddelbrand);

	//Get models data
	mysql_select_db($database_conexion, $conexion);
	$query_GetModels = sprintf("SELECT * FROM z_cars_models WHERE brand=%s ORDER BY year DESC",
	GetSQLValueString($carbrand, "text"));
	$GetModels = mysql_query($query_GetModels, $conexion) or die(mysql_error());
	$row_GetModels = mysql_fetch_assoc($GetModels);
	$totalRows_GetModels = mysql_num_rows($GetModels);

?>
<!DOCTYPE html>
	<?php include_once("includes/fuckoff.php"); ?>
	<head>
		<meta charset="utf-8">
		<meta name="Author" content="Diesel vs. Gasoline" />
		<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7, IE=9" />
		<title>DVSG - <?php echo $row_GetBrandModel['brand']; ?></title>
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
				<?php include_once("includes/menuPages/menuGoBackCars.php");?>
			</div>
			<div class="innerBodyContent">
				<div class="pageBody pageCarsBrandModels">
					<div class="brand">
						<img src="<?php echo $row_GetBrandModel['image']; ?>">
						<p><?php echo $row_GetBrandModel['brand']; ?></p>
					</div>
					<?php do { ?>
						<?php if($row_GetModels['year'] != $_SESSION['sesionYear']){?>
							<div class="modelYear">
								<?php echo $row_GetModels['year']; ?>
							</div>
						<?php } $_SESSION['sesionYear'] = $row_GetModels['year']; ?>
						<a class="modelBox" onclick="return createTimedLink(this, myFunction, 600);" href="<?php echo $urlWeb ?>model/<?php echo $row_GetModels['url']; ?>">
							<div class="image">
								<img src="<?php echo $row_GetModels['image']; ?>">
							</div>
							<div class="title">
								<?php echo $row_GetModels['model']; ?>
							</div>

							<div class="price">
								<?php echo $row_GetModels['price']; ?>
							</div>

							<div class="data">
								<?php include("images/svg/likes.php");?>
								<?php echo $row_GetModels['likes']; ?>
								<?php include("images/svg/views.php");?>
								<?php echo $row_GetModels['views']; ?>
								<?php include("images/svg/comments.php");?>
								<?php echo $row_GetModels['comments']; ?>
							</div>
							<squarebox></squarebox>
						</a>
					<?php } while ($row_GetModels = mysql_fetch_assoc($GetModels)); ?>
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
<?php mysql_free_result($GetBrandModel); ?>
<?php mysql_free_result($GetModels); ?>