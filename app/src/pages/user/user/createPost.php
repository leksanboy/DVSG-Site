<?php require_once '../../../Connections/conexion.php';
    $userId = $_POST['userId'];

    //User data
    mysql_select_db($database_conexion, $conexion);
    $query_userData = sprintf('SELECT * FROM z_users WHERE id = %s',
    GetSQLValueString($userId, 'int'));
    $userData = mysql_query($query_userData, $conexion) or die(mysql_error());
    $row_userData = mysql_fetch_assoc($userData);
    $totalRows_userData = mysql_num_rows($userData);

    //User music
    mysql_select_db($database_conexion, $conexion);
    $query_songsList = sprintf("SELECT DISTINCT f.song, m.name, m.title, m.duration FROM z_music_favorites f INNER JOIN z_music m ON m.id = f.song WHERE f.user = $userId ORDER BY f.date DESC LIMIT 20");
    $songsList = mysql_query($query_songsList, $conexion) or die(mysql_error());
    $row_songsList = mysql_fetch_assoc($songsList);
    $totalRows_songsList = mysql_num_rows($songsList);

    //User photos
	mysql_select_db($database_conexion, $conexion);
	$query_photosList = sprintf("SELECT f.photo, p.name FROM z_photos_favorites f INNER JOIN z_photos p ON p.id = f.photo WHERE f.user = $userId ORDER BY f.date DESC LIMIT 12");
	$photosList = mysql_query($query_photosList, $conexion) or die(mysql_error());
	$row_photosList = mysql_fetch_assoc($photosList);
	$totalRows_photosList = mysql_num_rows($photosList);

	//User videos
    mysql_select_db($database_conexion, $conexion);
    $query_videosList = sprintf("SELECT DISTINCT f.video, v.name, v.title, v.duration FROM z_videos_favorites f INNER JOIN z_videos v ON v.id = f.video WHERE f.user = $userId ORDER BY f.date DESC LIMIT 8");
    $videosList = mysql_query($query_videosList, $conexion) or die(mysql_error());
    $row_videosList = mysql_fetch_assoc($videosList);
    $totalRows_videosList = mysql_num_rows($videosList);
?>
<form onSubmit="return false">
	<div class="createPostBox">
		<div class="head">
			<div class="image">
				<img src="<?php echo $row_userData['avatar']; ?>"/>
			</div>
			<div class="name">
				<?php echo $row_userData['name']; ?>
			</div>
		</div>
		<div class="textArea">
			<textarea name="textcontent" placeholder="Write here..."></textarea>
		</div>
		<div class="addedFiles">
			<div class="files">
				<div class="container">
					<div class="videoFiles"></div>
				</div>
			</div>
			<div class="files">
				<div class="container">
					<div class="photoFiles"></div>
				</div>
			</div>
			<div class="files">
				<div class="container">
					<div class="audioFiles"></div>
				</div>
			</div>
		</div>

		<!-- Audios Box -->
		<div class="audioFilesBox">
			<div class="container">
				<div class="title">
					Add songs
					<div class="close" onClick="attachAudioFiles(1)"><?php include '../../../images/svg/close.php'; ?></div>
				</div>
				<div class="content">
					<?php if ($totalRows_songsList != 0) { ?>
						<ul class="songsListBox">
							<?php $contador = -1;
								do {
								$contador = $contador + 1;
							?>
								<li class="song<?php echo $row_songsList['song'] ?>" id="song<?php echo $contador ?>">
									<div class="playPause" onClick="playTrack(<?php echo $contador ?>)">
										<div class="play" style="display:block;">
											<?php include '../../../images/svg/play.php'; ?>
										</div>
										<div class="pause" style="display:none;">
											<?php include '../../../images/svg/pause.php'; ?>
										</div>
									</div>
									<div class="text" onClick="playTrack(<?php echo $contador ?>)"><?php echo $row_songsList['title']?></div>
									<div class="duration"><?php echo $row_songsList['duration']?></div>
				                    <div class="actions" onClick='attachAudioFiles(2, <?php echo htmlspecialchars(json_encode($row_songsList), ENT_QUOTES, "UTF-8"); ?>)'>
			                            <div class="add">
			                                <?php include '../../../images/svg/add.php'; ?>
			                            </div>
			                            <div class="add added">
			                                <?php include '../../../images/svg/check.php'; ?>
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
				</div>
				<div class="buttons">
					<button onClick="attachAudioFiles(3)">DONE</button>
					<button onClick="attachAudioFiles(1)">CLOSE</button>
				</div>
			</div>
		</div>

		<!-- Photos Box -->
		<div class="photoFilesBox">
			<div class="container">
				<div class="title">
					Add photos
					<div class="close" onClick="attachPhotoFiles(1)"><?php include '../../../images/svg/close.php'; ?></div>
				</div>
				<div class="content">
					<?php if ($totalRows_photosList != 0) { ?>
						<ul class="photosListBox">
							<?php 
								$contador = - 1; 
								do { 
								$contador = $contador + 1;
							?>
								<li class="photo<?php echo $row_photosList['photo'] ?>">
									<div class="image">
										<div class="img" style="background-image: url(<?php echo $urlWeb ?>pages/user/photos/photos/thumbnails/<?php echo $row_photosList['name']?>); width: 100%; height: 100%;"></div>
									</div>
									<div class="actions" onClick='attachPhotoFiles(2, <?php echo htmlspecialchars(json_encode($row_photosList), ENT_QUOTES, "UTF-8"); ?>)'>
			                            <div class="add">
			                                <?php include '../../../images/svg/add.php'; ?>
			                            </div>
			                            <div class="add added">
			                                <?php include '../../../images/svg/check.php'; ?>
			                            </div>
				                    </div>
								</li>
							<?php } while ($row_photosList = mysql_fetch_assoc($photosList)); ?>
						</ul>
					<?php } else { ?>
						<div class="noData">
							No photos
						</div>
					<?php } ?>
				</div>
				<div class="buttons">
					<button onClick="attachPhotoFiles(3)">DONE</button>
					<button onClick="attachPhotoFiles(1)">CLOSE</button>
				</div>
			</div>
		</div>

		<!-- Videos Box -->
		<div class="videoFilesBox">
			<div class="container">
				<div class="title">
					Add videos
					<div class="close" onClick="attachVideoFiles(1)"><?php include '../../../images/svg/close.php'; ?></div>
				</div>
				<div class="content">
					<?php if ($totalRows_videosList != 0) { ?>
						<ul class="videosListBox">
							<?php 
								$contador = - 1; 
								do { 
								$contador = $contador + 1;
							?>
								<li class="video<?php echo $row_videosList['video'] ?>">
									<div class="video">
										<video>
											<source src="<?php echo $urlWeb ?>pages/user/videos/videos/thumbnails/<?php echo $row_videosList['name']?>.jpg"/>
										</video>
									</div>
									<div class="actions" onClick='attachVideoFiles(2, <?php echo htmlspecialchars(json_encode($row_videosList), ENT_QUOTES, "UTF-8"); ?>)'>
			                            <div class="add">
			                                <?php include '../../../images/svg/add.php'; ?>
			                            </div>
			                            <div class="add added">
			                                <?php include '../../../images/svg/check.php'; ?>
			                            </div>
				                    </div>
								</li>
							<?php } while ($row_videosList = mysql_fetch_assoc($videosList)); ?>
						</ul>
					<?php } else { ?>
						<div class="noData">
							No videos
						</div>
					<?php } ?>
				</div>
				<div class="buttons">
					<button onClick="attachVideoFiles(3)">DONE</button>
					<button onClick="attachVideoFiles(1)">CLOSE</button>
				</div>
			</div>
		</div>


	</div>
	<div class="buttons">
		<div class="actions">
			<div class="action" onClick="attachPhotoFiles(1)">
				<label for="photoFiles"><?php include '../../../images/svg/photos.php'; ?></label>
			</div>
			<div class="action" onClick="attachAudioFiles(1)">
				<label for="musicFiles"><?php include '../../../images/svg/music.php'; ?></label>
			</div>
			<div class="action" onClick="attachVideoFiles(1)">
				<label for="videosFiles"><?php include '../../../images/svg/videos.php'; ?></label>
			</div>
		</div>
		<button onClick="createPost(3, textcontent.value)">POST</button>
		<button onClick="createPost(2)">CLOSE</button>
	</div>
</form>
<?php mysql_free_result($userData); ?>
<?php mysql_free_result($songsList); ?>
<?php mysql_free_result($photosList); ?>
<?php mysql_free_result($videosList); ?>