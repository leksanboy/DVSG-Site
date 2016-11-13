<?php  if (isset($_SESSION['MM_Id'])) { ?>
	<div class="userData">
		<div class="name" onclick="clickThePage(11)">
			<?php echo userName($_SESSION['MM_Id']); ?>
		</div>
		<div class="avatar" onclick="userDataBox()">
			<img src="<?php echo userAvatar($_SESSION['MM_Id']); ?>">
		</div>
		<div class="dataBox">
			<div class="box">
				<div class="button" onclick="clickThePage(11)">
					<?php include("images/svg/person.php");?>
				</div>
				<div class="button" onclick="clickThePage(13)">
					<?php include("images/svg/messages.php");?>
				</div>
				<div class="button" onclick="clickThePage(12)">
					<?php include("images/svg/settings.php");?>
				</div>
			</div>
		</div>
	</div>
<?php } else { ?>
	<div class="loginData">
		<span onclick="clickThePage(7)">Create an accont</span> | <span onclick="loginBoxAccess(2)">Sign in</span>
		<div class="dataBox">
			<form onsubmit="return false"> 
				<div class="error"></div>
				<label for="signinWindowMail">Email</label>
				<input type="email" name="email" id="signinWindowMail"/>
				<label for="signinWindowPassword">Password</label>
				<input type="password" name="password" id="signinWindowPassword"/>
				<button type="button" onClick="clickThePage(19)">Forgot password ?</button>
				<button type="submit" onClick="loginBoxAccess(1, email.value, password.value);" id="signInLoading">Sign in</button>
			</form>
		</div>
	</div>
<?php } ?>