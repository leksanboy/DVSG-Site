<?php require_once('Connections/conexion.php');
	if (!isset ($_SESSION['MM_Id'])){
		header("Location: " . $urlWeb );
	}

	error_reporting(0);
	$userId = "0";

	if (isset($_SESSION['MM_Id'])) {
		$userId = $_SESSION['MM_Id'];
	}

	//User data
	mysql_select_db($database_conexion, $conexion);
	$query_userData = sprintf("SELECT primary_color, secondary_color FROM z_users WHERE id = %s", 
	GetSQLValueString($userId, "int"));
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
		<title><?php echo traducir(53,$_COOKIE['idioma'])?></title>
		<?php include("includes/favicons.php"); ?>
		<meta name="theme-color" content="#<?php echo $row_userData['primary_color']; ?>">
		<link rel="stylesheet" type="text/css" href="<?php echo $urlWeb ?>styles/mobile/pages/message-window.min.css"/>
		<link rel="stylesheet" type="text/css" href="<?php echo $urlWeb ?>styles/desktop-user.min.css"/>
		<link rel="stylesheet" type="text/css" href="<?php echo $urlWeb ?>styles/mobile-user.min.css"/>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
	</head>
	<body>
		<?php include("includes/analyticstracking.php");?>
		<?php include("includes/browsehappy.php");?>
		<div class="innerBody">
			<?php include("includes/leftBlockRight.php"); ?>
			<div class="header headerUser headerMusic" style="background:#<?php echo $row_userData['primary_color']; ?>">
				<div class="headerEffect">
					<canvas id="headerEffect"></canvas>
				</div>
				
				<div class="menuLeft" onclick="toggleLeftSide(1)">
					<?php include("images/svg/menu.php"); ?>
				</div>

				<div class="userName">
						<?php echo $row_SacarMiPerfil['nombre']; ?>
					</div>

				<div class="menuRight" onclick="toggleRightSide(1)">
					<?php include("images/svg/circles.php"); ?>
				</div>

				<div class="buttonAction" onclick="uploadSong(1)">
					<?php include("images/svg/add.php");?>
				</div>
				
				<div class="title">
					<?php echo traducir(53,$_COOKIE['idioma'])?>
				</div>
				<div class="playerBox">
					<div class="buttons">
						<div class="previous" onclick="playPausePrevNext(2)">
							<?php include("images/svg/previous-track.php");?>
						</div>
						<div class="playPause" onclick="playPausePrevNext(1)">
							<div class="play">
								<?php include("images/svg/play.php");?>
							</div>
							<div class="pause">
								<?php include("images/svg/pause.php");?>
							</div>
						</div>
						<div class="next" onclick="playPausePrevNext(3)">
							<?php include("images/svg/next-track.php");?>
						</div>
					</div>
					<div class="titleAndProgress">
						<div class="titleDuration">
							<div class="text" id="playerBoxAudioPlayingTitle"></div>
							<div class="duration" id="playerBoxAudioPlayingDuration"></div>
						</div>
						<div class="progress">
							<input type="range" id="playerBoxAudioProgress" min="0" max="1000" value="0" onchange="setProgressBar(event.target)"/>
							<div class="buffer" id="playerBoxAudioBuffering"></div>
						</div>
					</div>

					<div id="playerBoxAudioCounter" style="display: none"></div>
					<audio id="playerBoxAudio" preload controls src="" style="display: none"></audio>
				</div>
			</div>
			<div class="innerBodyContent">
				<div class="pageMusic">
					<?php include("pages/user/music/content.php");?>
				</div>
				<?php include("includes/footer.php");?>
			</div>
			<?php include("pages/user/music/showUploadFileBox.php"); ?>
			<?php include("includes/cookies.php");?>
		</div>
		<div class="hiddenBody"></div>
		<script type="text/javascript" src="<?php echo $urlWeb ?>scripts/efectos.min.js"></script>
	</body>
	<?php include("includes/aplazarscripts.php");?>
</html>
<?php mysql_free_result($userData); ?>