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
    $query_songsList = sprintf("SELECT DISTINCT f.song, m.name, m.title, m.duration FROM z_music_favorites f INNER JOIN z_music m ON m.id = f.song WHERE f.user = $userId ORDER BY f.date DESC");
    $songsList = mysql_query($query_songsList, $conexion) or die(mysql_error());
    $row_songsList = mysql_fetch_assoc($songsList);
    $totalRows_songsList = mysql_num_rows($songsList);
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
				<div class="title">Photos</div>
				<div class="container">
					<div class="photoFiles"></div>
				</div>
			</div>
			<div class="files">
				<div class="title">Songs</div>
				<div class="container">
					<div class="audioFiles"></div>
				</div>
			</div>
			<div class="files">
				<div class="title">Videos</div>
				<div class="container">
					<div class="videoFiles"></div>
				</div>
			</div>
		</div>
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
			                <?php  if (isset($_SESSION['MM_Id'])) { ?>
			                    <div class="actions" onClick="attachAudioFiles(2, <?php echo $row_songsList['song'] ?>)">
		                            <div class="add">
		                                <?php include '../../../images/svg/add.php'; ?>
		                            </div>
		                            <div class="add added">
		                                <?php include '../../../images/svg/check.php'; ?>
		                            </div>
			                    </div>
			                <?php } ?>
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
					<button onClick="attachAudioFiles(1)">DONE</button>
					<button onClick="attachAudioFiles(1)">CLOSE</button>
				</div>
			</div>
		</div>
	</div>
	<div class="buttons">
		<div class="actions">
			<div class="action">
				<input id="photoFiles" type="file" name="fileUpload[]" multiple id="fileUpload" onChange="attachPhotoFiles(1, event)" accept="image/jpeg,image/png,image/gif">
				<label for="photoFiles"><?php include '../../../images/svg/photos.php'; ?></label>
			</div>
			<div class="action" onClick="attachAudioFiles(1)">
				<label for="musicFiles"><?php include '../../../images/svg/music.php'; ?></label>
			</div>
			<div class="action">
				<label for="videosFiles"><?php include '../../../images/svg/videos.php'; ?></label>
			</div>
		</div>
		<button onClick="createPost(3, textcontent.value)">POST</button>
		<button onClick="createPost(2)">CLOSE</button>
	</div>
</form>
<?php mysql_free_result($userData); ?>
<?php mysql_free_result($songsList); ?>
