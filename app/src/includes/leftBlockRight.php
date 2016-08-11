<?php if (isset($_SESSION['MM_Id'])){ ?>
	
	<?php if (!isset($_SESSION['MM_Id'])){ 
		header("Location: " . $urlWeb );
	}?>

	<?php
		$iddelperfil= $_SESSION['MM_Id'];
		mysql_select_db($database_conexion, $conexion);
		$query_SacarMiNombreUser = sprintf("SELECT * FROM z_users WHERE id=%s",$iddelperfil,"int");
		$SacarMiNombreUser = mysql_query($query_SacarMiNombreUser, $conexion) or die(mysql_error());
		$row_SacarMiNombreUser = mysql_fetch_assoc($SacarMiNombreUser);
		$totalRows_SacarMiNombreUser = mysql_num_rows($SacarMiNombreUser);

		mysql_select_db($database_conexion, $conexion);
		$query_SacarDefault = sprintf("SELECT * FROM z_users_default WHERE id=1","int");
		$SacarDefault = mysql_query($query_SacarDefault, $conexion) or die(mysql_error());
		$row_SacarDefault = mysql_fetch_assoc($SacarDefault);
		$totalRows_SacarDefault = mysql_num_rows($SacarDefault);   
	?>

	<div class="leftSideBlock">
		<div class="userBox">
			<div class="images">
				<?php if ($row_SacarMiNombreUser['avatar_bg1'] === null || $row_SacarMiNombreUser['avatar_bg1'] === '') { ?>
				    <img src="<?php echo $row_SacarDefault['avatar_bg1_default']; ?>" class="fadeImage"/>
				<?php } else { ?>
				    <img src="<?php echo $row_SacarMiNombreUser['avatar_bg1']; ?>" class="fadeImage"/>
				<?php } ?>

				<?php if ($row_SacarMiNombreUser['avatar_bg2'] === null || $row_SacarMiNombreUser['avatar_bg2'] === '') { ?>
				    <img src="<?php echo $row_SacarDefault['avatar_bg2_default']; ?>" class="fadeImage"/>
				<?php } else { ?>
				    <img src="<?php echo $row_SacarMiNombreUser['avatar_bg2']; ?>" class="fadeImage"/>
				<?php } ?>

				<?php if ($row_SacarMiNombreUser['avatar_bg3'] === null || $row_SacarMiNombreUser['avatar_bg3'] === '') { ?>
				    <img src="<?php echo $row_SacarDefault['avatar_bg3_default']; ?>" class="fadeImage"/>
				<?php } else { ?>
				    <img src="<?php echo $row_SacarMiNombreUser['avatar_bg3']; ?>" class="fadeImage"/>
				<?php } ?>
			</div>

			<div class="image">
				<img src="<?php echo $row_SacarMiNombreUser['avatar']; ?>"/>
			</div>
			<ul>
				<li><?php echo $row_SacarMiNombreUser['nombre']; ?></li>
				<li><?php echo $row_SacarMiNombreUser['email']; ?></li>
			</ul>
		</div>
		<div class="menu">
			<ul>
				<li onClick="clickThePage(11)">
					<?php include("images/svg/home.php");?>
					My Page
				</li>
				<li style="opacity:0.35">
					<?php include("images/svg/news.php");?>
					My News
				</li>
				<li onClick="clickThePage(14)">
					<?php include("images/svg/friends.php");?>
					My Friends
					<?php if (AmigosPendientes($_SESSION['MM_Id'])){ ?>
						<div class="pending">+<?php echo saber_cuantos_amigos_estan_sin_confirmar($_SESSION['MM_Id']) ?></div>
					<?php }?>
				</li>
				<li onClick="clickThePage(17)">
					<?php include("images/svg/photos.php");?>
					My Photos
				</li>
				<li onClick="clickThePage(15)">
					<?php include("images/svg/music.php");?>
					My Music
				</li>
				<li onClick="clickThePage(16)">
					<?php include("images/svg/videos.php");?>
					My Videos
				</li>
				<li onClick="clickThePage(13)">
					<?php include("images/svg/messages.php");?>
					My Messages
					<?php if (MensajesPendientes($_SESSION['MM_Id'])){ ?>
						<div class="pending">+<?php echo saber_cuantos_estan_sin_leer($_SESSION['MM_Id']) ?></div>
					<?php }?>
				</li>
				<li style="opacity:0.35">
					<?php include("images/svg/groups.php");?>
					My Groups
				</li>
				<li style="opacity:0.35">
					<?php include("images/svg/favorites.php");?>
					My Favorites
				</li>
				<li onClick="clickThePage(12)">
					<?php include("images/svg/settings.php");?>
					My Settings
				</li>
				<li onClick="location.href='<?php echo $urlWeb ?>'">
					<?php include("images/svg/flash.php");?>
					DVSG Home
				</li>
				<li onClick="logOut()">
					<?php include("images/svg/logout.php");?>
					Log Out
				</li>
			</ul>
		</div>
	</div>

	<div class="leftSideClose" onclick="toggleLeftSide(0)"></div>


	<div class="rightSideBlock">
		<br>
		<br>
		<br>
		<br>
		<center>
			RIGHT SIDE CONTENT
			<br>
			<br>
			Audio Player
			<br>
			Chat
			<br>
			© 2016 DVSG
		</center>
	</div>

	<div class="rightSideClose" onclick="toggleRightSide(0)"></div>


	<div class="logOutWindow">
		<div class="text">You sure to want to leave the page ?</div>
		<div class="button" onClick="logOutFade()">No</div>
		<div class="button" onClick="location.href='<?php echo $logoutAction ?>'">Yes</div>
	</div>
	<div class="logOutWindowHidden" onClick="logOutFade()"></div>


<?php } else {?>
	<div class="webAccess">
		<div class="button" onClick="clickThePage(7)">Create an account</div>
		<div class="button" onclick="singInAccess()">Sign in</div>
	</div>

	<div class="signInBox">
		<form onsubmit="return false"> 
			<div class="error"></div>
			<label for="signinWindowMail">Email</label>
			<input type="email" name="nombre" class="input" id="signinWindowMail"/>
			<label for="signinWindowPassword">Password</label>
			<input type="password" name="password" class="input" id="signinWindowPassword"/>
			<input id="signin-remember-check" data-role="none" type="checkbox" value="on" name="recordar" checked="checked" class="check">
			<label for="signin-remember-check" data-role="none" class="check">Remember me</label>

			<input type="button" onClick="location.href='<?php echo $urlWeb ?>forgot-password'" class="button" value="Forgot password ?"/>
			<input type="submit" onClick="loginAccess(nombre.value,password.value,recordar.value);" class="button signInLoading" value="Sign in"/>
		</form>
	</div>
	<div class="signInBoxHidden" onclick="singInAccess()"></div>
<?php }?>
<?php mysql_free_result($SacarMiNombreUser);?>
<?php mysql_free_result($SacarDefault);?>