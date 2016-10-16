<?php require_once('Connections/conexion.php');
	if (isset($_GET['id'])) {
		$userPageId = $_GET['id'];

		if ($userPageId == '')
			$userPageId = $_SESSION['MM_Id'];
	} else if (!isset($_GET['id'])){
		$userPageId = $_SESSION['MM_Id'];
	}

	//User data
	mysql_select_db($database_conexion, $conexion);
	$query_userData = sprintf("SELECT * FROM z_users WHERE id = %s", 
	GetSQLValueString($userPageId, "int"));
	$userData = mysql_query($query_userData, $conexion) or die(mysql_error());
	$row_userData = mysql_fetch_assoc($userData);
	$totalRows_userData = mysql_num_rows($userData);

	//User photos
	mysql_select_db($database_conexion, $conexion);
	$query_photosList = sprintf("SELECT f.photo, f.date, v.name FROM z_photos_favorites f INNER JOIN z_photos v ON v.id = f.photo WHERE f.user = $userPageId ORDER BY f.date DESC");
	$photosList = mysql_query($query_photosList, $conexion) or die(mysql_error());
	$row_photosList = mysql_fetch_assoc($photosList);
	$totalRows_photosList = mysql_num_rows($photosList);
?>
<!DOCTYPE html>
	<?php include_once("includes/fuckoff.php"); ?>
	<head>
		<meta charset="utf-8">
		<meta name="Author" content="Diesel vs. Gasoline" />
		<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7, IE=9" />
		<title><?php echo $row_userData['name']; ?></title>
		<?php include_once("includes/favicons.php"); ?>
		<meta name="theme-color" content="#<?php echo $row_userData['primary_color']; ?>">
		<link rel="stylesheet" type="text/css" href="<?php echo $urlWeb ?>styles/mobile/pages/modal-box.min.css"/>
	 	<link rel="stylesheet" type="text/css" href="<?php echo $urlWeb ?>styles/desktop-user.min.css"/>
	 	<link rel="stylesheet" type="text/css" href="<?php echo $urlWeb ?>styles/mobile-user.min.css"/>
	 	<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
	</head>
	<body>
		<?php include_once("includes/analyticstracking.php");?>
		<?php include_once("includes/browsehappy.php");?>
		<div class="innerBody">
			<?php include_once("includes/leftBlockRight.php"); ?>
			<div class="header headerUser headerMyPage">
				<div class="menuLeft" onclick="toggleLeftSide(1)">
					<?php include("images/svg/menu.php"); ?>
				</div>

				<div class="userName">
						<?php echo $row_userData['name']; ?>
					</div>

				<div class="menuRight" onclick="toggleRightSide(1)">
					<?php include("images/svg/circles.php"); ?>
				</div>
			</div>
			<div class="innerBodyContent">
				<div class="pageBody pageUser">
					<?php include_once("pages/user/user/content.php");?>
				</div>
				<?php include_once("includes/footer.php");?>
			</div>
			<?php include("pages/user/modal-box.php"); ?>
			<?php include_once("includes/cookies.php");?>
		</div>
		<div class="hiddenBody"></div>
		<script type="text/javascript" src="<?php echo $urlWeb ?>scripts/efectos.min.js"></script>
	</body>
	<?php include_once("includes/aplazarscripts.php");?>
</html>
<?php mysql_free_result($userData); ?>
<?php mysql_free_result($photosList); ?>