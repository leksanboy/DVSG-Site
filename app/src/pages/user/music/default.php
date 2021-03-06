<?php require_once('../../../Connections/conexion.php');
	$userId = $_POST['userId'];

    //Default data for LoadMore
    $_SESSION['loadMoreSongs'.$userId] = 0;

	// User music  --> songsList
	mysql_select_db($database_conexion, $conexion);
	$query_songsList = sprintf("SELECT f.id, f.song, m.name, m.title, m.duration 
                                FROM z_music_favorites f INNER JOIN z_music m ON m.id = f.song 
                                WHERE f.user = $userId AND f.is_deleted = 0 ORDER BY f.date DESC LIMIT 10");
	$songsList = mysql_query($query_songsList, $conexion) or die(mysql_error());
	$row_songsList = mysql_fetch_assoc($songsList);
	$totalRows_songsList = mysql_num_rows($songsList);
?>
<?php if ($totalRows_songsList != 0){ ?>
	<ul class="songsListBox">
		<?php 
			$_SESSION['counterSongs'.$userId] = - 1;
			do { 
			$_SESSION['counterSongs'.$userId] = $_SESSION['counterSongs'.$userId]+1;
		?>
			<li class="song<?php echo $row_songsList['id'] ?>" id="song<?php echo $_SESSION['counterSongs'.$userId] ?>">
				<div class="playPause" onClick="playTrack(<?php echo $_SESSION['counterSongs'.$userId] ?>)">
					<div class="play" style="display:block;">
						<?php include("../../../images/svg/play.php"); ?>
					</div>
					<div class="pause" style="display:none;">
						<?php include("../../../images/svg/pause.php"); ?>
					</div>
				</div>
				<div class="text" onClick="playTrack(<?php echo $_SESSION['counterSongs'.$userId] ?>)"><?php echo $row_songsList['title']?></div>
				<div class="duration"><?php echo $row_songsList['duration']?></div>

                <?php  if (isset($_SESSION['MM_Id'])) { ?>
                    <div class="actions">
                        <?php if ($userId == $_SESSION['MM_Id']) { ?>
                            <div class="add" onClick="deleteSong(1, <?php echo $row_songsList['id'] ?>)">
                                <?php include("../../../images/svg/close.php"); ?>
                            </div>
                            <div class="add added">
                                <?php include("../../../images/svg/add.php"); ?>
                            </div>
                        <?php } else { ?>
                            <div class="add" onClick="addSong(1, <?php echo $row_songsList['id'] ?>, <?php echo $row_songsList['song'] ?>)">
                                <?php include("../../../images/svg/add.php"); ?>
                            </div>
                            <div class="add added">
                                <?php include("../../../images/svg/check.php"); ?>
                            </div>
                        <?php } ?>
                    </div>
                <?php } ?>
			</li>
		<?php } while ($row_songsList = mysql_fetch_assoc($songsList)); ?>
	</ul>

    <?php if ($totalRows_songsList == 10){ ?>
        <div class="loadMore" onclick="loadMore();"> + LOAD MORE</div>
    <?php } ?>
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
        tracks = [ 	<?php mysql_data_seek( $songsList, 0 );
                        while($row = mysql_fetch_array( $songsList )) { ?>
    		            	{
    		                    "name": "<?php echo $row['title'] ?>",
    		                    "file": "<?php echo $row['name'] ?>",
    		                    "duration": "<?php echo $row['duration'] ?>"
    		                },
		            <?php } ?>
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
            idSongPlaying = idSong;
            player.play();
    	}
    }

    // ·····> Play·pause track
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

    //·····> Load more
    function loadMore(){
        $.ajax({ //HTML
            type: "GET",
            url: '<?php echo $urlWeb ?>' + 'pages/user/music/loadMore.php',
            data: 'cuantity=' + 10 + '&userId=' + userId,
            success: function(response) {
                if (response != '') {
                    response = JSON.parse(response);

                    for (var i = 0; i < response.length ; i++) {
                        tracks.push(response[i]);
                    }
                    trackCount = tracks.length;

                    for (var i = 0; i < response.length ; i++) {
                        var moreData = '<li class="song'+ response[i].id +'" id="song'+ response[i].counter+'">\
                                            <div class="playPause" onClick="playTrack('+ response[i].counter+')">\
                                                <div class="play" style="display:block;">\
                                                    <?php include("../../../images/svg/play.php"); ?>\
                                                </div>\
                                                <div class="pause" style="display:none;">\
                                                    <?php include("../../../images/svg/pause.php"); ?>\
                                                </div>\
                                            </div>\
                                            <div class="text" onClick="playTrack('+ response[i].counter+')">'+ response[i].name +'</div>\
                                            <div class="duration">'+ response[i].duration +'</div>\
                                            <?php  if (isset($_SESSION['MM_Id'])) { ?>\
                                                <div class="actions">\
                                                    <?php if ($userId == $_SESSION['MM_Id']) { ?>\
                                                        <div class="add" onClick="deleteSong(1, '+ response[i].id +')">\
                                                            <?php include("../../../images/svg/close.php"); ?>\
                                                        </div>\
                                                        <div class="add added">\
                                                            <?php include("../../../images/svg/add.php"); ?>\
                                                        </div>\
                                                    <?php } else { ?>\
                                                        <div class="add" onClick="addSong(1, '+ response[i].id +', '+ response[i].song +')">\
                                                            <?php include("../../../images/svg/add.php"); ?>\
                                                        </div>\
                                                        <div class="add added">\
                                                            <?php include("../../../images/svg/check.php"); ?>\
                                                        </div>\
                                                    <?php } ?>\
                                                </div>\
                                            <?php } ?>\
                                        </li>';

                        $('.songsListBox').append(moreData);
                    }
                } else {
                    $('.loadMore').hide();
                }
            }
        });
    }
</script>
<?php mysql_free_result($songsList); ?>
<?php mysql_free_result($songsListJS); ?>