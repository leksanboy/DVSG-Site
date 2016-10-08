<?php require_once('../../../Connections/conexion.php');
	$userId = $_POST['userId'];
	$videoId = $_POST['videoId'];
	$_SESSION['moreCommentsVideo'] = 0;

	// Update views
	$updateSQL = sprintf("UPDATE z_videos SET replays = replays+1 WHERE id = %s",
    GetSQLValueString($videoId, "int"));
    mysql_select_db($database_conexion, $conexion);
    $Result1 = mysql_query($updateSQL, $conexion) or die(mysql_error());

	// Likes user video
	mysql_select_db($database_conexion, $conexion);
	$query_GetLikes = sprintf ("SELECT * FROM z_videos_likes WHERE video = %s",
		GetSQLValueString($videoId, "int"));
	$GetLikes = mysql_query($query_GetLikes, $conexion) or die(mysql_error());
	$row_GetLikes = mysql_fetch_assoc($GetLikes);
	$totalRows_GetLikes = mysql_num_rows($GetLikes);

	// Comments user video
	mysql_select_db($database_conexion, $conexion);
	$query_GetComments = sprintf ("SELECT * FROM z_videos_comments WHERE video = %s ORDER BY date DESC",
		GetSQLValueString($videoId, "int"));
	$GetComments = mysql_query($query_GetComments, $conexion) or die(mysql_error());
	$row_GetComments = mysql_fetch_assoc($GetComments);
	$totalRows_GetComments = mysql_num_rows($GetComments);
?>
<div class="user">
	<div class="avatar" onclick="userPage(<?php echo $userId ?>)">
		<img src="<?php echo userAvatar($userId); ?>">
	</div>
	<div class="name" onclick="userPage(<?php echo $userId ?>)">
		<?php echo userName($userId); ?>
	</div>
	<div class="actions">
		<div class="date"><?php echo timeAgo(timeUserVideo($videoId)); ?></div>
    	<div class="analytics">
			<div class='comments'>
				<?php include('../../../images/svg/comments.php'); ?>
				<span class='count'><?php echo $totalRows_GetComments ?></span>
			</div>
			<div class='likes' <?php  if (isset($_SESSION['MM_Id'])) { ?> onClick='like(<?php echo $videoId ?>)' <?php } ?>>
				<span class='like'>
					<?php if (checkLikeUserVideo($_SESSION['MM_Id'], $videoId) == true ){ ?>
						<?php include('../../../images/svg/unlike.php'); ?>
					<?php } else {?>
						<?php include('../../../images/svg/like.php'); ?>
					<?php } ?>
				</span>
				<span class='count'><?php echo $totalRows_GetLikes ?></span>
			</div>
    	</div>
	</div>
</div>
<div class="comments">
	<?php  if (isset($_SESSION['MM_Id'])) {?>
	    <div class="newComment">
	        <form onsubmit="return false">
	            <textarea name="comment" id="comentario" class="inputBox" onkeyup="newComment(1, this.value, <?php echo $videoId ?>)" placeholder="Write a comment..."></textarea>
	            <input type="hidden" name="page" value="<?php echo $videoId; ?>" />
	            <input type="button" style="display:none" onclick="newComment(2, comment.value, <?php echo $videoId ?>)" id="btn_comentario">
	            <label for="btn_comentario">
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
	        <?php do { $countComments++; ?>
	            <div class="item" id="comment<?php echo $row_GetComments['id'] ?>">
	                <div class="avatar" onclick="userPage(<?php echo $row_GetComments['user'] ?>)">
	                    <img src="<?php echo userAvatar($row_GetComments["user"]); ?>" width="36px" height="36px" style="border-radius: 50%"/>
	                </div>
	                <div class="name" onclick="userPage(<?php echo $row_GetComments['user'] ?>)">
	                    <?php echo userName($row_GetComments['user']); ?>
	                    <font size="-2"><?php echo timeAgo($row_GetComments['time']);?></font>
	                </div>

	                <?php if (isset ($_SESSION['MM_Id'])){ ?> 
	                    <?php  if (($row_GetComments['user'] == $_SESSION['MM_Id']) || (rango_admin($_SESSION['MM_Id']) ==4)) {?>
							<div class="delete" onClick="deleteComment(1, <?php echo $row_GetComments['id'] ?>)">
								<?php include("../../../images/svg/clear.php"); ?>
							</div>
							<div class="deleteBoxConfirmation" id="delete<?php echo $row_GetComments['id'] ?>">
								<div class="text">Delete this comment?</div>
								<div class="buttons">
									<button onClick="deleteComment(1, <?php echo $row_GetComments['id'] ?>)">NO</button>
									<button onClick="deleteComment(2, <?php echo $row_GetComments['id'] ?>)">YES</button>
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
	        <?php } while ($countComments < 10 && $row_GetComments = mysql_fetch_assoc($GetComments)); ?>
	    <?php } else { ?>
	    	<div class="noComents">NO COMMENTS</div>
	    <?php } ?>
	</div>

    <?php if ($totalRows_GetComments > 10) { ?>
    	<div class="loadMore" onclick="loadMorecomments(<?php echo $videoId ?>);"> + LOAD MORE</div>
    <?php } ?>
</div>
<script type="text/javascript">
	var videoPlayer = document.getElementById('video_player');

	//·····> Video player
    function playerAction(type, button){
    	if (type == 1) { //playPause
    		if (!videoPlayer.paused){
    			videoPlayer.pause();
    			$(button).html(playIcon);
    		} else {
    			videoPlayer.play();
    			$(button).html(pauseIcon);

    			playerAction(4, button);
    		}
    	} else if (type == 2) { //fullScreen
    		if ($.isFunction(videoPlayer.webkitEnterFullscreen))
                videoPlayer.webkitEnterFullscreen();
            else if ($.isFunction(videoPlayer.mozRequestFullScreen))
                videoPlayer.mozRequestFullScreen();
            else
                alert('Your browsers doesn\'t support fullscreen');
    	} else if (type == 3) { //More
    		alert('DVSG <br> More');
    	} else if (type == 4) { //Video click to show/Hide
    		$('.videoBox .boxContent .title').fadeToggle();
    		$('.videoBox .boxContent .playPause').fadeToggle();
    		$('.videoBox .boxContent .controlPanel').fadeToggle();
    	} else if (type == 5) { //Close
    		$('.modalBox').toggleClass('modalDisplay');
			$('body').toggleClass('modalHidden');

			videoPlayer.pause();
    		$(button).html(playIcon);
    	}
    }

    // ·····> format time
    function formatTime(time){
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

	// ·····> Set video progress
    function setProgressBar(target) {
    	var min = target.min,
		    max = target.max,
		    val = target.value;

    	var duration = Math.round(val / 1000 * videoPlayer.duration);
    	videoPlayer.currentTime = duration;
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

	//·····> Like photo
	function like(id){
		var countLikesVideo 	= $('.modalBox .box .videoBox .boxData .user .actions .analytics .likes .count'),
			likeIconVideo		= $('.modalBox .box .videoBox .boxData .user .actions .analytics .likes .like'),
			countLikes 			= parseInt(countLikesVideo.html());

		$.ajax({
	        type: 'POST',
	        url: '<?php echo $urlWeb ?>' + 'pages/user/videos/like.php',
	        data: 'videoId=' + id,
	        success: function(response){
	            if (response == 'like'){ // Like
	            	countLikes = countLikes +1;

	            	countLikesVideo.html(countLikes);
	            	likeIconVideo.html(likeIcon);
	        	} else { // Unlike
	            	countLikes = countLikes -1;

	            	countLikesVideo.html(countLikes);
	            	likeIconVideo.html(unlikeIcon);
	        	}
	        }
	    });
	}

	//·····> New comment
	function newComment(type, value, id){
		var buttonSendCommentVideo 	= $(".modalBox .box .videoBox .boxData .comments .newComment .button svg"),
			inputSendCommentVideo 	= $(".modalBox .box .videoBox .boxData .comments .newComment .inputBox"),
			commentsList 			= $('.modalBox .box .videoBox .boxData .comments .commentsList'),
			countCommentsVideo 		= $('.modalBox .box .videoBox .boxData .user .actions .analytics .comments .count'),
			countComments 			= parseInt(countCommentsVideo.html());

		if (type == 1) {
            if(value != ''){
                buttonSendCommentVideo.css("fill","#09f");
            }else{
                buttonSendCommentVideo.css("fill","#333");
            }
		} else if (type == 2) {
			if (value != '') {
                $.ajax({
                    type: "POST",
                    url: '<?php echo $urlWeb ?>' + 'pages/user/videos/comments/new.php',
                    data: 'commentText=' + value + '&videoId=' + id,
                    success: function(response) {
                        commentsList.prepend(response);
                        inputSendCommentVideo.val('');
                        countCommentsVideo.html(countComments + 1);
                    }
                });
            }
		}
	}

	//·····> Delete comment
	function deleteComment(type, id){
		var countCommentsVideo 	= $('.modalBox .box .videoBox .boxData .user .actions .analytics .comments .count'),
			countComments 		= parseInt(countCommentsVideo.html()),
			deleteComment		= $('.modalBox .box .videoBox .boxData .comments .commentsList .item #delete' + id),
			boxComment			= $('.modalBox .box .videoBox .boxData .comments .commentsList #comment' + id);

		if (type == 1) {
			deleteComment.toggle();
		}else if (type==2) {
			$.ajax({
				type: 'POST',
				url: '<?php echo $urlWeb ?>' + 'pages/user/videos/comments/delete.php',
				data: 'id=' + id,
				success: function(response){
					boxComment.fadeOut(300);
					deleteComment.fadeOut(300);

					countCommentsVideo.html(countComments - 1);
				}
			});
		}
	}

	//·····> Load more comment
	function loadMorecomments(id){
		var commentsList 					= $('.modalBox .box .videoBox .boxData .comments .commentsList'),
			buttonLoadMoreCommentsVideo 	= $('.modalBox .box .videoBox .boxData .comments .loadMore');

		$.ajax({
            type: "POST",
            url: '<?php echo $urlWeb ?>' + 'pages/user/videos/comments/loadMore.php',
            data: 'cuantity=' + 10 + '&videoId=' + id,
            success: function(response) {
            	if (response != '')
                	commentsList.append(response);
                else
                	buttonLoadMoreCommentsVideo.hide();

            }
        });
	}
</script>
<?php mysql_free_result($GetLikes); ?>
<?php mysql_free_result($GetComments); ?>