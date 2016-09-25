<?php require_once('../../../Connections/conexion.php');
	$titleValue = $_POST['titleValue'];

	//All music --> songsListSearch
	mysql_select_db($database_conexion, $conexion);
	$query_songsListSearch = sprintf("SELECT * FROM z_music WHERE title LIKE %s ORDER BY title DESC", 
		GetSQLValueString("%" . $titleValue . "%", "text"));
	$songsListSearch = mysql_query($query_songsListSearch, $conexion) or die(mysql_error());
	$row_songsListSearch = mysql_fetch_assoc($songsListSearch);
	$totalRows_songsListSearch = mysql_num_rows($songsListSearch);

	//All music --> songsListSearchJS
	mysql_select_db($database_conexion, $conexion);
	$query_songsListSearchJS = sprintf("SELECT * FROM z_music WHERE title LIKE %s ORDER BY title DESC", 
		GetSQLValueString("%" . $titleValue . "%", "text"));
	$songsListSearchJS = mysql_query($query_songsListSearchJS, $conexion) or die(mysql_error());
	$row_songsListSearchJS = mysql_fetch_assoc($songsListSearchJS);
	$totalRows_songsListSearchJS = mysql_num_rows($songsListSearchJS);
?>

<?php if ($totalRows_songsListSearch != 0){ ?>
	<ul class="songsListBox">
		<?php 
			$contador=-1; 
			do { 
			$contador = $contador+1;
		?>
			<li id="songSearch<?php echo $contador ?>" class="songSearch<?php echo $row_songsListSearch['id'] ?>">
				<div class="playPause" onClick="playTrack(<?php echo $contador ?>)">
					<div class="play" style="display:block;">
						<?php include("../../../images/svg/play.php"); ?>
					</div>
					<div class="pause" style="display:none;">
						<?php include("../../../images/svg/pause.php"); ?>
					</div>
				</div>
				<div class="text" onClick="playTrack(<?php echo $contador ?>)"><?php echo $row_songsListSearch['title']?></div>
				<div class="duration"><?php echo $row_songsListSearch['duration']?></div>
				<div class="actions">
					<div class="add" onClick="addSongSearch(1, <?php echo $row_songsListSearch['id'] ?>)">
						<?php include("../../../images/svg/add.php"); ?>
					</div>
					<div class="add added">
						<?php include("../../../images/svg/check.php"); ?>
					</div>
					
				</div>
			</li>
		<?php } while ($row_songsListSearch = mysql_fetch_assoc($songsListSearch)); ?>
	</ul>
<?php } else { ?>
	<div class="noData">
		No result songs
	</div>
<?php } ?>
<script type="text/javascript">
	$("#playerBoxAudioCounter").html('0');
	$('.playerBox .buttons .playPause .play').show();
    $('.playerBox .buttons .playPause .pause').hide();
	var player = document.getElementById('playerBoxAudio');
    var idSong = parseInt($("#playerBoxAudioCounter").html());

    // ···> Get songs list
    var playing = false,
        mediaPath = '<?php echo $urlWeb ?>' + 'pages/user/music/songs/',
        tracks = [ 	<?php do { ?>
		            	{
		                    "name": "<?php echo $row_songsListSearchJS['title'] ?>",
		                    "file": "<?php echo $row_songsListSearchJS['name'] ?>",
		                    "duration": "<?php echo $row_songsListSearchJS['duration'] ?>"
		                },
		            <?php } while ($row_songsListSearchJS = mysql_fetch_assoc($songsListSearchJS)); ?>
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
        $('.songsListBox li#songSearch' + id).addClass('active');

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

                $('#songSearch'+ idSong + ' .playPause .play').show();
                $('#songSearch'+ idSong + ' .playPause .pause').hide();
            } else {
                player.play();

                $('#songSearch'+ idSong + ' .playPause .play').hide();
                $('#songSearch'+ idSong + ' .playPause .pause').show();
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
                $('#songSearch'+ x + ' .playPause .play').show();
            	$('#songSearch'+ x + ' .playPause .pause').hide();
            }

            $('.songsListBox li').removeClass('active');
        	$('.songsListBox li#songSearch' + idSong).addClass('active');
            $('#songSearch'+ idSong + ' .playPause .play').hide();
            $('#songSearch'+ idSong + ' .playPause .pause').show();
            $('.playerBox .buttons .playPause .play').hide();
        	$('.playerBox .buttons .playPause .pause').show();

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
                $('#songSearch'+ x + ' .playPause .play').show();
            	$('#songSearch'+ x + ' .playPause .pause').hide();
            }

            $('.songsListBox li').removeClass('active');
        	$('.songsListBox li#songSearch' + idSong).addClass('active');
            $('#songSearch'+ idSong + ' .playPause .play').hide();
            $('#songSearch'+ idSong + ' .playPause .pause').show();
            $('.playerBox .buttons .playPause .play').hide();
        	$('.playerBox .buttons .playPause .pause').show();

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
            $('#songSearch'+ x + ' .playPause .play').show();
        	$('#songSearch'+ x + ' .playPause .pause').hide();
        }

        $('.songsListBox li').removeClass('active');
        $('.songsListBox li#songSearch' + idSong).addClass('active');

        if (idSongPlaying != idSong || idSongPlaying == undefined) {
        	idSongPlaying = idSong;

        	nowPlayingTitle.text(tracks[idSong].name);
        	nowPlayingDuration.text(tracks[idSong].duration);
        	audio.src = mediaPath + tracks[idSong].file;
        }

        if (!player.paused) {
            player.pause();

            $('#songSearch'+ idSong + ' .playPause .play').show();
            $('#songSearch'+ idSong + ' .playPause .pause').hide();
            $('.playerBox .buttons .playPause .play').show();
        	$('.playerBox .buttons .playPause .pause').hide();
        } else {
            player.play();

            $('#songSearch'+ idSong + ' .playPause .play').hide();
            $('#songSearch'+ idSong + ' .playPause .pause').show();
            $('.playerBox .buttons .playPause .play').hide();
        	$('.playerBox .buttons .playPause .pause').show();
        }
    };
</script>
<?php mysql_free_result($songsListSearch); ?>
<?php mysql_free_result($songsListSearchJS); ?>