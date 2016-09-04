<?php require_once('../../../Connections/conexion.php');
	$userId = $_POST['userId'];

	//User videos
	mysql_select_db($database_conexion, $conexion);
	$query_videosList = sprintf("SELECT f.id, f.video, f.date, f.time, v.name, v.title, v.duration, v.replays FROM z_videos_favorites f INNER JOIN z_videos v ON v.id = f.video WHERE f.user = $userId ORDER BY f.date DESC");
	$videosList = mysql_query($query_videosList, $conexion) or die(mysql_error());
	$row_videosList = mysql_fetch_assoc($videosList);
	$totalRows_videosList = mysql_num_rows($videosList);
?>
<?php if ($totalRows_videosList != 0){ ?>
	<ul class="videosListBox">
		<?php 
			$contador=-1; 
			do { 
			$contador = $contador+1;
		?>
			<li class="video<?php echo $row_videosList['id'] ?>">
				<video onclick="openVideo(1, '<?php echo $row_videosList['name']?>', '<?php echo $row_videosList['title']?>', '<?php echo $row_videosList['video']?>')">
					<source src="<?php echo $urlWeb ?>pages/user/videos/videos/<?php echo $row_videosList['name']?>"/>
				</video>
				<div class="title">
					<div class="duration"><?php echo $row_videosList['duration']?></div>
					<div class="text"><?php echo $row_videosList['title']?></div>
					<div class="replays"><?php echo $row_videosList['replays']?> viwes · <?php echo timeAgo($row_videosList['time']) ?></div>
				</div>
				
				<?php  if (isset($_SESSION['MM_Id'])) { ?>
					<div class="actions">
						<?php if ($userId == $_SESSION['MM_Id']) { ?>
							<div class="delete" onClick="deleteVideo(1, <?php echo $row_videosList['id'] ?>)">
								<?php include("../../../images/svg/clear.php"); ?>
							</div>
						<?php } else { ?>
							<div class="add" onClick="addVideo(1, <?php echo $row_videosList['id'] ?>, <?php echo $row_videosList['video'] ?>)">
								<?php include("../../../images/svg/add.php"); ?>
							</div>
							<div class="add added">
								<?php include("../../../images/svg/check.php"); ?>
							</div>
						<?php } ?>
					</div>
					<div class="deleteBoxConfirmation" id="delete<?php echo $row_videosList['id'] ?>">
						<div class="text">Delete this video?</div>
						<div class="buttons">
							<button onClick="deleteVideo(1, <?php echo $row_videosList['id'] ?>)">NO</button>
							<button onClick="deleteVideo(2, <?php echo $row_videosList['id'] ?>)">YES</button>
						</div>
					</div>
				<?php } ?>
			</li>
		<?php } while ($row_videosList = mysql_fetch_assoc($videosList)); ?>
	</ul>
<?php } else { ?>
	<div class="noData">
		No videos
	</div>
<?php } ?>
<?php mysql_free_result($videosList); ?>