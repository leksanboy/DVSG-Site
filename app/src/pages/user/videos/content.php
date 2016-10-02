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
	var userId = <?php echo $userPageId ?>;
	console.log('userId', userId);

	//·····> SVG icons
	var uploadIcon 			= '<?php include('images/svg/upload.php'); ?>',
		arrowUpIcon 		= '<?php include('images/svg/arrow-up.php'); ?>',
		progressIcon 		= '<?php include('images/svg/progress.php'); ?>',
		addIcon 			= '<?php include('images/svg/add.php'); ?>',
		closeIcon 			= '<?php include('images/svg/close.php'); ?>',
		playIcon 			= '<?php include('images/svg/play.php'); ?>',
		pauseIcon 			= '<?php include('images/svg/pause.php'); ?>',
		moreIcon 			= '<?php include('images/svg/dots.php'); ?>',
		fullscreenIcon 		= '<?php include('images/svg/fullscreen.php'); ?>',
		likeIcon 			= "<?php include('images/svg/like.php'); ?>",
		unlikeIcon 			= "<?php include('images/svg/unlike.php'); ?>";
	
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
				ajax.open("POST", "pages/user/videos/upload.php");
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
			url: '<?php echo $urlWeb ?>' + 'pages/user/videos/default.php',
			data: 'userId=' + userId,
			success: function(response) {
				$('.defaultDataList').show();
				$('.defaultDataList').html(response);
				$('.searchBoxDataList').hide();
			}
		});
	}
	defaultLoad();

	//·····> Add video
	function addVideo(type, id, videoId){
		if (type==1) {
			$.ajax({
				type: 'POST',
				url: '<?php echo $urlWeb ?>' + 'pages/user/videos/add.php',
				data: 'type=add' + '&videoId=' + videoId,
				success: function(response){
					$('.video'+ id + ' .actions .add').hide();
					$('.video'+ id + ' .actions .added').show();
					$('.video'+ id + ' .actions .added').attr("onclick","addVideo(2, "+id+", "+response+");");
				}
			});
		} else if (type==2) {
			$.ajax({
				type: 'POST',
				url: '<?php echo $urlWeb ?>' + 'pages/user/videos/add.php',
				data: 'type=delete' + '&videoId=' + videoId,
				success: function(response){
					$('.video'+ id + ' .actions .add').show();
					$('.video'+ id + ' .actions .added').hide();
				}
			});
		}
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
					$('.video'+id).fadeOut(300);
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

	//·····> Add video
	function addVideoSearch(type, id, videoId){
		if (type==1) {
			$.ajax({
				type: 'POST',
				url: '<?php echo $urlWeb ?>' + 'pages/user/videos/add.php',
				data: 'type=add' + '&videoId=' + id,
				success: function(response){
					$('.videoSearch'+ id + ' .actions .add').hide();
					$('.videoSearch'+ id + ' .actions .added').show();
					$('.videoSearch'+ id + ' .actions .added').attr("onclick","addVideoSearch(2, "+ id +", "+ response +");");
				}
			});
		} else if (type==2) {
			$.ajax({
				type: 'POST',
				url: '<?php echo $urlWeb ?>' + 'pages/user/videos/add.php',
				data: 'type=delete' + '&videoId=' + videoId,
				success: function(response){
					$('.videoSearch'+ id + ' .actions .add').show();
					$('.videoSearch'+ id + ' .actions .added').hide();
				}
			});
		}
	}

	//·····> Open video
	function openVideo(type, fileName, title, id){
		if (type==1) { // Open
			$('.modalBox').toggleClass('modalDisplay');
			setTimeout(function() {
				$('.modalBox').toggleClass('showModal');
			}, 100);
			$('body').toggleClass('modalHidden');

			$.ajax({
		        type: 'POST',
		        url: '<?php echo $urlWeb ?>' + 'pages/user/videos/setData.php',
		        data: 'videoId=' + id + '&userId=' + userId,
		        success: function(response){
		        	$('.videoBox .boxData').html(response);
		        }
		    });

			var box = "<form onSubmit='return false'>\
							<div class='videoBox'>\
								<div class='boxContent'>\
									<video id='video_player' controls preload='auto' onClick='playerAction(4)'>\
										<source src='pages/user/videos/videos/" + fileName + "'>\
									</video>\
									<div class='title'>\
										<div class='action' onClick='openVideo(2)'>"+ closeIcon +"</div>\
										<div class='action' onClick='playerAction(3)'>"+ moreIcon +"</div>\
									</div>\
									<div class='playPause' onClick='playerAction(1, this)'>\
										"+ playIcon +"\
									</div>\
									<div class='controlPanel'>\
										<div class='time'>\
											<div class='current'>00:00</div>\
											<div class='total'>00:00</div>\
										</div>\
										<div class='fullScreen' onClick='playerAction(2)'>"+ fullscreenIcon +"</div>\
									</div>\
									<div class='progress'>\
										<input type='range' id='playerBoxVideoProgress' min='0' max='1000' value='0' onchange='setProgressBar(event.target)'/>\
										<div class='buffer' id='playerBoxVideoBuffering'></div>\
									</div>\
								</div>\
								<div class='boxData'></div>\
							</div>\
							<div class='buttons'>\
								<button onClick='openVideo(2)'>CLOSE</button>\
							</div>\
						</form>"

			$('.modalBox .box').html(box);

			// ·····> declarate video player
			var videoPlayer = document.getElementById('video_player');
		    
			// ·····> remove default control when JS loaded
		    videoPlayer.removeAttribute("controls");

		    // ·····> video buffer
		    videoPlayer.addEventListener('progress', function() {
		    	var buffering = videoPlayer.buffered.length;
		    	$('#playerBoxVideoBuffering').width(buffering * 100 +'%');
		    });

		    // ·····> video time duration
		    videoPlayer.addEventListener('timeupdate', function() {
				var duration =  ((videoPlayer.currentTime / videoPlayer.duration) * 1000);

				if (videoPlayer.duration > 0) {
					$('#playerBoxVideoProgress').val(duration);

					$('#playerBoxVideoProgress').css({
						'backgroundSize': (duration / 10) + '% 100%',
						'background-image': "linear-gradient(#<?php echo $row_userData['secondary_color'];?>, #<?php echo $row_userData['secondary_color'];?>)"
					});
				}
		    });

		    // ·····> video end
		    videoPlayer.addEventListener('ended', function() {
		    	$('.videoBox .boxContent .title').fadeToggle();
	    		$('.videoBox .boxContent .playPause').html(playIcon).fadeToggle();
	    		$('.videoBox .boxContent .controlPanel').fadeToggle();
		    });

		    // ·····> video time current -/- total
			setInterval(function(){
				$('.videoBox .boxContent .controlPanel .time .total').text(formatTime(videoPlayer.duration));
				$('.videoBox .boxContent .controlPanel .time .current').text(formatTime(videoPlayer.currentTime));

				if(videoPlayer.duration == videoPlayer.currentTime)
					$('.videoBox .controlPanel .playPause').html(playIcon);
			}, 1000);
		} else if (type==2) { //Close
			$('.modalBox').toggleClass('modalDisplay');
			$('body').toggleClass('modalHidden');
		}
	}

	// //·····> Video player
 //    function playerAction(type, button){
 //    	if (type == 1) { //playPause
 //    		if (!videoPlayer.paused){
 //    			videoPlayer.pause();
 //    			$(button).html(playIcon);
 //    		} else {
 //    			videoPlayer.play();
 //    			$(button).html(pauseIcon);
 //    		}
 //    	} else if (type == 2) { //fullScreen
 //    		if ($.isFunction(videoPlayer.webkitEnterFullscreen))
 //                videoPlayer.webkitEnterFullscreen();
 //            else if ($.isFunction(videoPlayer.mozRequestFullScreen))
 //                videoPlayer.mozRequestFullScreen();
 //            else
 //                alert('Your browsers doesn\'t support fullscreen');
 //    	} else if (type == 3) { //More
 //    		alert('DVSG');
 //    	} else if (type == 4) { //Video click to show/Hide
 //    		$('.videoBox .player .playPause').fadeToggle();
 //    		$('.videoBox .player .title').fadeToggle();
 //    		$('.videoBox .player .controlPanel').fadeToggle();
 //    	} else if (type == 5) { //Sound mute
 //    		//TODO: MUTE/SOUND
 //    	} else if (type == 6) {
 //    		//TODO: Show/Hide controls & title like youtube
	// 		// videoPlayer.on('click', function () {
	// 		// 	console.log('CLICK-VP');
	// 		// });
 //    	}
 //    }

 //    // ·····> format time
 //    function formatTime(time){
	// 	var duration = time,
	// 		hours = Math.floor(duration / 3600),
	// 		minutes = Math.floor((duration % 3600) / 60),
	// 		seconds = Math.floor(duration % 60),
	// 		time = [];

	// 	if (hours) {
	// 		time.push(hours)
	// 	}

	// 	time.push(((hours ? "0" : "") + minutes).substr(-2));
	// 	time.push(("0" + seconds).substr(-2));
	// 	return time.join(":");
	// };

	// // ·····> Set video progress
 //    function setProgressBar(target) {
 //    	var min = target.min,
	// 	    max = target.max,
	// 	    val = target.value;

 //    	var duration = Math.round(val / 1000 * videoPlayer.duration);
 //    	videoPlayer.currentTime = duration;
 //    }
 //    $('input[type=range]').on('input', function(e){
	// 	var min = e.target.min,
	// 		max = e.target.max,
	// 		val = e.target.value;

	// 	$(e.target).css({
	// 		'backgroundSize': (val - min) * 100 / (max - min) + '% 100%',
	// 		'background-image': "linear-gradient(#<?php echo $row_userData['secondary_color'];?>, #<?php echo $row_userData['secondary_color'];?>)"
	// 	});
	// }).trigger('input');

	// //·····> Like photo
	// function like(id){
	// 	var countLikesVideo 	= $('.modalBox .box .videoBox .boxData .user .actions .analytics .likes .count'),
	// 		likeIconVideo		= $('.modalBox .box .videoBox .boxData .user .actions .analytics .likes .like'),
	// 		countLikes 			= parseInt(countLikesVideo.html());

	// 	$.ajax({
	//         type: 'POST',
	//         url: '<?php echo $urlWeb ?>' + 'pages/user/videos/like.php',
	//         data: 'videoId=' + id,
	//         success: function(response){
	//             if (response == 'like'){ // Like
	//             	countLikes = countLikes +1;

	//             	countLikesVideo.html(countLikes);
	//             	likeIconVideo.html(likeIcon);
	//         	} else { // Unlike
	//             	countLikes = countLikes -1;

	//             	countLikesVideo.html(countLikes);
	//             	likeIconVideo.html(unlikeIcon);
	//         	}
	//         }
	//     });
	// }

	// //·····> New comment
	// function newComment(type, value, id){
	// 	var buttonSendCommentVideo 	= $(".modalBox .box .videoBox .boxData .comments .newComment .button svg"),
	// 		inputSendCommentVideo 	= $(".modalBox .box .videoBox .boxData .comments .newComment .inputBox"),
	// 		commentsList 			= $('.modalBox .box .videoBox .boxData .comments .commentsList'),
	// 		countCommentsVideo 		= $('.modalBox .box .videoBox .boxData .user .actions .analytics .comments .count'),
	// 		countComments 			= parseInt(countCommentsVideo.html());

	// 	if (type == 1) {
 //            if(value != ''){
 //                buttonSendCommentVideo.css("fill","#09f");
 //            }else{
 //                buttonSendCommentVideo.css("fill","#333");
 //            }
	// 	} else if (type == 2) {
	// 		if (value != '') {
 //                $.ajax({
 //                    type: "POST",
 //                    url: '<?php echo $urlWeb ?>' + 'pages/user/videos/comments/new.php',
 //                    data: 'commentText=' + value + '&videoId=' + id,
 //                    success: function(response) {
 //                        commentsList.prepend(response);
 //                        inputSendCommentVideo.val('');
 //                        countCommentsVideo.html(countComments + 1);
 //                    }
 //                });
 //            }
	// 	}
	// }

	// //·····> Delete comment
	// function deleteComment(type, id){
	// 	var countCommentsVideo 	= $('.modalBox .box .videoBox .boxData .user .actions .analytics .comments .count'),
	// 		countComments 		= parseInt(countCommentsVideo.html()),
	// 		deleteComment		= $('.modalBox .box .videoBox .boxData .comments .commentsList .item #delete' + id),
	// 		boxComment			= $('.modalBox .box .videoBox .boxData .comments .commentsList #comment' + id);

	// 	if (type == 1) {
	// 		deleteComment.toggle();
	// 	}else if (type==2) {
	// 		$.ajax({
	// 			type: 'POST',
	// 			url: '<?php echo $urlWeb ?>' + 'pages/user/videos/comments/delete.php',
	// 			data: 'id=' + id,
	// 			success: function(response){
	// 				boxComment.fadeOut(300);
	// 				deleteComment.fadeOut(300);

	// 				countCommentsVideo.html(countComments - 1);
	// 			}
	// 		});
	// 	}
	// }

	// //·····> Load more comment
	// function loadMorecomments(id){
	// 	var commentsList 					= $('.modalBox .box .videoBox .boxData .comments .commentsList'),
	// 		buttonLoadMoreCommentsVideo 	= $('.modalBox .box .videoBox .boxData .comments .loadMore');

	// 	$.ajax({
 //            type: "POST",
 //            url: '<?php echo $urlWeb ?>' + 'pages/user/videos/comments/loadMore.php',
 //            data: 'cuantity=' + 10 + '&videoId=' + id,
 //            success: function(response) {
 //            	if (response != '')
 //                	commentsList.append(response);
 //                else
 //                	buttonLoadMoreCommentsVideo.hide();

 //            }
 //        });
	// }
</script>