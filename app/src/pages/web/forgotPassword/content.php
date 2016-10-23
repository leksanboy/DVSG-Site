<form onSubmit="return false" id="forgotPassword">
	<div class="pageTitle">Forgot password</div>
	<label for="emailRegister">Email</label>
	<input type="email" name="email" class="inputRegister"/>
	<button type="submit" class="button buttonRegister" onclick="forgotPassword(email.value)">
		<?php include("images/svg/spinner.php");?>
		Send
	</button>
</form>

<div class="pageTitle" id="forgotPasswordDone" style="display: none">Check your email</div>

<script type="text/javascript">
	function forgotPassword(email){
		if (validateEmail(email)) {
			$.ajax({
	            type: 'POST',
	            url: '<?php echo $urlWeb ?>' + 'pages/web/forgotPassword/recover.php',
	            data: 'email=' + email,
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
	                		$('#forgotPassword').hide();
	                		$('#forgotPasswordDone').show();
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