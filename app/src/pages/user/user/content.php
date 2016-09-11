<div class="userBox">
	<div class="backgroundImages">
		<div class="imgBox">
			<img src="<?php echo $row_userData['avatar_bg1']; ?>" class="fadeImage"/>
			<img src="<?php echo $row_userData['avatar_bg2']; ?>" class="fadeImage"/>
			<img src="<?php echo $row_userData['avatar_bg3']; ?>" class="fadeImage"/>
		</div>
	</div>

	<div class="dataBox">
		<img src="<?php echo $row_userData['avatar']; ?>"/>

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
						<div class="img" style="background-image: url(<?php echo $urlWeb ?>pages/user/photos/photos/<?php echo $row_photosList['name']?>);"></div>
					</div>
				</li>
			<?php } while ($row_photosList = mysql_fetch_assoc($photosList)); ?>
		</ul>
	</div>
<?php }?>

<div class="buttonCreate" onclick="createPost(1)">Create post</div>

<div id="userPosts"></div>

<script type="text/javascript">
	var userId 			= <?php echo $userPageId ?>,
			userName 		= "<?php echo $row_userData['name'] ?>",
			userAvatar 		= "<?php echo $row_userData['avatar'] ?>";

	var photoIcon 		= '<?php include('images/svg/photos.php'); ?>',
			musicIcon 		= '<?php include('images/svg/music.php'); ?>',
			videoIcon 		= '<?php include('images/svg/videos.php'); ?>',
			closeIcon		= '<?php include('images/svg/close.php'); ?>';

	//·····> Header scrolling transparent to color
	var imagesBox = $('.backgroundImages .imgBox'),
		dataBox = $('.dataBox'),
	    header = $('.header'),
	    navHeight = 56;

	$('.backgroundImages').css('background', "#<?php echo $row_userData['secondary_color'];?>");

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
						'background': "#<?php echo $row_userData['secondary_color'];?>"
					});
	    } else {
	        $('.userName').removeClass('showUserName');
			    header.css({
						'boxShadow': 'none',
						'background': "transparent"
					});
	    }
	});

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
                type: 'POST',
                url: '<?php echo $urlWeb ?>' + 'pages/user/photos/slidePhotos.php',
                data: 'position=' + position + '&photoId=' + photoId + '&userId=' + userId,
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

	//·····> load News
	function defaultLoad(){
		$.ajax({
			type: 'POST',
			url: '<?php echo $urlWeb ?>' + 'pages/user/user/loadNews.php',
			data: 'userId=' + userId,
			success: function(response) {
				$('#userPosts').html(response);
			}
		});
	};
	defaultLoad();

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
			$.ajax({
				type: 'POST',
				url: url + 'pages/user/user/publicatePost.php',
				data: 'content=' + data + '&photos=' + photosArray + '&songs=' + songsArray + '&videos=' + videosArray,
				success: function(response) {
					defaultLoad();
					
					$('.modalBox').toggleClass('modalDisplay');
					$('body').toggleClass('modalHidden');
				}
			});
		}
	}

	//·····> attach photo files
	function attachPhotoFiles(type, data){
		if (type==1) {
			var i = 0,
				reader,
				file,
				photoArray = [];

			for (;i < data.currentTarget.files.length; i++) {
				file = data.currentTarget.files[i];
				reader = new FileReader();
				reader.readAsDataURL(file);

				reader.onload = function (e) {
					var a = e.target.result;
					photoArray.push(a);

					var b = "<div class='image'>\
						<div class='img' style='background-image: url("+ a +");'></div>\
						<div class='delete'>"+ closeIcon +"</div>\
						</div>";
				
					$(".addedFiles .files .container .photoFiles").prepend(b);
				}
			}

			console.log('photoArray', photoArray);
		} else if (type==2) { //Add
			// if (arraySong.indexOf(song) == -1){
			// 	arraySong.push(song);
			// 	$('.song'+ id + ' .actions .add').hide();
			// 	$('.song'+ id + ' .actions .added').show();
			// } else{
			// 	arraySong.splice(arraySong.indexOf(song),1);
			// 	$('.song'+ id + ' .actions .add').show();
			// 	$('.song'+ id + ' .actions .added').hide();
			// }

			// console.log('SongS', arraySong);
		}
	}

	//·····> attach audio files
	function attachAudioFiles(type, id){
		if (type==1) {
			$('.audioFilesBox').toggle();
		} else if (type==2) { //Add
			if (songsArray.indexOf(id) == -1){
				songsArray.push(id);
				$('.song'+ id + ' .actions .add').hide();
				$('.song'+ id + ' .actions .added').show();
			} else{
				songsArray.splice(songsArray.indexOf(id),1);
				$('.song'+ id + ' .actions .add').show();
				$('.song'+ id + ' .actions .added').hide();
			}

			console.log('SongS', songsArray);
		}
	}

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
</script>
