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

						<?php
							// $to = '"Sasá Rafalsky" <<a href="mailto:leksanboy@gmail.com">leksanboy@gmail.com</a>>';
							// $subject = 'PHP mail tester';
							// $message = 'This message was sent via PHP!' . PHP_EOL .
							//            'Some other message text.' . PHP_EOL . PHP_EOL .
							//            '-- signature' . PHP_EOL;
							// $headers = 'From: "From Name" <<a href="mailto:from@email.dom">from@email.dom</a>>' . PHP_EOL .
							//            'Reply-To: <a href="mailto:reply@email.com">reply@email.com</a>' . PHP_EOL .
							//            'X-Mailer: PHP/' . phpversion();
							// mail($to, $subject, $message, $headers);

							// if (mail($to, $subject, $message, $headers)) {
							//   echo 'mail() Success!';
							// }
							// else {
							//   echo 'mail() Failed!';
							// }

							// $header = "From: noreply@example.com\r\n"; 
							// $header.= "MIME-Version: 1.0\r\n"; 
							// $header.= "Content-Type: text/html; charset=ISO-8859-1\r\n"; 
							// $header.= "X-Priority: 1\r\n";

							// $status = mail("sasarafalsky@gmail.com", "TITLE", "BODY MESSAGE", $header);

							// if($status)
							// { 
							//     echo '<p>Your mail has been sent!</p>';
							// } else { 
							//     echo '<p>Something went wrong, Please try again!</p>'; 
							// }
						?>
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