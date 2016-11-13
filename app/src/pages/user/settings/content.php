<form id="formOne" onSubmit="return false"> <!-- Account -->
	<div class="avatarBox">
		<div class="avatar" onclick="openModalAccount(1)">
			<?php include("images/svg/photos.php"); ?>
			<img src="<?php echo $row_userData['avatar_original']; ?>" alt="Avatar">
			<input type="hidden" name="avatar" value="<?php echo $row_userData['avatar']; ?>">
			<input type="hidden" name="avatar_original" value="<?php echo $row_userData['avatar_original']; ?>">
		</div>
	</div>

	<div class="colorBox" id="colorBoxTheme">
		<div class="title">Theme color</div>
		<div class="buttons">
			<button onclick="colorTheme('light')">Light</button>
			<button onclick="colorTheme('dark')">Dark</button>
		</div>
	</div>

	<div class="colorBox" id="colorBoxPrimary">
		<div class="title">Header color</div>
		<div class="selector">
			<button onclick="colorPicker(1)" id="buttonPrimary" style="background:#<?php echo $row_userData['primary_color']; ?>">Choose</button>
			<label>
				<?php include("images/svg/hashtag.php"); ?>
			</label>
			<input name="primary-color" 
				type="text" 
				onkeyup="colorPicker(2, this.value)" 
				maxlength="6" 
				placeholder="0099ff" 
				value="<?php echo $row_userData['primary_color']; ?>"
			/>
		</div>

		<div class="box">
			<div class="btn button-red" onclick="colorPicker(3, 'button-red')"></div>
			<div class="btn button-pink" onclick="colorPicker(3, 'button-pink')"></div>
			<div class="btn button-purple" onclick="colorPicker(3, 'button-purple')"></div>
			<div class="btn button-deeppurple" onclick="colorPicker(3, 'button-deeppurple')"></div>
			<div class="btn button-indigo" onclick="colorPicker(3, 'button-indigo')"></div>
			<div class="btn button-blue" onclick="colorPicker(3, 'button-blue')"></div>
			<div class="btn button-lightblue" onclick="colorPicker(3, 'button-lightblue')"></div>
			<div class="btn button-teal" onclick="colorPicker(3, 'button-teal')"></div>
			<div class="btn button-green" onclick="colorPicker(3, 'button-green')"></div>
			<div class="btn button-lightgreen" onclick="colorPicker(3, 'button-lightgreen')"></div>
			<div class="btn button-lime" onclick="colorPicker(3, 'button-lime')"></div>
			<div class="btn button-yellow" onclick="colorPicker(3, 'button-yellow')"></div>
			<div class="btn button-orange" onclick="colorPicker(3, 'button-orange')"></div>
			<div class="btn button-deeporange" onclick="colorPicker(3, 'button-deeporange')"></div>
			<div class="btn button-bluegrey" onclick="colorPicker(3, 'button-bluegrey')"></div>
			<div class="btn button-light" onclick="colorPicker(3, 'button-light')"></div>
			<div class="btn button-dark" onclick="colorPicker(3, 'button-dark')"></div>
		</div>
	</div>

	<div class="inputBox">
		<div class="title">
			<?php echo traducir(25,$_COOKIE['idioma'])?>
		</div>
		<input type="text" name="name" id="user-name" value="<?php echo $row_userData['name']; ?>"/>
	</div>
	<div class="inputBox">
		<div class="title">
			<?php echo traducir(27,$_COOKIE['idioma'])?>
		</div>
		<input name="car" id="pass-change-1" type="text" value="<?php echo $row_userData['car']; ?>"/>
	</div>
	<div class="inputBox">
		<div class="title">
			<?php echo traducir(28,$_COOKIE['idioma'])?>
		</div>
		<input name="profession" id="pass-change-1" type="text" value="<?php echo $row_userData['profession']; ?>"/>
	</div>
	<div class="inputBox">
		<div class="title">
			<?php echo traducir(29,$_COOKIE['idioma'])?>
		</div>
		<div class="select">
			<select name="country_id" id="countriesData" class="input-medium bfh-countries" data-country="<?php echo $row_userData['country_id']; ?>"></select>
			<input type="hidden" id="countryId" name="country"/>
			<select name="city_id" id="citiesData" class="input-medium bfh-states" data-country="countriesData" data-state="<?php echo $row_userData['city_id']; ?>"></select>
			<input type="hidden" id="cityId" name="city"/>
		</div>
	</div>
	<div class="inputBox">
		<div class="title">
			<?php echo traducir(30,$_COOKIE['idioma'])?>
		</div>
		<input name="birthday" id="birthdayDate" type="hidden" value="<?php $partes = explode(" - ",$row_userData['birthday']); echo $partes['0']." - ".monthsCaption($partes['1'])." - ".$partes['2'] ?>"/>
		<div class="select">
			<select name="birthday-day" id="birthdayDay" class="form-control-date">
				<?php $i = 1; do { ?>
				 	<option value='<?php echo $i ?>' <?php if($partes['0'] == $i) echo 'selected'?>><?php echo $i ?></option>
				<?php $i++; } while ($i <= 31); ?>
			</select>

			<select name="birthday-month" id="birthdayMonth" class="form-control-date">
				<?php $i = 0; do { ?>
				 	<option value='<?php echo $i ?>' <?php if($partes['1'] == $i) echo 'selected'?>><?php echo traducir(55 + $i,$_COOKIE['idioma'])?></option>
				<?php $i++; } while ($i <= 11); ?>
			</select>

			<select name="birthday-year" id="birthdayYear" class="form-control-date">
				<?php $i = 1945; do { ?>
				 	<option value='<?php echo $i ?>' <?php if($partes['2'] == $i) echo 'selected'?>><?php echo $i ?></option>
				<?php $i++; } while ($i <= date("Y")); ?>
			</select>

		</div>
	</div>
	<div class="inputBox">
		<div class="title">
			<?php echo traducir(31,$_COOKIE['idioma'])?>
		</div>
		<div class="select">
			<select name="relationship" class="form-controler" style="cursor:pointer;">
				<option  value="1" <?php if($row_userData['relationship'] == '1') echo 'selected'?>>Single</option>
				<option  value="2" <?php if($row_userData['relationship'] == '2') echo 'selected'?>>In a relationship</option>
				<option  value="3" <?php if($row_userData['relationship'] == '3') echo 'selected'?>>Engaged</option>
				<option  value="4" <?php if($row_userData['relationship'] == '4') echo 'selected'?>>Married</option>
				<option  value="5" <?php if($row_userData['relationship'] == '5') echo 'selected'?>>It´s complicated</option>
				<option  value="6" <?php if($row_userData['relationship'] == '6') echo 'selected'?>>Widowed</option>
				<option  value="7" <?php if($row_userData['relationship'] == '7') echo 'selected'?>>Separated</option>
				<option  value="8" <?php if($row_userData['relationship'] == '8') echo 'selected'?>>Divorced</option>
				<option  value="9" <?php if($row_userData['relationship'] == '9') echo 'selected'?>>In a civil union</option>
				<option  value="10" <?php if($row_userData['relationship'] == '10') echo 'selected'?>>Waiting for a miracle</option>
				<option  value="11" <?php if($row_userData['relationship'] == '11') echo 'selected'?>>In a relationship with</option>
			</select>
		</div>
	</div>
	<div class="inputBox">
		<div class="title">
			<?php echo traducir(32,$_COOKIE['idioma'])?>
		</div>
		<input name="other" type="hidden" val="" id="aboutMeId"/>
		<div class="aboutMe" id="aboutMe" contenteditable="true">
			<?php echo $row_userData['other']; ?>
		</div>
	</div>

	<button type="submit" class="button buttonSave" onclick="saveAccount()">
		<?php include("images/svg/spinner.php");?>
		<?php echo traducir(9,$_COOKIE['idioma'])?>
	</button>
</form>

<form id="formTwo" onSubmit="return false"> <!-- Backgrounds -->
	<div class="avatarBox" id="backgroundBoxOne">
		<div class="background" onclick="openModalBackground(1, 1)">
			<?php include("images/svg/photos.php"); ?>
			<img src="<?php echo $row_userData['avatar_bg1']; ?>" alt="">
			<input type="hidden" name="avatar_bg1" value="<?php echo $row_userData['avatar_bg1']; ?>">
		</div>
	</div>

	<div class="avatarBox" id="backgroundBoxTwo">
		<div class="background" onclick="openModalBackground(1, 2)">
			<?php include("images/svg/photos.php"); ?>
			<img src="<?php echo $row_userData['avatar_bg2']; ?>" alt="">
			<input type="hidden" name="avatar_bg2" value="<?php echo $row_userData['avatar_bg2']; ?>">
		</div>
	</div>

	<div class="avatarBox" id="backgroundBoxThree">
		<div class="background" onclick="openModalBackground(1, 3)">
			<?php include("images/svg/photos.php"); ?>
			<img src="<?php echo $row_userData['avatar_bg3']; ?>" alt="">
			<input type="hidden" name="avatar_bg3" value="<?php echo $row_userData['avatar_bg3']; ?>">
		</div>
	</div>

	<button type="submit" class="button buttonSave" onclick="saveBackgrounds()">
		<?php include("images/svg/spinner.php");?>
		<?php echo traducir(9,$_COOKIE['idioma'])?>
	</button>
</form>

<form id="formThree" onSubmit="return false"> <!-- Language -->
	<div class="languageBox">
		<?php do{ ?>
			<a onClick="setLanguage('<?php echo $row_languages['pais']?>');"
				<?php if ($_COOKIE['idioma'] == $row_languages['pais']){?>
				class="selected"
			<?php }?>>
				<?php if ($_COOKIE['idioma'] == $row_languages['pais']){?>
			    	<?php include("images/svg/check.php"); ?>
			    <?php } ?>
			    <?php echo ucwords($row_languages['pais'])?>
			</a>
		<?php } while ($row_languages = mysql_fetch_assoc($languages)); ?>
	</div>
</form>

<form id="formFour" onSubmit="return false"> <!-- Password -->
	<div class="inputBox">
		<div class="title">
			<?php echo traducir(6,$_COOKIE['idioma'])?>
		</div>
		<input type="password" name="currentPassword" onkeyup="changePassword(1, this.value)"/>
		<div class="status" id="currentPassword">
			<span class="done">
				<?php include("images/svg/check.php");?>
			</span>
			<span class="bad">
				<?php include("images/svg/close.php");?>
			</span>
			<span class="loader">
				<?php include("images/svg/spinner.php");?>
			</span>
		</div>
	</div>
	<div class="inputBox">
		<div class="title">
			<?php echo traducir(7,$_COOKIE['idioma'])?>
		</div>
		<input type="password" name="newPassword" onkeyup="changePassword(2, this.value, confirmPassword.value)"/>
	</div>
	<div class="inputBox">
		<div class="title">
			<?php echo traducir(8,$_COOKIE['idioma'])?>
		</div>
		<input type="password" name="confirmPassword" onkeyup="changePassword(3, newPassword.value, this.value)"/>
		<div class="status" id="confirmPassword">
			<span class="done">
				<?php include("images/svg/check.php");?>
			</span>
			<span class="bad">
				<?php include("images/svg/close.php");?>
			</span>
			<span class="loader">
				<?php include("images/svg/spinner.php");?>
			</span>
		</div>
	</div>

	<button type="submit" class="button buttonSave" onclick="changePassword(4, currentPassword.value, newPassword.value, confirmPassword.value, '<?php echo $row_userData['id']; ?>')">
		<?php include("images/svg/spinner.php");?>
		<?php echo traducir(9,$_COOKIE['idioma'])?>
	</button>
</form>

<script type="text/javascript">
	// MENU TABS
	var tabsInner = $(".papertabs li a");

	tabsInner.click(function() {
	    var content = this.hash.replace('/', '');
	    tabsInner.removeClass("active");
	    $(this).addClass("active");
	    $(".pageSettings").find('form').hide();
	    $(content).fadeIn(600);
	});

	// Transform color
	function rgb2hex(orig){
		var rgb = orig.replace(/\s/g,'').match(/^rgba?\((\d+),(\d+),(\d+)/i);
		return (rgb && rgb.length === 4) ?
		("0" + parseInt(rgb[1],10).toString(16)).slice(-2) +
		("0" + parseInt(rgb[2],10).toString(16)).slice(-2) +
		("0" + parseInt(rgb[3],10).toString(16)).slice(-2) : orig;
	}

	// Color picker
	function colorPicker(type, value){
		var header = $('.headerUser'),
			inputPrimary = $('#colorBoxPrimary > .selector > input'),
			buttonLanguage = $('.languageBox .selected'),
			buttonPrimary = $('#buttonPrimary');

		if (type==1) { //Box One
			$('#colorBoxPrimary .box').toggleClass('showColorBox');
		}else if (type==2) {
			header.css('background', '#' + value);
			buttonPrimary.css('background', '#' + value);
		}else if (type==3) {
			var color = $('.'+ value).css("background-color");
			header.css('background', color);
			buttonPrimary.css('background', color);
			var colorHex = rgb2hex(color);
			inputPrimary.val(colorHex);
		}
	}

	// Color theme
	function colorTheme(type){
		console.log('T:', type);
		
		if (type=='light') {
			$('#darkThemeColor').hide();
			$('#lightThemeColor').show();
		}else if (type=='dark') {
			$('#lightThemeColor').hide();
			$('#darkThemeColor').show();
		}
	}
	
	// Birth form-control-date
	$(function () {
		months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dec'];
		
		var $ld = $('select[name=birthday-day]');
		var $lm = $('select[name=birthday-month]');
		var $ly = $('select[name=birthday-year]');
		
		function daysInMonth(month, year) {
			return new Date(year, month, 0).getDate();
		}
		function adjustDates(selMonthDates, $sel) {
			var $options = $sel.find('option');
			var dates = $options.length;
			if (dates > selMonthDates) { 
				$options.slice(selMonthDates).remove();
			} else { 
				var dateOptions = [];
				for (var date = dates + 1; date <= selMonthDates; date++) {
					dateOptions.push('<option value="' + date + '">' + date + '</option>');
				}
				$sel.append(dateOptions.join(''));
			}
		}
		function resetDates() {
			$lm.val(function (i, v) {
				return (v == '') ? '1' : v;
			});
			$ly.val(function (i, v) {
				return (v == '') ? '2013' : v;
			});
		}
		var dateFormat = function (day, month, year) {
			resetDates();
			var selMonthDates = daysInMonth((parseInt($lm.val(), 10) + 1), $ly.val());
			adjustDates(selMonthDates, $ld);
			if (day > selMonthDates) {
				day = selMonthDates;
				$ld.val(day); //update selection
			}
			var selectedDate = new Date(year, month, day);
			var nextDay = new Date(selectedDate.getTime() + 86400000);
			var tmpArr = [];
			for (var yrs = parseInt(nextDay.getFullYear(), 10); yrs <= 2020; yrs++) {
				tmpArr.push('<option value="' + yrs + '">' + yrs + '</option>');
			}
			// $sy.empty().append(tmpArr.join('')); 
			// $sm.val(nextDay.getMonth());
			selMonthDates = daysInMonth(parseInt(nextDay.getMonth(), 10) + 1, nextDay.getFullYear());
			// adjustDates(selMonthDates, $sd);
			// $sd.val(nextDay.getDate());
			$('#log').empty().append('Fecha: ' + selectedDate).append('<br>');
			$('#log').append('Siguiente: ' + nextDay);
		}

		$('.select select').on('change', function () {
			var ldia = $ld.val();
			var lmes = $lm.val();
			var lano = $ly.val();
			var ldias = dateFormat(ldia, lmes, lano);
		});
	});

	//···> ACCOUNT
	function saveAccount(){
		var nombre = $("#user-name").val();

		if (nombre=="" || nombre==null) {
			$('.buttonSave').css('background','#F44336');

			setTimeout(function() {
	            $('.buttonSave').css('background','#2196F3');
	        }, 900);
		} else {
			var countryId = $('#countriesData option:selected').text();
            $('#countryId').val(countryId);

            var cityId = $('#citiesData option:selected').text();
            $('#cityId').val(cityId);

            var aboutMeId = $('#aboutMe').html();
            $('#aboutMeId').val(aboutMeId);

            var day = $('#birthdayDay').val(),
            	month = $('#birthdayMonth').val(),
            	year = $('#birthdayYear').val();
			$('#birthdayDate').val(day + ' - ' + month + ' - ' + year);
            
            var getInputName = $('#formOne');
            $('.buttonSave svg').show();

			$.ajax({
				type: 'POST',
				url: url+'pages/user/settings/account/save.php',
				data: getInputName.serialize(),
				success: function(response) {
					if (response == true){
	                	setTimeout(function() {
	                		$('.buttonSave svg').hide();
	                		$('.actionMessage').addClass('showMessageSettings');
	                	}, 1200);

	                	setTimeout(function() {
	                		$('.actionMessage').removeClass('showMessageSettings');
	                	}, 5000);
					} else {
						$('.buttonSave svg').hide();
						$('.buttonSave').css('background','#F44336');

						setTimeout(function() {
				            $('.buttonSave').css('background','#2196F3');
				        }, 900);
					}
				}
			});
		}
	}

	//···> BACKGROUNDS
	function saveBackgrounds(){
		var getInputName = $('#formTwo');
		$('.buttonSave svg').show();

		$.ajax({
			type: 'POST',
			url: url + 'pages/user/settings/backgrounds/save.php',
			data: getInputName.serialize(),
			success: function(response) {
				if (response == true){
                	setTimeout(function() {
                		$('.buttonSave svg').hide();
                		$('.actionMessage').addClass('showMessageSettings');
                	}, 1200);

                	setTimeout(function() {
                		$('.actionMessage').removeClass('showMessageSettings');
                	}, 5000);
				} else {
					$('.buttonSave svg').hide();
					$('.buttonSave').css('background','#F44336');

					setTimeout(function() {
			            $('.buttonSave').css('background','#2196F3');
			        }, 900);
				}
			}
		});
	}

	//···> LANGUAGE
	function setLanguage(value){
		$.ajax({
			type: 'POST',
			url: url + 'pages/user/settings/language/set.php',
			data: 'language='+value,
			success: function(htmlres){
				location.reload();
			}
		});
	}

	//···> PASSWORD
	function changePassword(type, value1, value2, value3, idUser) {
		if (type==1) {
			var password = '<?php echo $row_userData['password']; ?>',
				done = $('#currentPassword .done'),
				bad = $('#currentPassword .bad'),
				loader = $('#currentPassword .loader');

		    loader.show();
		    if (loader.is(':visible')) {
		        done.hide();
		        bad.hide();
		    }

		    setTimeout(function() {
			    if (password == md5(value1)) {
					done.show();
		            bad.hide();
		            loader.hide();
				}else{
					done.hide();
		            bad.show();
		            loader.hide();
				}
			}, 1400);
		}else if (type==2 || type==3) {
			var done = $('#confirmPassword .done'),
			bad = $('#confirmPassword .bad'),
			loader = $('#confirmPassword .loader');

			loader.show();
		    if (loader.is(':visible')) {
		        done.hide();
		        bad.hide();
		    }

		    setTimeout(function() {
			    if (value1 == value2) {
					done.show();
		            bad.hide();
		            loader.hide();
				}else{
					done.hide();
		            bad.show();
		            loader.hide();
				}
			}, 1400);
		}else if (type==4) {
			var password = '<?php echo $row_userData['password']; ?>';

			if (password == md5(value1) && value2 == value3 && value2 != '') {
				$.ajax({
					type: 'POST',
					url: url + 'pages/user/settings/password/change.php',
					data: 'newPassword=' + value2 + '&idUser=' + idUser,
					success: function(response){ 
						$('.buttonSave svg').show();

						if (response == true){
		                	setTimeout(function() {
		                		$('.buttonSave svg').hide();
		                		$('.actionMessage').addClass('showMessageSettings');
		                	}, 1200);

		                	setTimeout(function() {
		                		$('.actionMessage').removeClass('showMessageSettings');
		                	}, 5000);
						} else {
							$('.buttonSave svg').hide();
							$('.buttonSave').css('background','#F44336');

							setTimeout(function() {
					            $('.buttonSave').css('background','#2196F3');
					        }, 900);
						}
					}
				});
			}else{
				$('.buttonSave').css('background','#F44336');

				setTimeout(function() {
		            $('.buttonSave').css('background','#2196F3');
		        }, 900);
			}
		}
	}
</script>