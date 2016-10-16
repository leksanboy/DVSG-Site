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

	//Languages
	mysql_select_db($database_conexion, $conexion);
	$query_languages = "SELECT * FROM z_idiomas ORDER by pais";
	$languages = mysql_query($query_languages, $conexion) or die(mysql_error());
	$row_languages = mysql_fetch_assoc($languages);
	$totalRows_languages = mysql_num_rows($languages);
?>
<!DOCTYPE html>
	<?php include_once("includes/fuckoff.php"); ?>
	<head>
		<meta charset="utf-8">
		<meta name="Author" content="Diesel vs. Gasoline" />
		<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7, IE=9" />
		<title><?php echo traducir(5,$_COOKIE['idioma'])?></title>
		<?php include_once("includes/favicons.php"); ?>
		<meta name="theme-color" content="#<?php echo $row_userData['primary_color']; ?>">
		<link rel="stylesheet" type="text/css" href="<?php echo $urlWeb ?>styles/mobile/pages/cropper-box.min.css"/>
		<link rel="stylesheet" type="text/css" href="<?php echo $urlWeb ?>styles/desktop-user.min.css"/>
		<link rel="stylesheet" type="text/css" href="<?php echo $urlWeb ?>styles/mobile-user.min.css"/>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
		<script type="text/javascript" src="<?php echo $urlWeb ?>scripts/cropper-account/cropper.js"></script>
	</head>
	<body>
		<?php include_once("includes/analyticstracking.php");?>
		<?php include_once("includes/browsehappy.php");?>
		<div class="innerBody">
			<?php include_once("includes/leftBlockRight.php"); ?>
			<div class="header headerUser headerSettings" style="background:#<?php echo $row_userData['primary_color']; ?>">
				<div class="headerEffect">
					<canvas id="headerEffect"></canvas>
				</div>
				
				<div class="menuLeft" onclick="toggleLeftSide(1)">
					<?php include_once("images/svg/menu.php"); ?>
				</div>
				<div class="userName">
					<?php echo $row_userData['name']; ?>
				</div>
				<div class="menuRight" onclick="toggleRightSide(1)">
					<?php include_once("images/svg/circles.php"); ?>
				</div>
				<div class="title">
					<?php echo traducir(5,$_COOKIE['idioma'])?>
				</div>
				<nav id="navItemTabs">
					<ul class="papertabs">
						<li>
							<a href="#/formOne" class="active">
								<?php include_once("images/svg/person.php"); ?>
								<?php echo traducir(1,$_COOKIE['idioma'])?>
								<span class="paperripple">
									<span class="circleNav"></span>
								</span>
							</a>
						</li>
						<li>
							<a href="#/formTwo">
								<?php include_once("images/svg/image.php"); ?>
								<?php echo traducir(2,$_COOKIE['idioma'])?>
								<span class="paperripple">
									<span class="circleNav"></span>
								</span>
							</a>
						</li>
						<li>
							<a href="#/formThree">
								<?php include_once("images/svg/language.php"); ?>
								<?php echo traducir(3,$_COOKIE['idioma'])?>
								<span class="paperripple">
									<span class="circleNav"></span>
								</span>
							</a>
						</li>
						<li>
							<a href="#/formFour">
								<?php include_once("images/svg/lock.php"); ?>
								<?php echo traducir(4,$_COOKIE['idioma'])?>
								<span class="paperripple">
									<span class="circleNav"></span>
								</span>
							</a>
						</li>
					</ul>
				</nav>
			</div>
			<div class="innerBodyContent">
				<div class="pageSettings">
					<?php include_once("pages/user/settings/content.php");?>
				</div>
				<?php include_once("includes/footer.php");?>
			</div>
			<?php include_once("pages/user/settings/account/cropper.php"); ?>
			<?php include_once("pages/user/settings/backgrounds/cropper.php"); ?>
			<?php include_once("includes/cookies.php");?>
		</div>
		<div class="hiddenBody"></div>
		<script type="text/javascript" src="<?php echo $urlWeb ?>scripts/efectos.min.js"></script>
		<script type="text/javascript" src="<?php echo $urlWeb ?>scripts/encrypt/md5.min.js"></script>
		<script type="text/javascript" src="<?php echo $urlWeb ?>scripts/formhelpers/script.js"></script>
	</body>
	<?php include_once("includes/aplazarscripts.php");?>
</html>
<?php mysql_free_result($userData); ?>
<?php mysql_free_result($languages); ?>