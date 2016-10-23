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
			<div class="images">
				<div class="fadeImage" style="background-image: url(<?php echo $row_getUserData['avatar_bg1']; ?>);"></div>
				<div class="fadeImage" style="background-image: url(<?php echo $row_getUserData['avatar_bg2']; ?>);"></div>
				<div class="fadeImage" style="background-image: url(<?php echo $row_getUserData['avatar_bg3']; ?>);"></div>
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
					Sign out
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
			DVSG © 2016
		</center>
	</div>

	<div class="rightSideClose" onclick="toggleRightSide(0)"></div>

	<div class="logOutWindow">
		<div class="text">You sure to want to leave the page ?</div>
		<div class="button" onClick="logOutFade()">No</div>
		<div class="button" onClick="location.href='<?php echo $logoutAction ?>'">Yes</div>
	</div>
	<div class="logOutWindowHidden" onClick="logOutFade()"></div>
<?php } else { ?>
	<div class="webAccess">
		<div class="button" onClick="clickThePage(7)">Sign up</div>
		<div class="button" onclick="loginAccess(2)">Sign in</div>
	</div>

	<div class="signInBox">
		<div class="close" onclick="loginAccess(2)">
			<?php include("images/svg/close.php");?>
		</div>
		<form onsubmit="return false"> 
			<div class="error"></div>
			<label for="signinWindowMail">Email</label>
			<input type="email" name="email" class="input" id="signinWindowMail"/>
			<label for="signinWindowPassword">Password</label>
			<input type="password" name="password" class="input" id="signinWindowPassword"/>
			<input id="signin-remember-check" data-role="none" type="checkbox" value="on" name="recordar" checked="checked" class="check">
			<label for="signin-remember-check" data-role="none" class="check">Remember me</label>

			<input type="button" onClick="clickThePage(19)" class="button" value="Forgot password ?"/>
			<input type="submit" onClick="loginAccess(1, email.value, password.value);" class="button signInLoading" value="Sign in"/>
		</form>
	</div>
	<div class="signInBoxHidden" onclick="loginAccess(2)"></div>
<?php } ?>
<script type="text/javascript">
	function loginAccess(type, email, password) {
	    if (type == 1) {
	        if (email === "" || password === "") {
	            $('.error').fadeIn(300).html('Complete the Fields');
	            setTimeout(function() {
	                $('.error').fadeOut(300);
	            }, 3000);

	            return false;
	        } else {
	            $.ajax({
	                type: 'POST',
	                url: url + 'includes/arrancar.php',
	                data: 'email=' + email + '&password=' + password,
	                success: function(html) {
	                    if (html != "false") {
	                        $('.signInLoading').val("Loading...");
	                        location.reload();
	                        return true;
	                    } else if (html == "false") {
	                        $('.error').fadeIn(300);
	                        setTimeout(function() {
	                            $('.error').fadeOut(500);
	                        }, 3000);
	                        $('.error').html('Email or Password is incorrect');
	                        return false;
	                    }
	                }
	            });
	        }
	    } else if (type == 2) {
	        $('.signInBox').toggleClass('signInBoxActive');
	        $('.signInBoxHidden').toggleClass('signInBoxHiddenActive');
	    }
	}
</script>
<?php mysql_free_result($getUserData);?>