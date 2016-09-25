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
	var userId = <?php echo $userPageId ?>;
	console.log('userId', userId);

	//·····> SVG icons
	var uploadIcon 			= '<?php include('images/svg/upload.php'); ?>',
		arrowUpIcon 		= '<?php include('images/svg/arrow-up.php'); ?>',
		progressIcon 		= '<?php include('images/svg/progress.php'); ?>';

	//·····> Get id element
	function getFile(el){
		return document.getElementById(el);
	}

	//·····> Add new song
	var filesArray = [];
	function uploadFile(type, event){
		if (type==1) { // Open
			$('.modalBox').toggleClass('modalDisplay');
			setTimeout(function() {
				$('.modalBox').toggleClass('showModal');
			}, 100);
			$('body').toggleClass('modalHidden');

			var box = "<div class='head'>\
							UPLOAD SONGS\
						</div>\
						<form enctype='multipart/form-data' method='post' onSubmit='return false'>\
							<div class='upload'>\
								<label for='fileUpload'>\
									" + uploadIcon + " Upload\
								</label>\
								<input type='file' name='fileUpload[]' multiple id='fileUpload' onChange='uploadFile(4, event)' accept='audio/mpeg'>\
							</div>\
							<div class='filesBox'></div>\
							<div class='buttons'>\
								<button onClick='uploadFile(3, this)'>UPLOAD</button>\
								<button onClick='uploadFile(2)'>CLOSE</button>\
							</div>\
						</form>"

			$('.modalBox .box').html(box);
		} else if (type==2) { //Close
			if (filesArray.length > 0)
				defaultLoad();

			$('.modalBox').toggleClass('modalDisplay');
			$('body').toggleClass('modalHidden');

			setTimeout(function() {
				$('.modalBox').toggleClass('showModal');
				$(".filesBox").html('');
				filesArray = [];
			}, 100);
		} else if (type==3) { //Save
			var i = 0;

			for (; i < filesArray.length; i++) {
				var file = filesArray[i],
					formdata = new FormData(),
					ajax = new XMLHttpRequest();

				ajaxCall(file, formdata, ajax, i);
			}

			function ajaxCall(file, formdata, ajax, i){
				formdata.append("fileUpload", file);
				ajax.upload.addEventListener("progress", function(evt){ progressHandler(evt, i) }, false);
				ajax.addEventListener("load", function(evt){ completeHandler(evt, i) }, false);
				ajax.addEventListener("error", function(evt){ errorHandler(evt, i) }, false);
				ajax.addEventListener("abort", function(evt){ abortHandler(evt, i) }, false);
				ajax.open("POST", "pages/user/music/upload.php");
				ajax.send(formdata);
			}

			if (filesArray.length > 0) // Disable button after upload
				$(event).attr("disabled", "disabled");
		} else if (type==4) { //Get song data
			var	file,
				i = 0;

			for (; i < event.currentTarget.files.length; i++) {
      			file = event.currentTarget.files[i];
				filesArray.push(file);

	      		var title = "\
			      		<div class='fileStatus' id='fileStatus"+ i +"'>\
							<div class='title' id='title'>" + file.name + "</div>\
							<div class='operations'>\
								<div class='status' id='status'>\
									<div class='progress'>" + progressIcon + "</div>\
									<div class='percentage'>0%</div>\
									<div class='loading'>" + arrowUpIcon + "</div>\
									<div class='result'></div>\
								</div>\
							</div>\
						</div>";

	      		$(".filesBox").append(title);
      		}
		}
	}

	//·····> Add new song uploading progress bar
	/* https://www.developphp.com/video/JavaScript/File-
	Upload-Progress-Bar-Meter-Tutorial-Ajax-PHP */
	function progressHandler(event, i){
		var percent = (event.loaded / event.total) * 100;
		percent = Math.round(percent);

		if (percent == 100){
			$('#fileStatus' + i + ' .operations #status .loading').show();
			$('#fileStatus' + i + ' .operations #status .percentage').hide();
			$('#fileStatus' + i + ' #status .progress svg circle').css('stroke-dashoffset', 0);
		}else{
			$('#fileStatus' + i + ' #status .percentage').html(percent + '%');
			$('#fileStatus' + i + ' #status .progress svg circle').css('stroke-dashoffset', 100 - percent*93/100);
		}
	}
	function completeHandler(event, i){
		$('#fileStatus' + i + ' .operations #status .loading').hide();
		$('#fileStatus' + i + ' .operations #status .result').html(event.target.responseText);
	}
	function errorHandler(event, i){
		$('#fileStatus' + i + ' .operations #status .result').html('Failed');
	}
	function abortHandler(event, i){
		$('#fileStatus' + i + ' .operations #status .result').html('Aborted');
	}

	//·····> Default
	function defaultLoad(){
		$.ajax({
			type: 'POST',
			url: '<?php echo $urlWeb ?>' + 'pages/user/music/default.php',
			data: 'userId=' + userId,
			success: function(response) {
				$('.defaultDataList').show();
				$('.defaultDataList').html(response);
				$('.searchBoxDataList').hide();
			}
		});
	}
	defaultLoad();

	//·····> Add song form another User
	function addSong(type, id, songId){
		if (type==1) {
			$.ajax({
				type: 'POST',
				url: '<?php echo $urlWeb ?>' + 'pages/user/music/add.php',
				data: 'type=add' + '&songId=' + songId,
				success: function(response){
					$('.song'+ id +' .actions .add').hide();
					$('.song'+ id +' .actions .added').show();
					$('.song'+ id +' .actions .added').attr("onclick","addSong(2, "+ id +", "+ response +");");
				}
			});
		} else if (type==2) {
			$.ajax({
				type: 'POST',
				url: '<?php echo $urlWeb ?>' + 'pages/user/music/add.php',
				data: 'type=delete' + '&songId=' + songId,
				success: function(response){
					$('.song'+ id + ' .actions .add').show();
					$('.song'+ id + ' .actions .added').hide();
				}
			});
		}
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

	//·····> Add song form search
	function addSongSearch(type, id, songId){
		if (type==1) {
			$.ajax({
				type: 'POST',
				url: '<?php echo $urlWeb ?>' + 'pages/user/music/add.php',
				data: 'type=add' + '&songId=' + id,
				success: function(response){
					$('.songSearch'+ id +' .actions .add').hide();
					$('.songSearch'+ id +' .actions .added').show();
					$('.songSearch'+ id +' .actions .added').attr("onclick","addSongSearch(2, "+ id +", "+ response +");");
				}
			});
		} else if (type==2) {
			$.ajax({
				type: 'POST',
				url: '<?php echo $urlWeb ?>' + 'pages/user/music/add.php',
				data: 'type=delete' + '&songId=' + songId,
				success: function(response){
					$('.songSearch'+ id + ' .actions .add').show();
					$('.songSearch'+ id + ' .actions .added').hide();
				}
			});
		}
	}
</script>
<script type="text/javascript">
    var player = document.getElementById('playerBoxAudio');
    var idSong = parseInt($("#playerBoxAudioCounter").html());
    var idSongPlaying;

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
    	idSongPlaying = idSong;
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