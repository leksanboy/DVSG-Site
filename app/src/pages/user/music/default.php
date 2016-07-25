<?php require_once('../../../Connections/conexion.php');
	$userId = $_SESSION['MM_Id'];

	//User music  --> songsList
	mysql_select_db($database_conexion, $conexion);
	$query_songsList = sprintf("SELECT f.id, m.name, m.title, m.duration FROM z_music_favorites f INNER JOIN z_music m ON m.id = f.song WHERE f.user = $userId ORDER BY f.date DESC");
	$songsList = mysql_query($query_songsList, $conexion) or die(mysql_error());
	$row_songsList = mysql_fetch_assoc($songsList);
	$totalRows_songsList = mysql_num_rows($songsList);

	//User music  --> songsListJS
	mysql_select_db($database_conexion, $conexion);
	$query_songsListJS = sprintf("SELECT f.id, m.name, m.title, m.duration FROM z_music_favorites f INNER JOIN z_music m ON m.id = f.song WHERE f.user = $userId ORDER BY f.date DESC");
	$songsListJS = mysql_query($query_songsListJS, $conexion) or die(mysql_error());
	$row_songsListJS = mysql_fetch_assoc($songsListJS);
	$totalRows_songsListJS = mysql_num_rows($songsListJS);
?>

<?php if ($totalRows_songsList != 0){ ?>
	<ul class="songsListBox">
		<?php 
			$contador=-1; 
			do { 
			$contador = $contador+1;
		?>
			<li id="song<?php echo $contador ?>" class="song<?php echo $row_songsList['id'] ?>">
				<div class="playPause" onClick="playTrack(<?php echo $contador ?>)">
					<div class="play" style="display:block;">
						<?php include("../../../images/svg/play.php"); ?>
					</div>
					<div class="pause" style="display:none;">
						<?php include("../../../images/svg/pause.php"); ?>
					</div>
				</div>
				<div class="text" onClick="playTrack(<?php echo $contador ?>)"><?php echo $row_songsList['title']?></div>
				<div class="duration"><?php echo $row_songsList['duration']?></div>
				<div class="actions">
					<div class="delete" onClick="deleteSong(1, <?php echo $row_songsList['id'] ?>)">
						<?php include("../../../images/svg/clear.php"); ?>
					</div>
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
		                    "name": "<?php echo $row_songsListJS['title'] ?>",
		                    "file": "<?php echo $row_songsListJS['name'] ?>",
		                    "duration": "<?php echo $row_songsListJS['duration'] ?>"
		                },
		            <?php } while ($row_songsListJS = mysql_fetch_assoc($songsListJS)); ?>
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
            }

            $('.songsListBox li').removeClass('active');
        	$('.songsListBox li#song' + idSong).addClass('active');
            $('#song'+ idSong + ' .playPause .play').hide();
            $('#song'+ idSong + ' .playPause .pause').show();
            $('.playerBox .buttons .playPause .play').hide();
        	$('.playerBox .buttons .playPause .pause').show();

        	nowPlayingTitle.text(tracks[idSong].name);
        	nowPlayingDuration.text(tracks[idSong].duration);
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

            $('.songsListBox li').removeClass('active');
        	$('.songsListBox li#song' + idSong).addClass('active');
            $('#song'+ idSong + ' .playPause .play').hide();
            $('#song'+ idSong + ' .playPause .pause').show();
            $('.playerBox .buttons .playPause .play').hide();
        	$('.playerBox .buttons .playPause .pause').show();

        	nowPlayingTitle.text(tracks[idSong].name);
        	nowPlayingDuration.text(tracks[idSong].duration);
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

        $('.songsListBox li').removeClass('active');
        $('.songsListBox li#song' + idSong).addClass('active');

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
<?php mysql_free_result($songsList); ?>
<?php mysql_free_result($songsListJS); ?>