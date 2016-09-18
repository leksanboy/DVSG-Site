<?php require_once '../../../Connections/conexion.php';
    $userId = $_POST['userId'];

	//User news
	mysql_select_db($database_conexion, $conexion);
	$query_newsList = sprintf("SELECT * FROM z_news WHERE user=%s ORDER BY id DESC LIMIT 99", $userId, "int");
	$newsList = mysql_query($query_newsList, $conexion) or die(mysql_error());
	$row_newsList = mysql_fetch_assoc($newsList);
	$totalRows_newsList = mysql_num_rows($newsList);
?>
<?php if ($totalRows_newsList != 0){ ?>
	<ul class="newsListBox">
		<?php do { ?>
			<li id="news<?php echo $row_newsList['id'] ?>">
				<div class="head">
					<div class="image" onclick="userPage(<?php echo $row_newsList['user']; ?>)">
						<img src="<?php echo userAvatar($row_newsList['user']); ?>"/>
					</div>
					<div class="name" onclick="userPage(<?php echo $row_newsList['user']; ?>)">
						<?php echo userName($row_newsList['user']); ?>
						<div class="date">
							<?php echo timeAgo($row_newsList['time']); ?>
						</div>
					</div>
					<?php if ($userId == $_SESSION['MM_Id']) { ?>
						<div class="delete" onClick="deleteNews(1, '<?php echo $row_newsList['id'] ?>')">
							<?php include("../../../images/svg/close.php"); ?>
						</div>
						<div class="deleteBoxConfirmation" id="delete<?php echo $row_newsList['id'] ?>">
							<div class="text">Delete this post?</div>
							<div class="buttons">
								<button onClick="deleteNews(1, <?php echo $row_newsList['id'] ?>)">NO</button>
								<button onClick="deleteNews(2, <?php echo $row_newsList['id'] ?>)">YES</button>
							</div>
						</div>
					<?php } ?>
				</div>

				<div class="body">
					<?php echo $row_newsList['content'] ?>
					<br>

					<?php
						$postId = $row_newsList['id'];
						
						//Files
						mysql_select_db($database_conexion, $conexion);
				        $query_newsFiles = sprintf("SELECT * FROM z_news_files WHERE post=%s ORDER BY id DESC", $postId, "int");
				        $newsFiles = mysql_query($query_newsFiles, $conexion) or die(mysql_error());
				        $row_newsFiles = mysql_fetch_assoc($newsFiles);
				        $totalRows_newsFiles = mysql_num_rows($newsFiles);
					?>
					<?php
						mysql_data_seek( $newsFiles, 0 );
						while($row = mysql_fetch_array( $newsFiles )){
							if ($row['type'] == 'video') {
								echo '<br>VID:'.$row['id'];
							}
						}

						echo '<br>';

						?>

						<ul class="photosListBoxNews">
							<?php
								mysql_data_seek( $newsFiles, 0 );
								while($row = mysql_fetch_array( $newsFiles )){
									if ($row['type'] == 'photo') { ?>
										<li>
											<div class="image">
												<img src="<?php echo $urlWeb ?>pages/user/photos/photos/<?php echo $row['name']?>"/>
											</div>
										</li>
									<?php }
								}
							?>
						</ul>


						<ul class="songsListBoxNews">
							<?php
								mysql_data_seek( $newsFiles, 0 );
								while($row = mysql_fetch_array( $newsFiles )){
									if ($row['type'] == 'audio') { ?>
										<li class="song<?php echo $row['id'] ?>" id="song<?php echo $contador ?>">
											<div class="playPause" onClick="playTrack(<?php echo $contador ?>)">
												<div class="play" style="display:block;">
													<?php include '../../../images/svg/play.php'; ?>
												</div>
												<div class="pause" style="display:none;">
													<?php include '../../../images/svg/pause.php'; ?>
												</div>
											</div>
											<div class="text" onClick="playTrack(<?php echo $contador ?>)"><?php echo $row['title'] ?></div>
											<div class="duration"><?php echo $row['duration'] ?></div>
										</li>
									<?php }
								}
							?>
						</ul>
					<?php mysql_free_result($newsFiles);?>
				</div>

				<div class="foot">
					<div class="analytics">
						<div class='comments'>
							<?php include('../../../images/svg/comments.php'); ?>
							<span class='count'>01</span>
						</div>
						<div class='likes' <?php  if (isset($_SESSION['MM_Id'])) { ?> onClick='like(<?php echo $photoId ?>)' <?php } ?>>
							<span class='like'>
								<?php if (checkLikeUserPhoto($_SESSION['MM_Id'], $photoId) == true ){ ?>
									<?php include('../../../images/svg/unlike.php'); ?>
								<?php } else {?>
									<?php include('../../../images/svg/like.php'); ?>
								<?php } ?>
							</span>
							<span class='count'>02</span>
						</div>
			    	</div>
				</div>
			</li>
		<?php } while ($row_newsList = mysql_fetch_assoc($newsList)); ?>
	</ul>
<?php } else { ?>
	<div class="noData">
		No news
	</div>
<?php } ?>
<?php mysql_free_result($newsList); ?>