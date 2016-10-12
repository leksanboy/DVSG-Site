<?php require_once '../../../Connections/conexion.php';
    $userId 		= $_POST['userId'];
    $pageLocation 	= $_POST['pageLocation'];

    if ($pageLocation == 'user') {
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
	} else if ($pageLocation == 'news') {
		//Users news
		mysql_select_db($database_conexion, $conexion);
		$query_newsList = sprintf("
			SELECT * FROM z_news
				WHERE user IN (SELECT
					CASE
						WHEN f.receiver = $userId THEN f.sender
						WHEN f.sender = $userId THEN f.receiver
					END
					FROM z_friends f WHERE f.receiver = $userId OR f.sender = $userId) OR user=%s 
					ORDER BY id DESC LIMIT 100", $userId, "int");
		$newsList = mysql_query($query_newsList, $conexion) or die(mysql_error());
		$row_newsList = mysql_fetch_assoc($newsList);
		$totalRows_newsList = mysql_num_rows($newsList);

		// User songs
		mysql_select_db($database_conexion, $conexion);
		$query_audiosList = sprintf("
			SELECT * FROM z_news_files
				WHERE user IN (SELECT
					CASE
						WHEN f.receiver = $userId THEN f.sender
						WHEN f.sender = $userId THEN f.receiver
					END
					FROM z_friends f WHERE f.receiver = $userId OR f.sender = $userId) OR user=%s AND type=%s
					ORDER BY id DESC LIMIT 100",
		GetSQLValueString($userId, "int"),
	   	GetSQLValueString("audio", "text"));
		$audiosList = mysql_query($query_audiosList, $conexion) or die(mysql_error());
		$row_audiosList = mysql_fetch_assoc($audiosList);
		$totalRows_audiosList = mysql_num_rows($audiosList);
	}

	$contadorPhotos = - 1;
	$contadorAudios = - 1;
?>
<?php if ($totalRows_newsList != 0){ ?>
	<div id="playerBoxAudioCounter" style="display: none">0</div>
	<audio id="playerBoxAudio" preload controls src="" style="display: none"></audio>
	<ul class="newsListBox">
		<?php do {
			$postId = $row_newsList['id'];
			
			//Files
			mysql_select_db($database_conexion, $conexion);
	        $query_newsFiles = sprintf("SELECT * FROM z_news_files WHERE post=%s ORDER BY id DESC", $postId, "int");
	        $newsFiles = mysql_query($query_newsFiles, $conexion) or die(mysql_error());
	        $row_newsFiles = mysql_fetch_assoc($newsFiles);
	        $totalRows_newsFiles = mysql_num_rows($newsFiles);

	        // Likes
			mysql_select_db($database_conexion, $conexion);
			$query_GetLikes = sprintf ("SELECT * FROM z_news_likes WHERE post = %s",
				GetSQLValueString($postId, "int"));
			$GetLikes = mysql_query($query_GetLikes, $conexion) or die(mysql_error());
			$row_GetLikes = mysql_fetch_assoc($GetLikes);
			$totalRows_GetLikes = mysql_num_rows($GetLikes);
		?>
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
					<?php if ($row_newsList['user'] == $_SESSION['MM_Id']) { ?>
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
					<?php if ($row_newsList['content']) { ?>
						<div class="content">
							<?php echo $row_newsList['content'] ?>
						</div>
					<?php } ?>
						<?php
							//Videos count MAX=3
							mysql_data_seek( $newsFiles, 0 ); $counterVideo = 0;
							while($row = mysql_fetch_array( $newsFiles )){
								if ($row['type'] == 'video') { $counterVideo++; }
							}

							//Photos count MAX=9
							mysql_data_seek( $newsFiles, 0 ); $counterPhoto = 0;
							while($row = mysql_fetch_array( $newsFiles )){
								if ($row['type'] == 'photo') { $counterPhoto++; }
							}
						?>
						<ul <?php if($counterVideo == 1 && $counterPhoto == 1){ ?>
								class="videosListBox onevp"
							<?php } else { ?>
								class="videosListBox <?php echo 'vlb'.$counterVideo ?>"
							<?php } ?>
						>
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

						<ul <?php if($counterVideo == 1 && $counterPhoto == 1){ ?>
								class="photosListBox onevp"
							<?php } else if($counterVideo >= 1 && $counterPhoto > 1){ ?>
								class="photosListBox allvp"
							<?php } else { ?>
								class="photosListBox <?php echo 'plb'.$counterPhoto ?>"
							<?php } ?>
						>
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
											<div class="progress"></div>
										</li>
									<?php }
								}
							?>
						</ul>
					<?php mysql_free_result($newsFiles);?>
					<?php mysql_free_result($GetLikes); ?>
					<?php mysql_free_result($GetComments); ?>
				</div>

				<div class="foot">
					<div class="analytics">
						<div class='comments'>
							<?php include('../../../images/svg/comments.php'); ?>
							<span class='count'><?php echo countCommentsUserNews($postId); ?></span>
						</div>
						<div class='likes' <?php  if (isset($_SESSION['MM_Id'])) { ?> onClick='likeNews(<?php echo $postId ?>)' <?php } ?>>
							<span class='like'>
								<?php if (checkLikeUserNews($_SESSION['MM_Id'], $postId) == true ){ ?>
									<?php include('../../../images/svg/unlike.php'); ?>
								<?php } else {?>
									<?php include('../../../images/svg/like.php'); ?>
								<?php } ?>
							</span>
							<span class='count'><?php echo $totalRows_GetLikes ?></span>
						</div>
			    	</div>
				</div>

				<div class="commentsBox">
					<?php 
						// Comments
						mysql_select_db($database_conexion, $conexion);
						$query_GetComments = sprintf ("SELECT * FROM z_news_comments WHERE post = %s ORDER BY id DESC",
						GetSQLValueString($postId, "int"));
						$GetComments = mysql_query($query_GetComments, $conexion) or die(mysql_error());
						$row_GetComments = mysql_fetch_assoc($GetComments);
						$totalRows_GetComments = mysql_num_rows($GetComments);
						
						$_SESSION['moreCommentsNews'.$postId] = 0;
						if (isset($_SESSION['MM_Id'])) 
					{ ?>
					    <div class="newComment">
					        <form onsubmit="return false">
					            <textarea name="comment" id="comentario" class="inputBox" onkeyup="newCommentNews(1, this.value, <?php echo $postId ?>)" placeholder="Write a comment..."></textarea>
					            <input type="hidden" name="page" value="<?php echo $postId; ?>" />
					            <input type="button" style="display:none" onclick="newCommentNews(2, comment.value, <?php echo $postId ?>)" id="btn_comentario<?php echo $postId ?>">
					            <label for="btn_comentario<?php echo $postId ?>">
					                <div class="button">
					                    <?php include("../../../images/svg/send.php");?>
					                </div>
					            </label>
					        </form>
					    </div>
					<?php } else {?>
					    <div class="registerToComment">to comment create an account</div>
					<?php }?>

					<div class="commentsList">
				    	<?php if ($row_GetComments != '') {?>
					        <?php do { $countComments[$postId]++; ?>
					            <div class="item" id="comment<?php echo $row_GetComments['id'] ?>">
					                <div class="avatar" onclick="userPage(<?php echo $row_GetComments['user'] ?>)">
					                    <img src="<?php echo userAvatar($row_GetComments["user"]); ?>" width="28px" height="28px" style="border-radius: 50%"/>
					                </div>

					                <div class="name" onclick="userPage(<?php echo $row_GetComments['user'] ?>)">
					                    <?php echo userName($row_GetComments['user']); ?>
					                    <font size="-2"><?php echo timeAgo($row_GetComments['time']);?></font>
					                </div>

					                <?php if (isset ($_SESSION['MM_Id'])){ ?> 
					                    <?php  if (($row_GetComments['user'] == $_SESSION['MM_Id']) || (rango_admin ($_SESSION['MM_Id']) ==4)) {?>
											<div class="delete" onClick="deleteCommentNews(1, <?php echo $row_GetComments['id'] ?>, <?php echo $postId ?>)">
												<?php include("../../../images/svg/clear.php"); ?>
											</div>
											<div class="deleteBoxConfirmation" id="delete<?php echo $row_GetComments['id'] ?>">
												<div class="text">Delete this comment?</div>
												<div class="buttons">
													<button onClick="deleteCommentNews(1, <?php echo $row_GetComments['id'] ?>, <?php echo $postId ?>)">NO</button>
													<button onClick="deleteCommentNews(2, <?php echo $row_GetComments['id'] ?>, <?php echo $postId ?>)">YES</button>
												</div>
											</div>
					                    <?php } ?>
					                <?php } ?>

					                <div class="content">
					                    <div class="inner">
					                        <?php echo $row_GetComments["comment"];?>
					                    </div>
					                </div>
					            </div>
					        <?php } while ($countComments[$postId] < 5 && $row_GetComments = mysql_fetch_assoc($GetComments)); ?>
					    <?php } ?>
					</div>

				    <?php if ($totalRows_GetComments > 5) { ?>
				    	<div class="loadMore" onclick="loadMorecommentsNews(<?php echo $postId ?>);"> + LOAD MORE</div>
				    <?php } ?>
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
	var userId 			= <?php echo $userId ?>,
		pageLocation 	= '<?php echo $pageLocation ?>';

		console.log('pageLocation', pageLocation);

	// ·····> Open photo slider
	function openPhotoPost(type, position, postId, photoId){
		if (type==1) { // Open
			$('.modalBox').toggleClass('modalDisplay');
			setTimeout(function() {
				$('.modalBox').toggleClass('showModal');
			}, 100);
			$('body').toggleClass('modalHidden');

			$.ajax({
                type: 'POST',
                url: '<?php echo $urlWeb ?>' + 'pages/user/user/slidePhotosPost.php',
                data: 'position=' + position + '&postId=' + postId + '&photoId=' + photoId + '&userId=' + userId + '&pageLocation='+pageLocation,
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

	// ·····> Open video to play
	function openVideoPost(type, videoId, fileName){
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
										<div class='action' onClick='playerAction(5)'>"+ closeIcon +"</div>\
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
								<button onClick='playerAction(5)'>CLOSE</button>\
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
		    	if (buffering > 1)
		    		buffering = 1;
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
		    	$('.videoBox .boxContent .title').fadeIn();
	    		$('.videoBox .boxContent .playPause').html(playIcon).fadeIn();
	    		$('.videoBox .boxContent .controlPanel').fadeIn();
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

	// ·····> Like
	function likeNews(id){
		var likeIcon 			= '<?php include('../../../images/svg/like.php'); ?>',
			unlikeIcon 			= '<?php include('../../../images/svg/unlike.php'); ?>',
			countLikesNews 		= $('#news'+ id +' .foot .analytics .likes .count'),
			likeIconNews		= $('#news'+ id +' .foot .analytics .likes .like'),
			countLikes 			= parseInt(countLikesNews.html());

		$.ajax({
	        type: 'POST',
	        url: '<?php echo $urlWeb ?>' + 'pages/user/user/like.php',
	        data: 'postId=' + id,
	        success: function(response){
	            if (response == 'like'){ // Like
	            	countLikes = countLikes +1;

	            	countLikesNews.html(countLikes);
	            	likeIconNews.html(likeIcon);
	        	} else { // Unlike
	            	countLikes = countLikes -1;

	            	countLikesNews.html(countLikes);
	            	likeIconNews.html(unlikeIcon);
	        	}
	        }
	    });
	}

	//·····> New comment
	function newCommentNews(type, value, id){
		var buttonSendCommentPhoto 	= $('#news'+id+' .commentsBox .newComment .button svg'),
			inputSendCommentPhoto 	= $('#news'+id+' .commentsBox .newComment .inputBox'),
			commentsList 			= $('#news'+id+' .commentsBox .commentsList'),
			countCommentsPhoto 		= $('#news'+id+' .foot .analytics .comments .count'),
			countComments 			= parseInt(countCommentsPhoto.html());

		console.log(type, value, id);

		if (type == 1) {
            if(value != ''){
                buttonSendCommentPhoto.css("fill","#09f");
            }else{
                buttonSendCommentPhoto.css("fill","#333");
            }
		} else if (type == 2) {
			if (value != '') {
                $.ajax({
                    type: "POST",
                    url: '<?php echo $urlWeb ?>' + 'pages/user/user/comments/new.php',
                    data: 'commentText=' + value + '&postId=' + id,
                    success: function(response) {
                        commentsList.prepend(response);
                        inputSendCommentPhoto.val('');
                        countCommentsPhoto.html(countComments + 1);
                    }
                });
            }
		}
	}

	//·····> Delete comment
	function deleteCommentNews(type, idComment, id){
		var countCommentsPhoto 	= $('#news'+id+' .foot .analytics .comments .count'),
			countComments 		= parseInt(countCommentsPhoto.html()),
			deleteComment		= $('#news'+id+' .commentsBox .commentsList .item #delete' + idComment),
			boxComment			= $('#news'+id+' .commentsBox .commentsList #comment' + idComment);

		if (type == 1) {
			deleteComment.toggle();
		}else if (type==2) {
			$.ajax({
				type: 'POST',
				url: '<?php echo $urlWeb ?>' + 'pages/user/user/comments/delete.php',
				data: 'id=' + id,
				success: function(response){
					boxComment.fadeOut(300);
					deleteComment.fadeOut(300);

					countCommentsPhoto.html(countComments - 1);
				}
			});
		}
	}

	//·····> Load more comment
	function loadMorecommentsNews(id){
		var commentsList 					= $('#news'+id+' .commentsBox .commentsList'),
			buttonLoadMoreCommentsPhoto 	= $('#news'+id+' .commentsBox .loadMore');

		$.ajax({
            type: "POST",
            url: '<?php echo $urlWeb ?>' + 'pages/user/user/comments/loadMore.php',
            data: 'cuantity=' + 5 + '&postId=' + id,
            success: function(response) {
            	if (response != '')
                	commentsList.append(response);
                else
                	buttonLoadMoreCommentsPhoto.hide();

            }
        });
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
				'background-image': "linear-gradient(#09f, #09f)"
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

    // ·····> Play truck default set
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