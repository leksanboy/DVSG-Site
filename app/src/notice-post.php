<?php require_once('Connections/conexion.php');
	if (!isset ($_SESSION['MM_Id'])){
		header("Location: " . $urlWeb );
	}
	if ( rango_admin ($_SESSION['MM_Id']) !=4){
		header("Location: " . $urlWeb );
	}

	//Eliminar Imagenes Terminales
	$deleteSQL = sprintf("DELETE FROM z_post_img_temporal WHERE usuario=%s",
    GetSQLValueString($_SESSION['MM_Id'], "text"));	        
    mysql_select_db($database_conexion, $conexion);
    $Result1 = mysql_query($deleteSQL, $conexion) or die(mysql_error());
?>
<!DOCTYPE html>
	<?php include_once("includes/fuckoff.php"); ?>
	<head>
		<meta charset="utf-8">
		<meta name="Author" content="Diesel vs. Gasoline" />
		<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7, IE=9" />
		<title>DVSG - Creating post</title>
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
				<?php include_once("includes/menuPages/menuGoBack.php");?>
			</div>
			<div class="innerBodyContent">
				<div class="pageBody pageCreateNoticePost">
					<?php include_once("pages/postFunctions/notice/create/content.php");?>	
				</div>
				<?php include_once("includes/footer.php");?>
			</div>
			<?php include_once("includes/cookies.php");?>
		</div>
		<div class="hiddenBody"></div>
		<script type="text/javascript" src="<?php echo $urlWeb ?>scripts/cropper/cropper-settings.js"></script>
		<script type="text/javascript" src="<?php echo $urlWeb ?>scripts/cropper/cropper-functionality.js"></script>
		<script type="text/javascript" src="<?php echo $urlWeb ?>scripts/efectos.min.js"></script>

	</body>
	<?php include_once("includes/aplazarscripts.php");?>
</html>