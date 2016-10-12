<form onSubmit="return false">
	<label for="nameRegister">Full name</label>
	<input type="text" name="fullname" class="inputRegister" onkeyup="validateParameters(1, this.value)" id="nameRegister"/>
	<div class="nameStatus">
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
	<input type="text" name="email" class="inputRegister" onkeyup="validateParameters(2, this.value)" id="emailRegister"/>
	<div class="emailStatus">
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
	<input type="password" name="password" class="inputRegister" onkeyup="validateParameters(3, this.value)" id="passwordRegister"/>
	<div class="passwordStatus">
		<span class="strength">
			<div class="bar"></div>
		</span>
	</div>
	<div class="terms">As requirements to register, you agree to the <a href="#">Terms of Service</a> and Privacy Policy, including the use of cookies.</div>
	<button type="submit" class="button buttonRegister" onclick="createAccount(1, fullname.value, email.value, password.value)">
		<?php include("images/svg/spinner.php");?>
		Sign up
	</button>
</form>

<div class="registerPopup">
	<div class="inner">
		<div class="text">
			Created successfully!
			<?php include("images/svg/check.php");?>
		</div>
		<div class="button" onclick="createAccount(2)">Access</div>
	</div>
</div>

<script type="text/javascript">
	var loaderName 			= $(".nameStatus span.loader"),
	    yesName 			= $(".nameStatus span.yes"),
	    noName 				= $(".nameStatus span.no"),
	    padlockEmail 		= $(".emailStatus span.padlock"),
	    loaderEmail 		= $(".emailStatus span.loader"),
	    yesEmail 			= $(".emailStatus span.yes"),
	    noEmail 			= $(".emailStatus span.no"),
	    strengthPassword 	= $(".passwordStatus span.strength .bar");

	var emailIsValid 		= false;

	function validateParameters(type, value){
		if (type == 1) { //Full name
			loaderName.show();

		    if (loaderName.is(':visible')) {
		        yesName.hide();
		        noName.hide();
		    }

		    setTimeout(function() {
		        if (value.trim().length > 0) {
		            yesName.show();
		            noName.hide();
		            loaderName.hide();
		        } else {
		            yesName.hide();
		            noName.show();
		            loaderName.hide();
		        }
		    }, 1200);
		}else if (type == 2) { //Email
			emailIsValid = false;

			if (value.indexOf("@") > 0) {
		        loaderEmail.show();

		        if (loaderEmail.is(':visible')) {
		            yesEmail.hide();
		            noEmail.hide();
		            padlockEmail.hide();
		        }

		        setTimeout(function() {
		            if (validateEmail(value)) {
		                $.ajax({
		                    type: 'POST',
		                    url: url + 'pages/web/register/check-register.php',
		                    data: 'email=' + value,
		                    success: function(html) {
		                        if (html === "false") {
		                            padlockEmail.show();
		                            noEmail.hide();
		                            yesEmail.hide();
		                            loaderEmail.hide();
		                            emailIsValid = false;
		                        } else {
		                            padlockEmail.hide();
		                            noEmail.hide();
		                            yesEmail.show();
		                            loaderEmail.hide();
		                            emailIsValid = true;
		                        }
		                    }
		                });
		            } else {
		                noEmail.show();
		                yesEmail.hide();
		                loaderEmail.hide();
		                padlockEmail.hide();
		            }
		        }, 1200);
		    } else if (value.length > 0) {
		        loaderEmail.show();
		        noEmail.hide();

		        setTimeout(function() {
		            loaderEmail.hide();
		            noEmail.show();
		        }, 1200);
		    } else {
		        noEmail.hide();
		        yesEmail.hide();
		    }

		    //Email validation key
			var validateEmail = function(data) {
			    var validar = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
			    return validar.test(data);
			}
		} else if (type == 3) { //Password
	        var strength = 0; //initial strength
	        if (value.length > 3) strength += 1 //length is ok, lets continue. if length is 8 characters or more, increase strength value
	        if (value.match(/([a-z].*[A-Z])|([A-Z].*[a-z])/))  strength += 1 //if value contains both lower and uppercase characters, increase strength value
	        if (value.match(/([!,%,&,@,#,$,^,*,?,_,~])/))  strength += 1 //if it has one special character, increase strength value
	        if (value.match(/(.*[!,%,&,@,#,$,^,*,?,_,~].*[!,",%,&,@,#,$,^,*,?,_,~])/)) strength += 1 //if it has two special characters, increase strength value
	     
	        //if value is less than 2
	    	if (strength == 0) {
	    		strengthPassword.css('width', '0');
	        } else if (strength < 2) {
	            strengthPassword.css({'width':'33%','background':'#F44336'});
	        } else if (strength == 2 ) {
	            strengthPassword.css({'width':'66%','background':'#388E3C'});
	        } else {
	            strengthPassword.css({'width':'100%','background':'#2196F3'});
	        }
		}
	}

	function createAccount(type, name, email, password){
		if (type == 1) {
			if (name != '' && emailIsValid == true && password != '') {
				$.ajax({
		            type: 'POST',
		            url: url + 'pages/web/register/check-register.php',
		            data: 'name=' + name + '&email=' + email + '&password=' + password,
		            success: function(response) {
		                if (response === "false") {
		                    $('.buttonRegister').css('background','#F44336');
		                } else {
		                	$('.buttonRegister').css('background','#2196F3');
		                	$('.buttonRegister svg').show();
		                	
		                	setTimeout(function() {
		                		$('.buttonRegister svg').hide();
		                		$('.registerPopup').fadeIn();
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
		} else if (type==2) {
	        location.href = url;
		}
	}
</script>