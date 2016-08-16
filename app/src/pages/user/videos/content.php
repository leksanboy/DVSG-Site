<div class="searchBox">
	<form>
		<input name="search" onKeyUp="searchButton(1, search.value)" placeholder="Search a video..."/>
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
	var uploadIcon 	= '<svg viewBox="0 0 48 48"><path d="M18 32h12V20h8L24 6 10 20h8zm-8 4h28v4H10z"/></svg>';
	var arrowUpIcon = '<svg viewBox="0 0 24 24"><path d="M0 0h24v24H0V0z" fill="none"/><path d="M4 12l1.41 1.41L11 7.83V20h2V7.83l5.58 5.59L20 12l-8-8-8 8z"/></svg>';
	var progressIcon = '<svg><circle fill="none"/></svg>';
	
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
							UPLOAD VIDEOS\
						</div>\
						<form enctype='multipart/form-data' method='post' onSubmit='return false'>\
							<div class='upload'>\
								<label for='fileUpload'>\
									" + uploadIcon + " Upload\
								</label>\
								<input type='file' name='fileUpload[]' multiple id='fileUpload' onChange='uploadFile(4, event)' accept='video/*,.3gp,.avi,.flv,.m4v,.mkv,.mp4,.mpeg,.mpg,.m2ts,.mts,.mov,.ogv,.rm,.rmvb,.ts,.vob,.webm,.wmv'>\
							</div>\
							<div class='filesBox'></div>\
							<div class='buttons'>\
								<button onClick='uploadFile(3)'>UPLOAD</button>\
								<button onClick='uploadFile(2)'>CLOSE</button>\
							</div>\
						</form>"

			$('.modalBox .box').html(box);
		} else if (type==2) { //Close
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
				ajax.open("POST", "pages/user/videos/upload.php");
				ajax.send(formdata);
			}
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
			$('#fileStatus' + i + ' #status .progress svg circle').css('stroke-dashoffset',  0);
		}else{
			$('#fileStatus' + i + ' #status .percentage').html(percent + '%');
			$('#fileStatus' + i + ' #status .progress svg circle').css('stroke-dashoffset',  107 - percent);
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

	//·····> Add video
	function addVideo(id){
		$.ajax({
			type: 'POST',
			url: '<?php echo $urlWeb ?>' + 'pages/user/videos/add.php',
			data: 'id=' + id,
			success: function(response){
				$('.songSearch'+id + ' .actions .add').hide();
				$('.songSearch'+id + ' .actions .added').show();
			}
		});
	}

	//·····> Delete video
	function deleteVideo(type, id){
		if (type==1) {
			$('#delete'+id).toggle();
		}else if (type==2) {
			$.ajax({
				type: 'POST',
				url: '<?php echo $urlWeb ?>' + 'pages/user/videos/delete.php',
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
				url: '<?php echo $urlWeb ?>' + 'pages/user/videos/search.php',
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
			url: '<?php echo $urlWeb ?>' + 'pages/user/videos/default.php',
			success: function(response) {
				$('.defaultDataList').show();
				$('.defaultDataList').html(response);
				$('.searchBoxDataList').hide();
			}
		});
	}
	defaultLoad();

	//·····> Open video
	var videoPlayer;
	function openVideo(type, fileName){
		if (type==1) { // Open
			$('.modalBox').toggleClass('modalDisplay');
			setTimeout(function() {
				$('.modalBox').toggleClass('showModal');
			}, 100);
			$('body').toggleClass('modalHidden');

			var box = "<form onSubmit='return false'>\
							<div class='videoBox'>\
								<video id='video_player' controls preload='auto'>\
									<source src='pages/user/videos/videos/" + fileName + "'>\
								</video>\
								<div class='title'>Title of my video '+Add'-'xClose'</div>\
								<div class='playPause' onClick='playerAction(1)'></div>\
								<div class='controlPanel'>\
									Time Progress fullScreen Volume\
									<div class='fullScreen' onClick='playerAction(2)'>\
										[·]\
									</div>\
								</div>\
							</div>\
							<div class='buttons'>\
								<button onClick='openVideo(2)'>CLOSE</button>\
							</div>\
						</form>"

			$('.modalBox .box').html(box);

			videoPlayer = $('#video_player'); //declarate video player
		    videoPlayer[0].removeAttribute("controls"); //remove default control when JS loaded
		} else if (type==2) { //Close
			$('.modalBox').toggleClass('modalDisplay');
			$('body').toggleClass('modalHidden');
		}
	}

	//·····> Video player
	var playPauseButton = $('.videoBox .playPause');
    function playerAction(type){
    	if (type == 1) { //playPause
    		if (!videoPlayer[0].paused)
    			videoPlayer[0].pause();
    		else
    			videoPlayer[0].play();

    		playPauseButton.toggle();
    	} else if (type == 2) { //fullScreen
    		if ($.isFunction(videoPlayer[0].webkitEnterFullscreen))
                videoPlayer[0].webkitEnterFullscreen();
            else if ($.isFunction(videoPlayer[0].mozRequestFullScreen))
                videoPlayer[0].mozRequestFullScreen();
            else
                alert('Your browsers doesn\'t support fullscreen');
    	} else if (type == 3) { //Sound mute
    		//TODO: MUTE/SOUND
    	} else if (type == 4) {
    		//TODO: Show/Hide controls & title like youtube
			// videoPlayer.on('click', function () {
			// 	console.log('CLICK-VP');
			// });
    	}
    }
</script>