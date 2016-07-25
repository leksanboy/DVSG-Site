<div class="searchBox">
	<form>
		<input name="search" onKeyUp="searchButton(1, search.value)" placeholder="Search a song..."/>
		<div class="searchIcon">
			<?php include("images/svg/search.php");?>
		</div>
		<div class="loaderIcon">
			<?php include("images/svg/spinner.php");?>
		</div>
		<div class="clearIcon" onClick="searchButton(2)">
			<?php include("images/svg/clear.php");?>
		</div>
	</form>
</div>

<div class="searchBoxDataList"></div>
<div class="defaultDataList"></div>

<script type="text/javascript">
	//·····> SVG icons
	var uploadIcon = '<svg viewBox="0 0 48 48"><path d="M18 32h12V20h8L24 6 10 20h8zm-8 4h28v4H10z"/></svg>';
	var loadingIcon = '<svg viewBox="0 0 28 28"><g class="qp-circular-loader"><path class="qp-circular-loader-path" fill="none" d="M 14,1.5 A 12.5,12.5 0 1 1 1.5,14" stroke-linecap="round" /></g></svg>';
	
	//·····> Get id element
	function getFile(el){
		return document.getElementById(el);
	}

	//·····> Add new song
	function uploadSong(type, event){
		if (type==1) { // Open
			$('.messageModalWindow').toggleClass('modalDisplay');
			setTimeout(function() {
				$('.messageModalWindow').toggleClass('showModal');
			}, 100);
			$('body').toggleClass('modalHidden');

			var box = "<div class='head'>\
							UPLOAD SONG\
						</div>\
						<form enctype='multipart/form-data' method='post' onSubmit='return false' style='color:#000'>\
							<div class='upload'>\
								<label for='fileUpload'>\
									" + uploadIcon + " Upload a song\
								</label>\
								<input type='file' name='fileUpload' id='fileUpload' onChange='uploadSong(4, event)' accept='audio/*'>\
							</div>\
							<div class='progressBar'>\
								<div class='title' id='title'></div>\
								<div class='operations'>\
									<div class='status' id='status'></div>\
									<div class='loading' id='loading'>" + loadingIcon + "</div>\
								</div>\
								<progress id='progressBar' value='0' max='100'></progress>\
							</div>\
							<div class='buttons'>\
								<button onClick='uploadSong(3)'>UPLOAD</button>\
								<button onClick='uploadSong(2)'>CLOSE</button>\
							</div>\
						</form>"

			$('.messageModalWindow .box').html(box);
		} else if (type==2) { //Close
			$('.messageModalWindow').toggleClass('modalDisplay');
			setTimeout(function() {
				$('.messageModalWindow').toggleClass('showModal');
			}, 100);
			$('body').toggleClass('modalHidden');
		} else if (type==3) { //Save
			var file = getFile("fileUpload").files[0];
			var formdata = new FormData();
			var ajax = new XMLHttpRequest();

			formdata.append("fileUpload", file);
			ajax.upload.addEventListener("progress", progressHandler, false);
			ajax.addEventListener("load", completeHandler, false);
			ajax.addEventListener("error", errorHandler, false);
			ajax.addEventListener("abort", abortHandler, false);
			ajax.open("POST", "pages/user/music/upload.php");
			ajax.send(formdata);
			// ajax.open("POST", "pages/user/music/upload.php?title="+titleSong);

		} else if (type==4) { //Get song data
			var file = event.currentTarget.files[0];
			$('.progressBar').show();

		    if (file.name == undefined || file.name == '') {
		    	document.getElementById("title").innerHTML = "Untitled";
		    }else{
		   		document.getElementById("title").innerHTML = file.name;
		    }
		}
	}

	//·····> Add new song uploading progress bar
	function progressHandler(event){
		/* https://www.developphp.com/video/JavaScript/File-
		Upload-Progress-Bar-Meter-Tutorial-Ajax-PHP */
		var percent = (event.loaded / event.total) * 100;
		getFile("progressBar").value = Math.round(percent);
		getFile("status").innerHTML = Math.round(percent)+"%";

		var percentRound = Math.round(percent);
		if (percentRound == '100') {
			getFile("loading").style.display="block";
		}else{
			getFile("loading").style.display="none";
		}
	}
	function completeHandler(event){
		getFile("status").innerHTML = event.target.responseText;
		getFile("loading").style.display="none";
	}
	function errorHandler(event){
		getFile("status").innerHTML = "Failed";
	}
	function abortHandler(event){
		getFile("status").innerHTML = "Aborted";
	}

	//·····> Add song
	function addSong(id){
		$.ajax({
			type: 'POST',
			url: '<?php echo $urlWeb ?>' + 'pages/user/music/add.php',
			data: 'id=' + id,
			success: function(response){
				$('.songSearch'+id + ' .actions .add').hide();
				$('.songSearch'+id + ' .actions .added').show();
			}
		});
	}

	//·····> Delete song
	function deleteSong(type, id){
		if (type==1) {
			$('#delete'+id).toggle();
		}else if (type==2) {
			$.ajax({
				type: 'POST',
				url: '<?php echo $urlWeb ?>' + 'pages/user/music/delete.php',
				data: 'id=' + id,
				success: function(response){
					$('.song'+id).fadeOut(300);
					$('#delete'+id).fadeOut(300);
				}
			});
		}
	}

	//·····> Search
	function searchButton(type, value){
		if (type==1 && value.trim().length > 0) { // Search
			$('.searchBox .searchIcon').hide();
			$('.searchBox .loaderIcon').show();

			$.ajax({
				type: 'POST',
				url: '<?php echo $urlWeb ?>' + 'pages/user/music/search.php',
				data: 'titleValue=' + value,
				success: function(response) {
					setTimeout(function() {
						$('.searchBox .searchIcon').show();
						$('.searchBox .loaderIcon').hide();

						$('.defaultDataList').hide();
						$('.searchBoxDataList').show();
						$('.searchBoxDataList').html(response);
					}, 600);
				}
			});
		} else if (type==2 || value.trim().length == 0) { // Reset
			$('.searchBox .searchIcon').hide();
			$('.searchBox .loaderIcon').show();
			$('.searchBox form input').val('');
			$('.searchBoxDataList').hide();

			setTimeout(function() {
				$('.searchBox .searchIcon').show();
				$('.searchBox .loaderIcon').hide();

				$('.defaultDataList').show();
				defaultLoad();
			}, 600);
		}
	}

	//·····> Default
	function defaultLoad(){
		$.ajax({
			type: 'GET',
			url: '<?php echo $urlWeb ?>' + 'pages/user/music/default.php',
			success: function(response) {
				$('.defaultDataList').show();
				$('.defaultDataList').html(response);
				$('.searchBoxDataList').hide();
			}
		});
	}
	defaultLoad();
</script>
<script type="text/javascript">
    var player = document.getElementById('playerBoxAudio');
    var idSong = parseInt($("#playerBoxAudioCounter").html());

    // ·····> Auto playing
    var audio = $('#playerBoxAudio')
    .bind('play', function () {
        playing = true; // Play
    })
    .bind('pause', function () {
        playing = false; // Pause
    })
    .bind('ended', function () {
    	playPausePrevNext(3); // Next (auto)
    }).get(0);

    // ·····> Song buffering
    player.addEventListener('progress', function() {
    	var buffering = player.buffered.length;
    	$('#playerBoxAudioBuffering').width(buffering * 100 +'%');
    });

    // ·····> Song time duration
    player.addEventListener('timeupdate', function() {
		var duration =  ((player.currentTime / player.duration) * 1000);

		if (player.duration > 0) {
			$('#playerBoxAudioProgress').val(duration);

			$('#playerBoxAudioProgress').css({
				'backgroundSize': (duration / 10) + '% 100%',
				'background-image': "linear-gradient(#<?php echo $row_userData['secondary_color'];?>, #<?php echo $row_userData['secondary_color'];?>)"
			});
		}
    });

    // ·····> Song duration
    function songDuration() {
        setInterval(function(){
	        var current = player.currentTime,
	        	total = player.duration,
	        	duration = Math.round(total-current);

	        countDown(duration);
	    }, 1000);

	    var countDown = function(duration){
	    	var time = formatTime(duration);
	    	$('#playerBoxAudioPlayingDuration').html(time);
	    };

		var formatTime = function(time){
			var duration = time,
				hours = Math.floor(duration / 3600),
				minutes = Math.floor((duration % 3600) / 60),
				seconds = Math.floor(duration % 60),
				time = [];

			if (hours) {
				time.push(hours)
			}

			time.push(((hours ? "0" : "") + minutes).substr(-2));
			time.push(("0" + seconds).substr(-2));
			return time.join(":");
		};
    }

    // ·····> Set song progress
    function setProgressBar(target) {
    	var min = target.min,
		    max = target.max,
		    val = target.value;

    	var duration = Math.round(val / 1000 * player.duration);
    	player.currentTime = duration;
    }
    $('input[type=range]').on('input', function(e){
		var min = e.target.min,
			max = e.target.max,
			val = e.target.value;

		$(e.target).css({
			'backgroundSize': (val - min) * 100 / (max - min) + '% 100%',
			'background-image': "linear-gradient(#<?php echo $row_userData['secondary_color'];?>, #<?php echo $row_userData['secondary_color'];?>)"
		});
	}).trigger('input');
</script>