<form onSubmit="return false">
	<div class="pageTitle">Log in</div>
	<label for="emailRegister">Email</label>
	<input type="email" name="email" class="inputRegister"/>
	<label for="passwordRegister">Password</label>
	<input type="password" name="password" class="inputRegister"/>
	<button type="submit" class="button buttonRegister" onclick="loginAccount(email.value, password.value)">
		<?php include("images/svg/spinner.php");?>
		Sign in
	</button>
</form>

<script type="text/javascript">
	function loginAccount(email, password){
		if (validateEmail(email) && password != '') {
			$.ajax({
	            type: 'POST',
	            url: url + 'includes/arrancar.php',
	            data: 'email=' + email + '&password=' + password,
	            success: function(response) {
	                if (response === "false") {
	                    $('.buttonRegister').css('background','#F44336');

	                    setTimeout(function() {
				            $('.buttonRegister').css('background','#2196F3');
				        }, 900);
	                }else{
	                	$('.buttonRegister svg').show();

	                	setTimeout(function() {
	                		$('.buttonRegister svg').hide();
				            document.location.href = '<?php echo $urlWeb ?>'+'id'+response;
	                	}, 1200);
	                }
	            }
	        });
		}else{
			$('.buttonRegister').css('background','#F44336');

			setTimeout(function() {
	            $('.buttonRegister').css('background','#2196F3');
	        }, 900);
		}
	}

	//Email validation key
	var validateEmail = function(data) {
	    var validar = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
	    return validar.test(data);
	};
</script>