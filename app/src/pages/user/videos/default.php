<?php require_once('../../../Connections/conexion.php');
	$userId = $_SESSION['MM_Id'];

	//User music  --> songsList
	mysql_select_db($database_conexion, $conexion);
	$query_songsList = sprintf("SELECT f.id, v.name, v.title, v.duration, v.replays, f.date FROM z_videos_favorites f INNER JOIN z_videos v ON v.id = f.video WHERE f.user = $userId ORDER BY f.date DESC");
	$songsList = mysql_query($query_songsList, $conexion) or die(mysql_error());
	$row_songsList = mysql_fetch_assoc($songsList);
	$totalRows_songsList = mysql_num_rows($songsList);
?>

<?php if ($totalRows_songsList != 0){ ?>
	<ul class="videosListBox">
		<?php do { ?>
			<li class="song<?php echo $row_songsList['id'] ?>">
				<video onclick="openVideo(1, '<?php echo $row_songsList['name']?>')">
					<source src="<?php echo $urlWeb ?>pages/user/videos/videos/<?php echo $row_songsList['name']?>"/>
				</video>
				<div class="title">
					<div class="duration"><?php echo $row_songsList['duration']?></div>
					<div class="text"><?php echo $row_songsList['title']?></div>
					<div class="replays"><?php echo $row_songsList['replays']?> viwes · <?php echo timeAgo($row_songsList['date']) ?></div>
				</div>
				

				<div class="actions">
					<div class="delete" onClick="deleteVideo(1, <?php echo $row_songsList['id'] ?>)">
						<?php include("../../../images/svg/clear.php"); ?>
					</div>
				</div>
				<div class="deleteBoxConfirmation" id="delete<?php echo $row_songsList['id'] ?>">
					<div class="text">Delete this video?</div>
					<div class="buttons">
						<button onClick="deleteVideo(1, <?php echo $row_songsList['id'] ?>)">NO</button>
						<button onClick="deleteVideo(2, <?php echo $row_songsList['id'] ?>)">YES</button>
					</div>
				</div>
			</li>
		<?php } while ($row_songsList = mysql_fetch_assoc($songsList)); ?>
	</ul>
<?php } else { ?>
	<div class="noData">
		No videos
	</div>
<?php } ?>
<?php mysql_free_result($songsList); ?>