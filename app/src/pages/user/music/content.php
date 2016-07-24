<div class="searchBox">
	<form id="formSearchBlog" onsubmit="return false">
		<input type="text" id="inputSearch" name="searchBlog" autocomplete="off" onkeyup="searchButtonEnable(this.value)" placeholder="Search..." value="<?php if (isset($_SESSION['textBlogSearch'])) echo $_SESSION['textBlogSearch'];?>"/>
		<?php include("images/svg/search.php");?>
		<div class="searchApply">
			<label for="submitButton">
				<div class="button" id="searchButton">
					<input type="submit" onclick="searchButton();" id="submitButton" style="display: none;"></input>
					<?php include("images/svg/send.php");?>
				</div>
			</label>
			<div class="button" onclick="resetSearch()" id="resetButton" <?php if(isset($_SESSION['textBlogSearch']) || isset($_SESSION['blogFilter'])){?>style="display:block"<?php }?>>
				<?php include("images/svg/remove.php");?>
			</div>
		</div>
	</form>
</div>


<?php if ($totalRows_songsList!=0){ ?>
	<ul class="songsListBox" id="plUL">
		<?php 
			$contador=-1; 
			do { 
			$contador = $contador+1;
		?>
			<li id="song<?php echo $contador ?>">
				<div class="playPause" onClick="playTrack(<?php echo $contador ?>)">
					<div class="play" style="display:block;">
						<?php include("images/svg/play.php"); ?>
					</div>
					<div class="pause" style="display:none;">
						<?php include("images/svg/pause.php"); ?>
					</div>
				</div>
				<div class="text" onClick="playTrack(<?php echo $contador ?>)"><?php echo $row_songsList['title']?></div>
				<div class="duration"><?php echo $row_songsList['duration']?></div>
				<div class="actions">
					<a onClick="deleteSong(1, <?php echo $row_songsList['id'] ?>)">
						<?php include("images/svg/remove.php"); ?>
                    </a>
				</div>
				<div class="deleteBoxConfirmation" id="delete<?php echo $row_songsList['id'] ?>">
					<div class="text">Delete this song?</div>
					<div class="buttons">
						<button onClick="deleteSong(1, <?php echo $row_songsList['id'] ?>)">NO</button>
						<button onClick="deleteSong(2, <?php echo $row_songsList['id'] ?>)">YES</button>
					</div>
				</div>
			</li>
		<?php } while ($row_songsList = mysql_fetch_assoc($songsList)); ?>
	</ul>
<?php } else { ?>
	<div class="noData">
		No songs
	</div>
<?php } mysql_free_result($songsList);?>



<script type="text/javascript">
	var uploadIcon = '<svg viewBox="0 0 48 48"><path d="M18 32h12V20h8L24 6 10 20h8zm-8 4h28v4H10z"/></svg>';
	var loadingIcon = '<svg viewBox="0 0 28 28"><g class="qp-circular-loader"><path class="qp-circular-loader-path" fill="none" d="M 14,1.5 A 12.5,12.5 0 1 1 1.5,14" stroke-linecap="round" /></g></svg>';
	//·····> Add new song
	function getFile(el){
		return document.getElementById(el);
	}

	function addSong(type, event){
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
								<input type='file' name='fileUpload' id='fileUpload' onChange='addSong(4, event)' accept='audio/*'>\
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
								<button onClick='addSong(3)'>UPLOAD</button>\
								<button onClick='addSong(2)'>CLOSE</button>\
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

	/* https://www.developphp.com/video/JavaScript/File-Upload-Progress-Bar-Meter-Tutorial-Ajax-PHP */
	function progressHandler(event){
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

	//·····> Delete song
	function deleteSong(type, id){
		if (type==1) {
			$('#delete'+id).toggle();
		}else if (type==2) {
			$.ajax({
				type: 'POST',
				url: url + 'pages/user/music/delete.php',
				data: 'id=' + id,
				success: function(response){
					$('#song'+id).fadeOut(300);
				}
			});
		}
	}

	//·····> Search
	function searchButtonEnable(value){
        if(value != ''){
            $("#searchButton svg").css("fill","#09f");
        }else{
            $("#searchButton svg").css("fill","#777");
            $('#resetButton').hide();
        }
    }

	function searchButton(){
		$('#resetButton').show();
		$("#searchButton svg").css("fill","#777");
		$('#loaderShortByShop').show();
		$('#blogPostsContainer').css("opacity","0.25");

		setTimeout(function() {
			$.ajax({
				type: 'POST',
				url: url + 'pages/blog/advancedSearch.php',
				data: $('#formSearchBlog').serialize(),
				success: function(response) {
					$('.loadMore').show();
					$('#loaderShortByShop').hide();
					$('#blogPostsContainer').css("opacity","1").html(response);
					$('#shortBy').attr('selected', 'selected');

					if (response.length > 2) {
						$('.loadMore').show();
					}
				}
			});
		}, 1000);
	}

	function resetSearch(){
		$('#resetButton').hide();
		$('#inputSearch').val('');
		$('#loaderShortByShop').show();
		$("#searchButton svg").css("fill","#777");
		$('#shortBy').attr('selected', 'selected');
		$('#blogPostsContainer').css("opacity","0.25");

		setTimeout(function() {
			$.ajax({
				type: 'POST',
				url: url + 'pages/blog/resetSearch.php',
				data: '',
				success: function(response) {
					$('#loaderShortByShop').hide();
					$('#blogPostsContainer').css("opacity","1").html(response);

					if (response.length > 2) {
						$('.loadMore').show();
					}
				}
			});
		}, 1000);
	}
</script>
<script type="text/javascript">
    var player = document.getElementById('playerBoxAudio');
    var idSong = parseInt($("#playerBoxAudioCounter").html());

    // ···> Get songs list
    var playing = false,
        mediaPath = '<?php echo $urlWeb ?>' + 'pages/user/music/songs/',
        tracks = [ 	<?php $contador = 0; 
        			do {
            			$contador = $contador + 1;
		            ?>
		            	{
		                    "track": 	<?php echo $contador ?>,
		                    "name": 	"<?php echo $row_songsListJS['title'] ?>",
		                    "file": 	"<?php echo $row_songsListJS['name'] ?>"
		                },
		            <?php } while ($row_songsListJS = mysql_fetch_assoc($songsListJS)); ?>
		        ],
        trackCount = tracks.length,
        nowPlayingTitle = $('#playerBoxAudioPlayingTitle');

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

    // ·····> Load track once (first time)
    function loadTrack(id) {
        nowPlayingTitle.text(tracks[id].name);
        audio.src = mediaPath + tracks[id].file;

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
            }

            $('#song'+ idSong + ' .playPause .play').hide();
            $('#song'+ idSong + ' .playPause .pause').show();
            $('.playerBox .buttons .playPause .play').hide();
        	$('.playerBox .buttons .playPause .pause').show();

        	nowPlayingTitle.text(tracks[idSong].name);
        	audio.src = mediaPath + tracks[idSong].file;
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
                $('#song'+ x + ' .playPause .play').show();
            	$('#song'+ x + ' .playPause .pause').hide();
            }

            $('#song'+ idSong + ' .playPause .play').hide();
            $('#song'+ idSong + ' .playPause .pause').show();
            $('.playerBox .buttons .playPause .play').hide();
        	$('.playerBox .buttons .playPause .pause').show();

        	nowPlayingTitle.text(tracks[idSong].name);
        	audio.src = mediaPath + tracks[idSong].file;
            player.play();
    	}
    }

    // ·····> Play·pause track
    var idSongPlaying;
    function playTrack(id) {
    	idSong = id;
    	songDuration();

        for (x = 0; x < trackCount ; x++) {
            $('#song'+ x + ' .playPause .play').show();
        	$('#song'+ x + ' .playPause .pause').hide();
        }

        if (idSongPlaying != idSong || idSongPlaying == undefined) {
        	idSongPlaying = idSong;

        	nowPlayingTitle.text(tracks[idSong].name);
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
	    	if (time == undefined || time == NaN)
	    		time = '00:00';
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