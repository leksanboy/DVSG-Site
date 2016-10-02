<?php require_once '../../../Connections/conexion.php';
    $userId = $_POST['userId'];

	//User news
	mysql_select_db($database_conexion, $conexion);
	$query_newsList = sprintf("SELECT * FROM z_news WHERE user=%s ORDER BY id DESC LIMIT 99", $userId, "int");
	$newsList = mysql_query($query_newsList, $conexion) or die(mysql_error());
	$row_newsList = mysql_fetch_assoc($newsList);
	$totalRows_newsList = mysql_num_rows($newsList);

	// User songs
	mysql_select_db($database_conexion, $conexion);
	$query_audiosList = sprintf("SELECT * FROM z_news_files WHERE user=%s AND type=%s ORDER by id DESC",
	GetSQLValueString($userId, "int"),
   	GetSQLValueString("audio", "text"));
	$audiosList = mysql_query($query_audiosList, $conexion) or die(mysql_error());
	$row_audiosList = mysql_fetch_assoc($audiosList);
	$totalRows_audiosList = mysql_num_rows($audiosList);

	$contadorPhotos = - 1;
	$contadorAudios = - 1;
?>
<?php if ($totalRows_newsList != 0){ ?>
	<div id="playerBoxAudioCounter" style="display: none">0</div>
	<audio id="playerBoxAudio" preload controls src="" style="display: none"></audio>
	<ul class="newsListBox">
		<?php do { ?>
			<li id="news<?php echo $row_newsList['id'] ?>">
				<div class="head">
					<div class="image" onclick="userPage(<?php echo $row_newsList['user']; ?>)">
						<img src="<?php echo userAvatar($row_newsList['user']); ?>"/>
					</div>
					<div class="name" onclick="userPage(<?php echo $row_newsList['user']; ?>)">
						<?php echo userName($row_newsList['user']); ?>
						<div class="date">
							<?php echo timeAgo($row_newsList['time']); ?>
						</div>
					</div>
					<?php if ($userId == $_SESSION['MM_Id']) { ?>
						<div class="delete" onClick="deleteNews(1, '<?php echo $row_newsList['id'] ?>')">
							<?php include("../../../images/svg/close.php"); ?>
						</div>
						<div class="deleteBoxConfirmation" id="delete<?php echo $row_newsList['id'] ?>">
							<div class="text">Delete this post?</div>
							<div class="buttons">
								<button onClick="deleteNews(1, <?php echo $row_newsList['id'] ?>)">NO</button>
								<button onClick="deleteNews(2, <?php echo $row_newsList['id'] ?>)">YES</button>
							</div>
						</div>
					<?php } ?>
				</div>

				<div class="body">
					<?php echo $row_newsList['content'] ?>

					<?php
						$postId = $row_newsList['id'];
						
						//Files
						mysql_select_db($database_conexion, $conexion);
				        $query_newsFiles = sprintf("SELECT * FROM z_news_files WHERE post=%s ORDER BY id DESC", $postId, "int");
				        $newsFiles = mysql_query($query_newsFiles, $conexion) or die(mysql_error());
				        $row_newsFiles = mysql_fetch_assoc($newsFiles);
				        $totalRows_newsFiles = mysql_num_rows($newsFiles);
					?>
						<ul class="videosListBox">
							<?php
								mysql_data_seek( $newsFiles, 0 );
								while($row = mysql_fetch_array( $newsFiles )){
									if ($row['type'] == 'video') { ?>
										<li>
											<div class="video" onclick="openVideoPost(1, <?php echo $row['file'] ?>, '<?php echo $row['name'] ?>')">
												<video>
													<source src="<?php echo $urlWeb ?>pages/user/videos/videos/<?php echo $row['name'] ?>"/>
												</video>
												<div class="play">
													<?php include('../../../images/svg/play.php'); ?>
												</div>
												<div class="duration">
													<?php echo $row['duration'] ?>
												</div>
											</div>
										</li>
									<?php }
								}
							?>
						</ul>

						<ul class="photosListBox">
							<?php
								mysql_data_seek( $newsFiles, 0 );
								while($row = mysql_fetch_array( $newsFiles )){
									if ($row['type'] == 'photo') { ?>
										<li <?php $contadorPhotos = $contadorPhotos + 1; ?>>
											<div class="image" onclick="openPhotoPost(1, <?php echo $contadorPhotos ?>, <?php echo $postId ?>, <?php echo $row['file'] ?>)" style="background-image: url(<?php echo $urlWeb ?>pages/user/photos/photos/<?php echo $row['name']?>)">
											</div>
										</li>
									<?php }
								}
							?>
						</ul>

						<ul class="songsListBox">
							<?php
								mysql_data_seek( $newsFiles, 0 );
								while($row = mysql_fetch_array( $newsFiles )){
									if ($row['type'] == 'audio') { ?>
										<li <?php $contadorAudios = $contadorAudios + 1; ?> class="song<?php echo $row['id'] ?>" id="song<?php echo $contadorAudios ?>">
											<div class="playPause" onClick="playTrack(<?php echo $contadorAudios ?>)">
												<div class="play" style="display:block;">
													<?php include '../../../images/svg/play.php'; ?>
												</div>
												<div class="pause" style="display:none;">
													<?php include '../../../images/svg/pause.php'; ?>
												</div>
											</div>
											<div class="text" onClick="playTrack(<?php echo $contadorAudios ?>)"><?php echo $row['title'] ?></div>
											<div class="duration"><?php echo $row['duration'] ?></div>

											<div class="progress">
												<!-- <input type="range" id="playerBoxAudioProgress" min="0" max="1000" value="0" onchange="setProgressBar(event.target)"/>
												<div class="buffer" id="playerBoxAudioBuffering"></div> -->
											</div>
										</li>
									<?php }
								}
							?>
						</ul>
					<?php mysql_free_result($newsFiles);?>
				</div>

				<div class="foot">
					<div class="analytics">
						<div class='comments'>
							<?php include('../../../images/svg/comments.php'); ?>
							<span class='count'>01</span>
						</div>
						<div class='likes' <?php  if (isset($_SESSION['MM_Id'])) { ?> onClick='like(<?php echo $photoId ?>)' <?php } ?>>
							<span class='like'>
								<?php if (checkLikeUserPhoto($_SESSION['MM_Id'], $photoId) == true ){ ?>
									<?php include('../../../images/svg/unlike.php'); ?>
								<?php } else {?>
									<?php include('../../../images/svg/like.php'); ?>
								<?php } ?>
							</span>
							<span class='count'>02</span>
						</div>
			    	</div>
				</div>
			</li>
		<?php } while ($row_newsList = mysql_fetch_assoc($newsList)); ?>
	</ul>
<?php } else { ?>
	<div class="noData">
		No news
	</div>
<?php } ?>
<script type="text/javascript">
	var userId = <?php echo $userId ?>;

	function openPhotoPost(type, position, postId, photoId){
		// console.log('type', type, 'position', position, 'postId', postId, 'photoId', photoId);
		if (type==1) { // Open
			$('.modalBox').toggleClass('modalDisplay');
			setTimeout(function() {
				$('.modalBox').toggleClass('showModal');
			}, 100);
			$('body').toggleClass('modalHidden');

			$.ajax({
                type: 'POST',
                url: '<?php echo $urlWeb ?>' + 'pages/user/user/slidePhotosPost.php',
                data: 'position=' + position + '&postId=' + postId + '&photoId=' + photoId + '&userId=' + userId,
                success: function(response){
                    $('.slidePhotosBox').html(response);
                }
            });

			var box = "<div class='slidePhotosBox'></div>\
						<div class='buttons'>\
							<button onClick='openPhoto(2)'>CLOSE</button>\
						</div>"

			$('.modalBox .box').html(box);
		} else if (type==2) { //Close
			$('.modalBox').toggleClass('modalDisplay');
			$('body').toggleClass('modalHidden');
		}
	}

	function openVideoPost(type, videoId, fileName){
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
		likeIcon 			= '<?php include('images/svg/like.php'); ?>',
		unlikeIcon 			= '<?php include('images/svg/unlike.php'); ?>';

		if (type==1) { // Open
			$('.modalBox').toggleClass('modalDisplay');
			setTimeout(function() {
				$('.modalBox').toggleClass('showModal');
			}, 100);
			$('body').toggleClass('modalHidden');

			$.ajax({
		        type: 'POST',
		        url: '<?php echo $urlWeb ?>' + 'pages/user/videos/setData.php',
		        data: 'videoId=' + videoId + '&userId=' + userId,
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
										<div class='action' onClick='openVideoPost(2)'>"+ closeIcon +"</div>\
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
								<button onClick='openVideoPost(2)'>CLOSE</button>\
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
</script>

<script type="text/javascript">
    var player = document.getElementById('playerBoxAudio');
    var idSong = parseInt($("#playerBoxAudioCounter").html());
    var idSongPlaying = idSong;

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

    // ·····> Auto set song progress
    function autoProgressBar(){
	    $('input[type=range]').on('input', function(e){
			var min = e.target.min,
				max = e.target.max,
				val = e.target.value;

			console.log('e.target', e.target);

			$(e.target).css({
				'backgroundSize': (val - min) * 100 / (max - min) + '% 100%',
				'background-image': "linear-gradient(#09f, #09f)"
			});
		}).trigger('input');
	}

    //////PLAY TRACK
	$('.playerBox .buttons .playPause .play').show();
    $('.playerBox .buttons .playPause .pause').hide();

    // ···> Get songs list
    var playing = false,
        mediaPath = '<?php echo $urlWeb ?>' + 'pages/user/music/songs/',
        tracks = [ 	<?php do { ?>
		            	{
		                    "name": "<?php echo $row_audiosList['title'] ?>",
		                    "file": "<?php echo $row_audiosList['name'] ?>",
		                    "duration": "<?php echo $row_audiosList['duration'] ?>"
		                },
		            <?php } while ($row_audiosList = mysql_fetch_assoc($audiosList)); ?>
		        ],
        trackCount = tracks.length,
        nowPlayingTitle = $('#playerBoxAudioPlayingTitle'),
        nowPlayingDuration = $('#playerBoxAudioPlayingDuration');

    // ·····> Load track once (first time)
    function loadTrack(id) {
        nowPlayingTitle.text(tracks[id].name);
        nowPlayingDuration.text(tracks[id].duration);
        audio.src = mediaPath + tracks[id].file;

        $('.songsListBox li').removeClass('active');
        $('.songsListBox li#song' + id).addClass('active');

        setTimeout(function() {
			player.pause();
		}, 100);
    };
    loadTrack(0);

    // ·····> Player buttons
    function playPausePrevNext(type){
    	songDuration();

    	if (type==1) { //Play·Pause
            $('.playerBox .buttons .playPause .play').toggle();
            $('.playerBox .buttons .playPause .pause').toggle();

            if (!player.paused) {
                player.pause();

                $('#song'+ idSong + ' .playPause .play').show();
                $('#song'+ idSong + ' .playPause .pause').hide();
            } else {
                player.play();

                $('#song'+ idSong + ' .playPause .play').hide();
                $('#song'+ idSong + ' .playPause .pause').show();
            }
    	} else if (type==2) { //Prev
            if ((idSong - 1) > -1) {
                idSong--;
                $("#playerBoxAudioCounter").html(idSong);
            } else {
				idSong = trackCount-1;
                $("#playerBoxAudioCounter").html(idSong);
            }

            for (x = 0; x < trackCount ; x++) {
                $('#song'+ x + ' .playPause .play').show();
            	$('#song'+ x + ' .playPause .pause').hide();
            	$('#song' + x + ' .progress').hide().html('');	
            }

            $('.songsListBox li').removeClass('active');
        	$('.songsListBox li#song' + idSong).addClass('active');
            $('#song'+ idSong + ' .playPause .play').hide();
            $('#song'+ idSong + ' .playPause .pause').show();
            $('.playerBox .buttons .playPause .play').hide();
        	$('.playerBox .buttons .playPause .pause').show();
        	$('.songsListBox li#song' + idSong + ' .progress').show().html('<input type="range" id="playerBoxAudioProgress" min="0" max="1000" value="0" onchange="setProgressBar(event.target)"/>');
        	autoProgressBar();

        	nowPlayingTitle.text(tracks[idSong].name);
        	nowPlayingDuration.text(tracks[idSong].duration);
        	audio.src = mediaPath + tracks[idSong].file;
        	idSongPlaying = idSong;
            player.play();
    	} else if (type==3) { //Next
    		if ((idSong+1) < trackCount) {
                idSong++;
                $("#playerBoxAudioCounter").html(idSong);
            } else {
                idSong = 0;
                $("#playerBoxAudioCounter").html(idSong);
            }

            for (x = 0; x < trackCount ; x++) {
                $('#song' + x + ' .playPause .play').show();
            	$('#song' + x + ' .playPause .pause').hide();
            	$('#song' + x + ' .progress').hide().html('');
            }

            $('.songsListBox li').removeClass('active');
        	$('.songsListBox li#song' + idSong).addClass('active');
            $('#song'+ idSong + ' .playPause .play').hide();
            $('#song'+ idSong + ' .playPause .pause').show();
            $('.playerBox .buttons .playPause .play').hide();
        	$('.playerBox .buttons .playPause .pause').show();
        	$('.songsListBox li#song' + idSong + ' .progress').show().html('<input type="range" id="playerBoxAudioProgress" min="0" max="1000" value="0" onchange="setProgressBar(event.target)"/>');
        	autoProgressBar();

        	nowPlayingTitle.text(tracks[idSong].name);
        	nowPlayingDuration.text(tracks[idSong].duration);
        	audio.src = mediaPath + tracks[idSong].file;
        	idSongPlaying = idSong;
            player.play();
    	}
    }

    // ·····> Play·pause track
    function playTrack(id) {
    	idSong = id;
    	songDuration();

        for (x = 0; x < trackCount ; x++) {
            $('#song' + x + ' .playPause .play').show();
        	$('#song' + x + ' .playPause .pause').hide();
        	$('#song' + x + ' .progress').hide().html('');
        }

        $('.songsListBox li').removeClass('active');
        $('.songsListBox li#song' + idSong).addClass('active');
        $('.songsListBox li#song' + idSong + ' .progress').show().html('<input type="range" id="playerBoxAudioProgress" min="0" max="1000" value="0" onchange="setProgressBar(event.target)"/>');
        autoProgressBar();

        if (idSongPlaying != idSong || idSongPlaying == undefined) {
        	idSongPlaying = idSong;

        	nowPlayingTitle.text(tracks[idSong].name);
        	nowPlayingDuration.text(tracks[idSong].duration);
        	audio.src = mediaPath + tracks[idSong].file;
        }

        if (!player.paused) {
            player.pause();

            $('#song'+ idSong + ' .playPause .play').show();
            $('#song'+ idSong + ' .playPause .pause').hide();
            $('.playerBox .buttons .playPause .play').show();
        	$('.playerBox .buttons .playPause .pause').hide();
        } else {
            player.play();

            $('#song'+ idSong + ' .playPause .play').hide();
            $('#song'+ idSong + ' .playPause .pause').show();
            $('.playerBox .buttons .playPause .play').hide();
        	$('.playerBox .buttons .playPause .pause').show();
        }
    };
</script>
<?php mysql_free_result($newsList); ?>
<?php mysql_free_result($audiosList); ?>