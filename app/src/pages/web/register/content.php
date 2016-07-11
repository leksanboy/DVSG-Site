<form onSubmit="return false">
	<label for="nameRegister">Full name</label>
	<input type="text" name="nombre_register" class="inputRegister" onkeyup="registerName(this.value)" id="nameRegister"/>
	<div class="estadoDeName">
		<span class="yes">
			<?php include("images/svg/check.php");?>
		</span>
		<span class="no">
			<?php include("images/svg/close.php");?>
		</span>
		<span class="loader">
			<?php include("images/svg/spinner.php");?>
		</span>
	</div>
	<label for="emailRegister">Email</label>
	<input type="text" name="email_register" class="inputRegister" onkeyup="registerEmail(this.value)" id="emailRegister"/>
	<div class="estadoDeEmail">
		<span class="yes">
			<?php include("images/svg/check.php");?>
		</span>
		<span class="no">
			<?php include("images/svg/close.php");?>
		</span>
		<span class="loader">
			<?php include("images/svg/spinner.php");?>
		</span>
		<span class="padlock">
			<?php include("images/svg/padlock.php");?>
		</span>
	</div>
	<label for="passwordRegister">Password</label>
	<input type="password" name="password_register" class="inputRegister" onkeyup="registerPassword(this.value)" id="passwordRegister"/>
	<div class="estadoDePassword">
		<span class="yes">
			<?php include("images/svg/check.php");?>
		</span>
		<span class="no">
			<?php include("images/svg/close.php");?>
		</span>
		<span class="loader">
			<?php include("images/svg/spinner.php");?>
		</span>
	</div>
	<label for="captchaRegister" class="capcha"><?php $aleatorio=md5(rand()); echo substr($aleatorio,'0','7'); ?></label>
	<input type="text" name="suma" class="inputRegister" onkeyup="registerCaptcha(this.value)" size="6" id="captchaRegister"/>
	<div class="estadoDeCaptcha">
		<span class="yes">
			<?php include("images/svg/check.php");?>
		</span>
		<span class="no">
			<?php include("images/svg/close.php");?>
		</span>
		<span class="loader">
			<?php include("images/svg/spinner.php");?>
		</span>
	</div>
	<div class="terms">As requirements to register, you agree to the <a href="#">Terms of Service</a> and Privacy Policy, including the use of cookies.</div>
	<input type="submit" name="button" class="button buttonRegister" value="Sign up" onclick="registro(nombre_register.value,email_register.value,password_register.value,suma.value);"/>
	<input type="hidden" name="MM_insert" value="form1" />
</form>