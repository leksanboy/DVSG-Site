<?php require_once('Connections/conexion.php');
	if (!isset ($_SESSION['MM_Id'])){
		header("Location: " . $urlWeb );
	} else {
		$userPageId = $_SESSION['MM_Id'];
	}

	//User data
	mysql_select_db($database_conexion, $conexion);
	$query_userData = sprintf("SELECT * FROM z_users WHERE id = %s", 
	GetSQLValueString($userPageId, "int"));
	$userData = mysql_query($query_userData, $conexion) or die(mysql_error());
	$row_userData = mysql_fetch_assoc($userData);
	$totalRows_userData = mysql_num_rows($userData);
?>
<!DOCTYPE html>
	<?php include("includes/fuckoff.php"); ?>
	<head>
		<meta charset="utf-8">
		<meta name="Author" content="Diesel vs. Gasoline" />
		<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7, IE=9" />
		<title><?php echo traducir(90,$_COOKIE['idioma'])?></title>
		<?php include("includes/favicons.php"); ?>
		<meta name="theme-color" content="#<?php echo $row_userData['primary_color']; ?>">
		<link rel="stylesheet" type="text/css" href="<?php echo $urlWeb ?>styles/mobile/pages/modal-box.min.css"/>
		<link rel="stylesheet" type="text/css" href="<?php echo $urlWeb ?>styles/desktop-user.min.css"/>
		<link rel="stylesheet" type="text/css" href="<?php echo $urlWeb ?>styles/mobile-user.min.css"/>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
	</head>
	<body>
		<?php include("includes/analyticstracking.php");?>
		<?php include("includes/browsehappy.php");?>
		<div class="innerBody">
			<?php include("includes/leftBlockRight.php"); ?>
			<div class="header headerUser headerNews">
				<div class="headerEffect">
					<canvas id="headerEffect"></canvas>
				</div>

				<?php  if (isset($_SESSION['MM_Id'])) { ?>
					<div class="menuLeft" onclick="toggleMenu('left', 1)">
						<?php include("images/svg/menu.php"); ?>
					</div>
					<div class="menuRight" onclick="toggleMenu('right', 1)">
						<?php include("images/svg/circles.php"); ?>
					</div>
				<?php } ?>

				<?php include("includes/userDataLogin.php"); ?>

				<div class="title">
					<?php echo traducir(90,$_COOKIE['idioma'])?>
				</div>
			</div>
			<div class="innerBodyContent">
				<div class="pageBody pageUser">
					<?php include("pages/user/news/content.php");?>
				</div>
				<?php include("includes/footer.php");?>
			</div>
			<?php include("pages/user/modal-box.php"); ?>
			<?php include("includes/cookies.php");?>
		</div>
		<div class="hiddenBody"></div>
		<script type="text/javascript" src="<?php echo $urlWeb ?>scripts/efectos.min.js"></script>
	</body>
	<?php include("includes/aplazarscripts.php");?>
</html>
<?php mysql_free_result($userData); ?>
<?php mysql_free_result($newsList); ?>