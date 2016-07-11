<?php require_once('Connections/conexion.php');
	if (!isset ($_SESSION['MM_Id'])){
		header("Location: " . $urlWeb );
	}
	if (!isset ($_GET['id'])){
		header("Location: " . $urlWeb );
	}

	$iddelpost = $_GET['id'];

	mysql_select_db($database_conexion, $conexion);
	$query_GetPost = sprintf("SELECT * FROM z_posts WHERE id=%s",$iddelpost,"int");
	$GetPost = mysql_query($query_GetPost, $conexion) or die(mysql_error());
	$row_GetPost = mysql_fetch_assoc($GetPost);
	$totalRows_GetPost = mysql_num_rows($GetPost);

	$tipopost = $row_GetPost['tipo']; 

	if ($row_GetPost['autor']!=$_SESSION['MM_Id']){
		header("Location: " . $urlWeb );
	}

	if($row_GetPost['imagen1'] != ''){
		$deleteSQL = sprintf("DELETE FROM z_post_img_temporal WHERE usuario=%s",
		GetSQLValueString($_SESSION['MM_Id'], "text"));	        
		mysql_select_db($database_conexion, $conexion);
		$Result1 = mysql_query($deleteSQL, $conexion) or die(mysql_error());

		$imagenes=explode('-:#:-', $row_GetPost['imagen1']);
		$cantidadImagenes=count($imagenes);

		for ($i=0; $i < $cantidadImagenes; $i++){
			$insertSQL = sprintf("INSERT INTO z_post_img_temporal (usuario, imagen) VALUES (%s, %s)",
			GetSQLValueString($_SESSION['MM_Id'], "text"),
			GetSQLValueString($imagenes[$i], "text"));
			mysql_select_db($database_conexion, $conexion);
			$Result1 = mysql_query($insertSQL, $conexion) or die(mysql_error());
		}
	}
?>
<!DOCTYPE html>
	<?php include_once("includes/fuckoff.php"); ?>
	<head>
		<meta charset="utf-8">
		<meta name="Author" content="Diesel vs. Gasoline" />
		<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7, IE=9" />
		<title>DVSG - Editing post</title>
		<?php include_once("includes/favicons.php"); ?>
		<link rel="stylesheet" type="text/css" href="<?php echo $urlWeb ?>styles/cropper/cropper.settings.css"/>
		<link rel="stylesheet" type="text/css" href="<?php echo $urlWeb ?>styles/cropper/cropper.functionality.css"/>
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
				<?php if ($tipopost == 1){ ?>
					<?php include_once("includes/menuPages/menuGoBack.php");?>
				<?php } ?>
				<?php if ($tipopost == 2){ ?>
					<?php include_once("includes/menuPages/menuGoBackShop.php");?>
				<?php } ?>
			</div>
			<div class="innerBodyContent">
				<?php if ($tipopost == 1){ ?>
					<div class="pageBody pageCreateNoticePost">
						<?php include_once("pages/postFunctions/notice/edit/content.php");?>
					</div>
				<?php } ?>
				<?php if ($tipopost == 2){ ?>
					<div class="pageBody pageCreateShopPost">
						<?php include_once("pages/postFunctions/shop/edit/content.php");?>
					</div>
				<?php } ?>
				<?php if ($tipopost == 3){ ?>
					<div class="pageBody pageCreateShopPost">
						<?php include_once("pages/postFunctions/blog/edit/content.php");?>
					</div>
				<?php } ?>
				<?php include_once("includes/footer.php");?>
			</div>
			<?php include_once("includes/cookies.php");?>
		</div>
		<div class="hiddenBody"></div>
		<script type="text/javascript" src="<?php echo $urlWeb ?>scripts/cropper/cropper-settings.js"></script>
		<script type="text/javascript" src="<?php echo $urlWeb ?>scripts/cropper/cropper-functionality.js"></script>
		<script type="text/javascript" src="<?php echo $urlWeb ?>scripts/formhelpers/script.js"></script>
		<script type="text/javascript" src="<?php echo $urlWeb ?>scripts/efectos.min.js"></script>
	</body>
	<?php include_once("includes/aplazarscripts.php");?>
</html>
<?php mysql_free_result($GetPost);?>