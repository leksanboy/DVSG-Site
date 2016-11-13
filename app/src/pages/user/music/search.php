<?php require_once('../../../Connections/conexion.php');
    $searchValue = $_GET['searchValue'];
    $userId = $_GET['userId'];

    //Default data for LoadMoreSearch
    $_SESSION['loadMoreSearchSongs'.$userId] = 0;

    //SEARCH
    mysql_select_db($database_conexion, $conexion);
    $query_searchData = sprintf("SELECT * 
                                    FROM z_music 
                                    WHERE title LIKE %s ORDER BY title DESC LIMIT 10", 
    GetSQLValueString("%" . $searchValue . "%", "text"));
    $searchData = mysql_query($query_searchData, $conexion) or die(mysql_error());
    $row_searchData = mysql_fetch_assoc($searchData);
    $totalRows_searchData = mysql_num_rows($searchData);
?>
<?php if ($totalRows_searchData != 0){ ?>
	<ul class="songsListBox">
		<?php 
			$_SESSION['counterSongsSearch'.$userId] = -1; 
			do { 
			$_SESSION['counterSongsSearch'.$userId] = $_SESSION['counterSongsSearch'.$userId]+1;
		?>
			<li id="songSearch<?php echo $_SESSION['counterSongsSearch'.$userId] ?>" class="songSearch<?php echo $row_searchData['id'] ?>">
				<div class="playPause" onClick="playTrack(<?php echo $_SESSION['counterSongsSearch'.$userId] ?>)">
					<div class="play" style="display:block;">
						<?php include("../../../images/svg/play.php"); ?>
					</div>
					<div class="pause" style="display:none;">
						<?php include("../../../images/svg/pause.php"); ?>
					</div>
				</div>
				<div class="text" onClick="playTrack(<?php echo $_SESSION['counterSongsSearch'.$userId] ?>)"><?php echo $row_searchData['title']?></div>
				<div class="duration"><?php echo $row_searchData['duration']?></div>
                <?php if (isset ($_SESSION['MM_Id'])){ ?>
    				<div class="actions">
    					<div class="add" onClick="addSongSearch(1, <?php echo $row_searchData['id'] ?>)">
    						<?php include("../../../images/svg/add.php"); ?>
    					</div>
    					<div class="add added">
    						<?php include("../../../images/svg/check.php"); ?>
    					</div>
    				</div>
                <?php } ?>
			</li>
		<?php } while ($row_searchData = mysql_fetch_assoc($searchData)); ?>
	</ul>

    <?php if ($totalRows_searchData == 10){ ?>
        <div class="loadMore" onclick="loadMoreSearch();"> + LOAD MORE</div>
    <?php } ?>
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
        tracks = [  <?php mysql_data_seek( $searchData, 0 );
                        while($row = mysql_fetch_array( $searchData )) { ?>
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

    //·····> Load more
    function loadMoreSearch(){
        $.ajax({ //HTML
            type: "GET",
            url: '<?php echo $urlWeb ?>' + 'pages/user/music/loadMoreSearch.php',
            data: 'cuantity=' + 10 + '&userId=' + userId,
            success: function(response) {
                if (response != '') {
                    response = JSON.parse(response);

                    for (var i = 0; i < response.length ; i++) {
                        tracks.push(response[i]);
                    }
                    trackCount = tracks.length;

                    for (var i = 0; i < response.length ; i++) {
                        var moreData = '<li id="songSearch'+ response[i].counter+'" class="songSearch'+ response[i].id +'">\
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
                                                    <div class="add" onClick="addSongSearch(1, '+ response[i].id +')">\
                                                        <?php include("../../../images/svg/add.php"); ?>\
                                                    </div>\
                                                    <div class="add added">\
                                                        <?php include("../../../images/svg/check.php"); ?>\
                                                    </div>\
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
<?php mysql_free_result($searchData); ?>