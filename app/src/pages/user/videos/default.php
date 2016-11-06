<?php require_once('../../../Connections/conexion.php');
	$userId = $_POST['userId'];

	//User videos
	mysql_select_db($database_conexion, $conexion);
	$query_videosList = sprintf("SELECT f.id, f.video, f.date, f.time, v.name, v.title, v.duration, v.replays FROM z_videos_favorites f INNER JOIN z_videos v ON v.id = f.video WHERE f.user = $userId ORDER BY f.date DESC LIMIT 10");
	$videosList = mysql_query($query_videosList, $conexion) or die(mysql_error());
	$row_videosList = mysql_fetch_assoc($videosList);
	$totalRows_videosList = mysql_num_rows($videosList);
?>
<?php if ($totalRows_videosList != 0){ ?>
	<ul class="videosListBox">
		<?php do { ?>
			<li class="video<?php echo $row_videosList['id'] ?>">
				<div class="video" onclick="openVideo(1, '<?php echo $row_videosList['name']?>', '<?php echo $row_videosList['title']?>', '<?php echo $row_videosList['video']?>')">
					<div class="thumb" style="background-image: url(<?php echo $urlWeb ?>pages/user/videos/videos/thumbnails/<?php echo $row_videosList['name']?>.jpg); width: 100%; height: 150px;"></div>
				</div>
				<div class="title">
					<div class="duration"><?php echo $row_videosList['duration']?></div>
					<div class="text"><?php echo $row_videosList['title']?></div>
					<div class="replays"><?php echo $row_videosList['replays']?> viwes · <?php echo timeAgo($row_videosList['time']) ?></div>
				</div>
				
				<?php  if (isset($_SESSION['MM_Id'])) { ?>
					<div class="actions">
                        <?php if ($userId == $_SESSION['MM_Id']) { ?>
                            <div class="add" onClick="deleteVideo(1, <?php echo $row_videosList['id'] ?>, <?php echo $row_videosList['video'] ?>)">
                                <?php include("../../../images/svg/close.php"); ?>
                            </div>
                            <div class="add added">
                                <?php include("../../../images/svg/add.php"); ?>
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
				<?php } ?>
			</li>
		<?php } while ($row_videosList = mysql_fetch_assoc($videosList)); ?>
	</ul>

	<?php if ($totalRows_videosList == 10){ ?>
		<div class="loadMore" onclick="loadMoreVideos();"> + LOAD MORE</div>
	<?php } ?>
<?php } else { ?>
	<div class="noData">
		No videos
	</div>
<?php } ?>
<?php mysql_free_result($videosList); ?>