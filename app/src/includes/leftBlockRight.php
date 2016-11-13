<?php 
	if (isset($_SESSION['MM_Id'])){ 
		if (!isset($_SESSION['MM_Id'])){ 
			header("Location: " . $urlWeb);
		}

		$userId = $_SESSION['MM_Id'];
		mysql_select_db($database_conexion, $conexion);
		$query_getUserData = sprintf("SELECT * FROM z_users WHERE id = %s", $userId, "int");
		$getUserData = mysql_query($query_getUserData, $conexion) or die(mysql_error());
		$row_getUserData = mysql_fetch_assoc($getUserData);
		$totalRows_getUserData = mysql_num_rows($getUserData);
?>
	<div class="leftSideBlock">
		<div class="userBox">
			<!-- <div class="images">
				<div class="fadeImage" style="background-image: url(<?php echo $row_getUserData['avatar_bg1']; ?>);"></div>
				<div class="fadeImage" style="background-image: url(<?php echo $row_getUserData['avatar_bg2']; ?>);"></div>
				<div class="fadeImage" style="background-image: url(<?php echo $row_getUserData['avatar_bg3']; ?>);"></div>
			</div> -->
			<div class="backgroundImage">
				<div class="cover" style="background-image: url(<?php echo $row_getUserData['avatar_bg1']; ?>)"></div>
			</div>

			<div class="image">
				<img src="<?php echo $row_getUserData['avatar']; ?>"/>
			</div>
			<ul>
				<li><?php echo $row_getUserData['name']; ?></li>
				<li><?php echo $row_getUserData['email']; ?></li>
			</ul>
		</div>
		<div class="menu">
			<ul>
				<li onClick="clickThePage(11)">
					<?php include("images/svg/home.php");?>
					My Page
				</li>
				<li onClick="clickThePage(18)">
					<?php include("images/svg/news.php");?>
					My News
				</li>
				<li onClick="clickThePage(14)">
					<?php include("images/svg/friends.php");?>
					My Friends
					<?php if (pendingFriendsToConfirm($_SESSION['MM_Id']) > 0){ ?>
						<div class="pending">+<?php echo pendingFriendsToConfirm($_SESSION['MM_Id']) ?></div>
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
					<?php if (pendingMessagesToRead($_SESSION['MM_Id']) > 0){ ?>
						<div class="pending">+<?php echo pendingMessagesToRead($_SESSION['MM_Id']) ?></div>
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
				<li onClick="logOut(1)">
					<?php include("images/svg/logout.php");?>
					Sign out
				</li>
			</ul>
		</div>
	</div>

	<div class="leftSideClose" onclick="toggleMenu('left',2)"></div>


	<div class="rightSideBlock">
		<br>
		<br>
		<br>
		<br>
		<center>
			<br>
			<br>
			Audio Player
			<br>
			Chat
			<br>
			DVSG © 2016
		</center>
	</div>

	<div class="rightSideClose" onclick="toggleMenu('right', 2)"></div>

	<div class="logOutWindow">
		<div class="text">You sure to want to leave the page ?</div>
		<div class="button" onClick="logOut(2)">No</div>
		<div class="button" onClick="location.href='<?php echo $logoutAction ?>'">Yes</div>
	</div>
	<div class="logOutWindowHidden" onClick="logOut(2)"></div>
<?php } else { ?>
	<div class="webAccess">
		<div class="button" onclick="clickThePage(7)">Sign up</div>
		<div class="button" onclick="loginAccess(2)">Sign in</div>
	</div>

	<div class="signInBox">
		<div class="close" onclick="loginAccess(2)">
			<?php include("images/svg/close.php");?>
		</div>
		<form onsubmit="return false"> 
			<div class="error"></div>
			<label for="signinWindowMail">Email</label>
			<input type="email" name="email" id="signinWindowMail"/>
			<label for="signinWindowPassword">Password</label>
			<input type="password" name="password" id="signinWindowPassword"/>
			<button type="button" onClick="clickThePage(19)">Forgot password ?</button>
			<button type="submit" onClick="loginAccess(1, email.value, password.value);" id="signInLoading">Sign in</button>
		</form>
	</div>
	<div class="signInBoxHidden" onclick="loginAccess(2)"></div>
<?php } ?>
<?php mysql_free_result($getUserData);?>