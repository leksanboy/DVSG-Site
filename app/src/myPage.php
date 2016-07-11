<?php require_once('Connections/conexion.php');
	$iddeluser = $_SESSION['MM_Id'];
	$_SESSION['indice']=0;

	//Personal data
	mysql_select_db($database_conexion, $conexion);
	$query_SacarMiPerfil = sprintf("SELECT * FROM z_users WHERE id=%s",$iddeluser,"int");
	$SacarMiPerfil = mysql_query($query_SacarMiPerfil, $conexion) or die(mysql_error());
	$row_SacarMiPerfil = mysql_fetch_assoc($SacarMiPerfil);
	$totalRows_SacarMiPerfil = mysql_num_rows($SacarMiPerfil);

	//Photos
	mysql_select_db($database_conexion, $conexion);
	$query_SacarMisFotos = sprintf("SELECT * FROM z_my_photos WHERE autor=%s ORDER BY id DESC",$_SESSION['MM_Id'],"int");
	$SacarMisFotos = mysql_query($query_SacarMisFotos, $conexion) or die(mysql_error());
	$row_SacarMisFotos = mysql_fetch_assoc($SacarMisFotos);
	$totalRows_SacarMisFotos = mysql_num_rows($SacarMisFotos);

?>
<!DOCTYPE html>
	<?php include_once("includes/fuckoff.php"); ?>
	<head>
		<meta charset="utf-8">
		<meta name="Author" content="Diesel vs. Gasoline" />
		<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7, IE=9" />
		<title><?php echo $row_SacarMiPerfil['nombre']; ?></title>
		<?php include_once("includes/favicons.php"); ?>
	 	<link rel="stylesheet" type="text/css" href="<?php echo $urlWeb ?>styles/desktop-user.min.css"/>
	 	<link rel="stylesheet" type="text/css" href="<?php echo $urlWeb ?>styles/mobile-user.min.css"/>
	 	<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
	</head>
	<body>
		<?php include_once("includes/analyticstracking.php");?>
		<?php include_once("includes/browsehappy.php");?>
		<div class="innerBody">
			<?php include_once("includes/leftBlockRight.php"); ?>
			<div class="header">
				<?php include_once("includes/headerUser/profile.php");?>
			</div>
			<div class="innerBodyContent">
				<div class="pageBody pageProfile">
					<?php include_once("user/myPage/content.php");?>
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
<?php mysql_free_result($SacarMiPerfil); ?>
<?php mysql_free_result($SacarMisFotos); ?>