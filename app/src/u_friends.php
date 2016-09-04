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

	//My friends
	$query_myFriends = sprintf("SELECT * FROM z_friends WHERE receiver = %s AND status = 1 OR sender = %s AND status = 1",
	GetSQLValueString($userPageId,"int"),
	GetSQLValueString($userPageId,"int"));
	$myFriends = mysql_query($query_myFriends, $conexion) or die(mysql_error());
	$row_myFriends = mysql_fetch_assoc($myFriends);
	$totalRows_myFriends = mysql_num_rows($myFriends);

	//Pending friends 'sent'
	$query_pendingSent = sprintf("SELECT * FROM z_friends WHERE sender = %s AND status = 0",
	GetSQLValueString($_SESSION['MM_Id'],"int"));
	$pendingSent = mysql_query($query_pendingSent, $conexion) or die(mysql_error());
	$row_pendingSent = mysql_fetch_assoc($pendingSent);
	$totalRows_pendingSent = mysql_num_rows($pendingSent);

	//Pending friends 'received'
	$query_pendingReceived = sprintf("SELECT * FROM z_friends WHERE receiver = %s AND status = 0",
	GetSQLValueString($_SESSION['MM_Id'],"int"));
	$pendingReceived = mysql_query($query_pendingReceived, $conexion) or die(mysql_error());
	$row_pendingReceived = mysql_fetch_assoc($pendingReceived);
	$totalRows_pendingReceived = mysql_num_rows($pendingReceived);
?>
<!DOCTYPE html>
	<?php include_once("includes/fuckoff.php"); ?>
	<head>
		<meta charset="utf-8">
		<meta name="Author" content="Diesel vs. Gasoline" />
		<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7, IE=9" />
		<title><?php echo traducir(89,$_COOKIE['idioma'])?></title>
		<?php include_once("includes/favicons.php"); ?>
		<meta name="theme-color" content="#<?php echo $row_userData['primary_color']; ?>">
		<link rel="stylesheet" type="text/css" href="<?php echo $urlWeb ?>styles/desktop-user.min.css"/>
		<link rel="stylesheet" type="text/css" href="<?php echo $urlWeb ?>styles/mobile-user.min.css"/>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
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

				<?php  if (isset($_SESSION['MM_Id'])) { ?>
					<div class="menuLeft" onclick="toggleLeftSide(1)">
						<?php include("images/svg/menu.php"); ?>
					</div>
					<div class="userName">
						<?php echo $row_userData['name']; ?>
					</div>
					<div class="menuRight" onclick="toggleRightSide(1)">
						<?php include("images/svg/circles.php"); ?>
					</div>
				<?php } ?>
				
				<div class="title">
					<?php echo traducir(89,$_COOKIE['idioma'])?>
				</div>

				<nav id="navItemTabs">
					<ul class="papertabs">
						<li>
							<a href="#/formOne" class="active">
								<?php include("images/svg/friends.php"); ?>
								Friends
								<span class="paperripple">
									<span class="circleNav"></span>
								</span>
							</a>
						</li>
						<?php if ($row_userData['id'] == $_SESSION['MM_Id']) { ?>
							<li>
								<a href="#/formTwo">
									<?php include("images/svg/friends-received.php"); ?>
									Received
									<span class="paperripple">
										<span class="circleNav"></span>
									</span>
								</a>
							</li>
							<li>
								<a href="#/formThree">
									<?php include("images/svg/friends-sent.php"); ?>
									Sent
									<span class="paperripple">
										<span class="circleNav"></span>
									</span>
								</a>
							</li>
						<?php } ?>
					</ul>
				</nav>
			</div>
			<div class="innerBodyContent">
				<div class="pageFriends">
					<?php include_once("pages/user/friends/content.php");?>
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
<?php mysql_free_result($userData); ?>
<?php mysql_free_result($myFriends); ?>
<?php mysql_free_result($pendingSent); ?>
<?php mysql_free_result($pendingReceived); ?>