<?php require_once('Connections/conexion.php');?>
<!DOCTYPE html>
	<?php include_once("includes/fuckoff.php"); ?>
	<head>
		<meta charset="utf-8">
		<meta name="Author" content="Diesel vs. Gasoline" />
		<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7, IE=9" />
		<title>DVSG - Error</title>
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
				<?php include_once("includes/menuPages/menuGoBack.php");?>
			</div>
			<div class="innerBodyContent">
				<div class="pageBody">
					<div>
						<br><br><br><br><br><br>
						<center> -ERROR- </center>
						<br><br><br><br><br><br>
						<!-- <?php phpinfo(); ?> -->

						<!-- <?php 
							// import('ffmpeg');
							// $ffmpeg = '/usr/bin/ffmpeg';
							// echo $ffmpeg;
							$command = 'ffmpeg -version';
							$path = '/tmp';

							exec($command, $path, $returncode);
							if ($returncode == 127)
							{
								echo 'ffmpeg is NOT available';
								die();
							}else{
								echo 'ffmpeg is available';
							}

						?> -->
					</div>
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