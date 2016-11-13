<div class="userBox">
	<div class="backgroundImages">
		<div class="imgBox">
			<img src="<?php echo $row_userData['avatar_bg1']; ?>" class="fadeImage"/>
			<img src="<?php echo $row_userData['avatar_bg2']; ?>" class="fadeImage"/>
			<img src="<?php echo $row_userData['avatar_bg3']; ?>" class="fadeImage"/>
		</div>
	</div>

	<div class="dataBox">
		<div class="image">
			<img src="<?php echo $row_userData['avatar']; ?>"/>
		</div>

		<div class="name">
			<?php echo $row_userData['name']; ?>
		</div>

		<ul class="media">
			<li onClick="location.href='<?php echo $urlWeb ?>friends<?php echo $row_userData['id']; ?>'">
				<?php include("images/svg/friends.php");?>
				<span>Friends</span>
			</li>
			<li onClick="location.href='<?php echo $urlWeb ?>photos<?php echo $row_userData['id']; ?>'">
				<?php include("images/svg/photos.php");?>
				<span>Photos</span>
			</li>
			<li onClick="location.href='<?php echo $urlWeb ?>audios<?php echo $row_userData['id']; ?>'">
				<?php include("images/svg/music.php");?>
				<span>Songs</span>
			</li>
			<li onClick="location.href='<?php echo $urlWeb ?>videos<?php echo $row_userData['id']; ?>'">
				<?php include("images/svg/videos.php");?>
				<span>Videos</span>
			</li>
			<li onClick="infoButton(1)">
				<?php include("images/svg/information.php");?>
				<span>Information</span>
			</li>
		</ul>
	</div>
</div>

<?php  if ($totalRows_photosList!=''){?>
	<div class="photosBloque">
		<ul>
			<?php
				$contador = - 1;
				do {
				$contador = $contador + 1;
			?>
				<li class="photo<?php echo $row_photosList['photo'] ?>">
					<div class="image" onClick="openPhoto(1, <?php echo $contador ?>, <?php echo $row_photosList['photo'] ?>)">
						<div class="img" style="background-image: url(<?php echo $urlWeb ?>pages/user/photos/photos/<?php echo $row_photosList['name']?>)"></div>
					</div>
				</li>
			<?php } while ($row_photosList = mysql_fetch_assoc($photosList)); ?>
		</ul>
	</div>
<?php }?>

<?php if (isset ($_SESSION['MM_Id'])){ ?>
	<?php if ($userPageId == $_SESSION['MM_Id']) { ?>
		<div class="buttonCreate" onclick="createPost(1)">Create post</div>
	<?php } else { ?>
		<?php if(checkFriendStatus($userPageId, $_SESSION['MM_Id']) != 2){ ?>
			<?php if (checkIfImSender($userPageId, $_SESSION['MM_Id']) == 'true') { ?>
				<div class="buttonCreate buttonDecline" onclick="addToFriends(2, 1)"><?php include("images/svg/close.php");?>Decline</div>
				<div class="buttonCreate buttonAccept" onclick="addToFriends(2, 2)"><?php include("images/svg/check.php");?>Accept</div>
			<?php } else { ?>
				<div class="buttonCreate addFriend" onclick="addToFriends(1)">
					<span class="add" 
						<?php if(checkFriendStatus($userPageId, $_SESSION['MM_Id']) == 0){ ?>
							 style="display: block;" 
						<?php } else { ?>
							style="display: none;"
						<?php } ?>
					>
						<?php include("images/svg/friends-sent.php"); ?>
						Add as friend
					</span>

					<span class="load" style="display: none;">
						<?php include("images/svg/spinner.php");?>
					</span>

					<span class="cancel" 
						<?php if(checkFriendStatus($userPageId, $_SESSION['MM_Id']) == 1){ ?>
							 style="display: block;" 
						<?php } else { ?>
							style="display: none;"
						<?php } ?>
					>
						<?php include("images/svg/close.php"); ?>
						Cancel request
					</span>
				</div>
			<?php } ?>
		<?php } ?>
	<?php }?>
<?php } ?>

<div id="userPosts">
	<div class="loaderNews">
		<?php include("images/svg/spinner.php");?>
	</div>
</div>

<script type="text/javascript">
	var userId 			= <?php echo $userPageId ?>,
		userName 		= "<?php echo $row_userData['name'] ?>",
		userAvatar 		= "<?php echo $row_userData['avatar'] ?>";

	var photoIcon 		= '<?php include('images/svg/photos.php'); ?>',
		musicIcon 		= '<?php include('images/svg/music.php'); ?>',
		videoIcon 		= '<?php include('images/svg/videos.php'); ?>',
		closeIcon		= '<?php include('images/svg/close.php'); ?>',
		playIcon 		= '<?php include('images/svg/play.php'); ?>',
		removeIcon 		= '<?php include('images/svg/close.php'); ?>',
		uploadIcon 		= '<?php include('images/svg/upload.php'); ?>',
		arrowUpIcon 	= '<?php include('images/svg/arrow-up.php'); ?>',
		progressIcon 	= '<?php include('images/svg/progress.php'); ?>',
		addIcon 		= '<?php include('images/svg/add.php'); ?>',
		pauseIcon 		= '<?php include('images/svg/pause.php'); ?>',
		moreIcon 		= '<?php include('images/svg/dots.php'); ?>',
		fullscreenIcon 	= '<?php include('images/svg/fullscreen.php'); ?>',
		likeIcon 		= '<?php include('images/svg/like.php'); ?>',
		unlikeIcon 		= '<?php include('images/svg/unlike.php'); ?>',
		loaderIcon		= '<?php include('images/svg/spinner.php');?>';

	//·····> Header scrolling transparent to color
	if (window.matchMedia("(max-width: 560px)").matches) {
		var imagesBox = $('.backgroundImages .imgBox'),
			dataBox = $('.dataBox'),
		    header = $('.header'),
		    navHeight = 56;

		$('.backgroundImages').css('background', "#<?php echo $row_userData['primary_color'];?>");

		$(window).scroll(function() {
		    var scrollTop = $(this).scrollTop(),
		        headlineHeight = imagesBox.outerHeight() - navHeight,
		        navOffset = header.offset().top;

		    imagesBox.css({
		        'opacity': (1 - scrollTop / headlineHeight)
		    });
		    dataBox.children().css({
		        'transform': 'translateY(' + scrollTop * 0.4 + 'px)'
		    });

		    if (navOffset > headlineHeight) {
		        $('.userName').addClass('showUserName');
		        header.css({
							'boxShadow': '0 2px 5px rgba(0,0,0,.26)',
							'background': "#<?php echo $row_userData['primary_color'];?>"
						});
		    } else {
		        $('.userName').removeClass('showUserName');
				    header.css({
							'boxShadow': 'none',
							'background': "transparent"
						});
		    }
		});
	} else {
		$('.header').css({
			'boxShadow': '0 2px 5px rgba(0,0,0,.26)',
			'background': "#<?php echo $row_userData['primary_color'];?>"
		});
	}

	//·····> Add to friends
	<?php if (isset($_SESSION['MM_Id'])){ ?>
		var statusCheck = <?php echo checkFriendStatus($userPageId, $_SESSION['MM_Id']) ?>;
		function addToFriends(type, status){
			if (type==1) { // Send request
				$('.addFriend .add').hide();
				$('.addFriend .cancel').hide();
		        $('.addFriend .load').show();

		        console.log('status', statusCheck);

				$.ajax({
		            type: 'POST',
		            url: '<?php echo $urlWeb ?>' + 'pages/user/friends/status.php',
		            data: 'status=' + statusCheck + '&sender=' + <?php echo $_SESSION['MM_Id'] ?> + '&receiver=' + userId,
		            success: function(response){
		                if (statusCheck == 0) {
		                	statusCheck = 1;
		                	setTimeout(function() {
								$('.addFriend .load').hide();
								$('.addFriend .cancel').show();
							}, 1200);
		                } else {
		                	statusCheck = 0;
		                	setTimeout(function() {
								$('.addFriend .load').hide();
								$('.addFriend .add').show();
							}, 1200);
		                }
		            }
		        });
			} else if (type==2) { // Decline/Accept
				$.ajax({
		            type: 'POST',
		            url: '<?php echo $urlWeb ?>' + 'pages/user/friends/status.php',
		            data: 'status=' + status + '&receiver=' + <?php echo $_SESSION['MM_Id'] ?> + '&sender=' + userId,
		            success: function(response){
		            	$('.buttonCreate').hide();
		            }
		        });
			}
		}
	<?php } ?>

	//·····> Info button
	function infoButton(type){
		if (type == 1) { //Open
			alert('Working on user information');
		}else if (type == 2) { //Close

		}
	}

	//·····> Open photo
	function openPhoto(type, position, photoId){
		if (type==1) { // Open
			$('.modalBox').toggleClass('modalDisplay');
			setTimeout(function() {
				$('.modalBox').toggleClass('showModal');
			}, 100);
			$('body').toggleClass('modalHidden');

			$.ajax({
                type: 'GET',
                url: '<?php echo $urlWeb ?>' + 'pages/user/photos/slidePhotos.php',
                data: 'position=' + position + '&photoId=' + photoId + '&userId=' + userId,
                success: function(response){
                    $('.slidePhotosBox').html(response);
                }
            });

			var box = "<div class='slidePhotosBox'>\
							<div class='loader'>\
								"+loaderIcon+"\
							</div>\
						</div>\
						<div class='buttons'>\
							<button onClick='openPhoto(2)'>CLOSE</button>\
						</div>"

			$('.modalBox .box').html(box);
		} else if (type==2) { //Close
			$('.modalBox').toggleClass('modalDisplay');
			$('body').toggleClass('modalHidden');
		}
	}

	//·····> load News
	function defaultLoad(){
		$.ajax({
			type: 'POST',
			url: '<?php echo $urlWeb ?>' + 'pages/user/user/loadPosts.php',
			data: 'userId=' + userId + '&pageLocation=user',
			success: function(response) {
				$('#userPosts').html(response);
			}
		});
	};
	defaultLoad();

	//·····> Delete message on Inbox
	function deleteNews(type, id){
		if (type==1) {
			$('#delete'+id).toggle();
		}else if (type==2) {
			console.log('DELETED', id);

			$.ajax({
				type: 'POST',
				url: url + 'pages/user/user/delete.php',
				data: 'id=' + id,
				success: function(response){
					$('#news'+id).fadeOut(300);
				}
			});
		}
	}

	//·····> create post
	var photosArray = [],
		songsArray = [],
		videosArray = [];
	function createPost(type, data){
		if (type==1) { //Open
			$('.modalBox').toggleClass('modalDisplay');
			setTimeout(function() {
				$('.modalBox').toggleClass('showModal');
			}, 100);
			$('body').toggleClass('modalHidden');

			$.ajax({
				type: 'POST',
				url: '<?php echo $urlWeb ?>' + 'pages/user/user/createPost.php',
				data: 'userId=' + userId,
				success: function(response){
					$('.modalBox .box').html(response);
				}
			});
		} else if (type==2) { //Close
			$('.modalBox').toggleClass('modalDisplay');
			$('body').toggleClass('modalHidden');
		} else if (type==3) { //Publicate
				var photos = JSON.stringify(photosArray.reverse()).replace(/[&\/\\~*?<>]/g,'');
				var songs = JSON.stringify(songsArray.reverse()).replace(/[&\/\\~*?<>]/g,'');
				var videos = JSON.stringify(videosArray.reverse()).replace(/[&\/\\~*?<>]/g,'');

			if (data.length == 0 && photos.length<=2 && songs.length<=2 && videos.length<=2) {
				// console.log('VACIO');
			}else{
				$.ajax({
					type: 'POST',
					url: url + 'pages/user/user/publicatePost.php',
					data: 'content=' + data + '&photos=' + photos + '&audios=' + songs + '&videos=' + videos,
					success: function(response) {
						defaultLoad();
						
						$('.modalBox').toggleClass('modalDisplay');
						$('body').toggleClass('modalHidden');

						photosArray = [];
						songsArray = [];
						videosArray = [];
					}
				});
			}
		}
	}

	//·····> find object in array
	function arrayObjectIndexOf(myArray, searchTerm, property){
		for(var i = 0, len = myArray.length; i < len; i++) {
	        if (myArray[i][property] === searchTerm) return i;
	    }
	    return -1;
	}

	//·····> attach audio files
	function attachAudioFiles(type, data){
		if (type==1) {
			$('.audioFilesBox').toggle();
		} else if (type==2) { //Add
			var dataPosition = arrayObjectIndexOf(songsArray, data.song, "song");

			if (dataPosition == -1){
				songsArray.push(data);
				$('.song'+ data.song + ' .actions .add').hide();
				$('.song'+ data.song + ' .actions .added').show();
			} else{
				songsArray.splice(dataPosition,1);
				$('.song'+ data.song + ' .actions .add').show();
				$('.song'+ data.song + ' .actions .added').hide();

				$('.songsListBoxAdded .song'+ data.song).fadeOut();
			}

			console.log('Songs:', songsArray);
		} else if (type==3) { //Done
			var bodySongs = '<ul class="songsListBoxAdded">';
					songsArray.forEach(function(item){
						// var itemArray = JSON.stringify(item);
						var itemArray = JSON.stringify(item);
						itemArray = itemArray.replace(/'/g, '');
						console.log('itemArray', itemArray);

						bodySongs += 	"<li class='song"+ item.song +"'>\
											<div class='playPause'>\
												<div class='play' style='display:block;'>"+ playIcon +"</div>\
											</div>\
											<div class='text'>"+ item.title +"</div>\
											<div class='duration'>"+ item.duration +"</div>\
											<div class='actions' onClick='attachAudioFiles(2,"+ itemArray +")'>\
					                            <div class='remove'>"+ removeIcon +"</div>\
					                        </div>\
										</li>";
					});
				bodySongs += '</ul>';

			$('.audioFilesBox').toggle();
			$('.createPostBox .addedFiles .audioFiles').html(bodySongs);
		}
	}

	//·····> attach photo files
	function attachPhotoFiles(type, data){
		if (type==1) {
			$('.photoFilesBox').toggle();
		} else if (type==2) { //Add
			var dataPosition = arrayObjectIndexOf(photosArray, data.photo, "photo");

			if (dataPosition == -1){
				photosArray.push(data);
				$('.photo'+ data.photo + ' .actions .add').hide();
				$('.photo'+ data.photo + ' .actions .added').show();
			} else{
				photosArray.splice(dataPosition,1);
				$('.photo'+ data.photo + ' .actions .add').show();
				$('.photo'+ data.photo + ' .actions .added').hide();

				$('.photosListBoxAdded .photo'+ data.photo).fadeOut();
			}

			console.log('Photos:', photosArray);
		} else if (type==3) { //Done
			var bodyPhotos = '<ul class="photosListBoxAdded">';
					photosArray.forEach(function(item){
						// var itemArray = JSON.stringify(item);
						var itemArray = JSON.stringify(item);
						itemArray = itemArray.replace(/'/g, '');
						console.log('itemArray', itemArray);

						bodyPhotos += 	"<li class='photo"+ item.photo +"'>\
											<div class='image'>\
												<div class='img' style='background-image: url(<?php echo $urlWeb ?>pages/user/photos/photos/"+ item.name +"); width: 100%; height: 100%;'></div>\
											</div>\
											<div class='actions' onClick='attachPhotoFiles(2,"+ itemArray +")'>\
					                            <div class='remove'>"+ removeIcon +"</div>\
					                        </div>\
										</li>";
					});
				bodyPhotos += '</ul>';

			$('.photoFilesBox').toggle();
			$('.createPostBox .addedFiles .photoFiles').html(bodyPhotos);
		}
	}

	//·····> attach audio files
	function attachVideoFiles(type, data){
		if (type==1) {
			$('.videoFilesBox').toggle();
		} else if (type==2) { //Add
			var dataPosition = arrayObjectIndexOf(videosArray, data.video, "video");

			if (dataPosition == -1){
				videosArray.push(data);
				$('.video'+ data.video + ' .actions .add').hide();
				$('.video'+ data.video + ' .actions .added').show();
			} else{
				videosArray.splice(dataPosition,1);
				$('.video'+ data.video + ' .actions .add').show();
				$('.video'+ data.video + ' .actions .added').hide();

				$('.videosListBoxAdded .video'+ data.video).fadeOut();
			}

			console.log('Videos:', videosArray);
		} else if (type==3) { //Done
			var bodyVideos = '<ul class="videosListBoxAdded">';
					videosArray.forEach(function(item){
						// var itemArray = JSON.stringify(item);
						var itemArray = JSON.stringify(item);
						itemArray = itemArray.replace(/'/g, '');
						console.log('itemArray', itemArray);

						bodyVideos += 	"<li class='video"+ item.video +"'>\
											<div class='video'>\
												<div class='video'>\
													<video>\
														<source src='<?php echo $urlWeb ?>pages/user/videos/videos/"+item.name+"'/>\
													</video>\
												</div>\
											</div>\
											<div class='actions' onClick='attachVideoFiles(2,"+ itemArray +")'>\
					                            <div class='remove'>"+ removeIcon +"</div>\
					                        </div>\
										</li>";
					});
				bodyVideos += '</ul>';

			$('.videoFilesBox').toggle();
			$('.createPostBox .addedFiles .videoFiles').html(bodyVideos);
		}
	}
</script>