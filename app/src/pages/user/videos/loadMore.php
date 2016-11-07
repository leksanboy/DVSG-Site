<?php require_once('../../../Connections/conexion.php');
	$userId = $_GET['userId'];
	$cuantity = $_GET['cuantity'];
	$_SESSION['loadMoreVideos'.$userId] = $_SESSION['loadMoreVideos'.$userId] + $cuantity;

	// Load more videos
	mysql_select_db($database_conexion, $conexion);
	$query_loadMoreVideos = sprintf("SELECT f.id, f.video, f.date, f.time, v.name, v.title, v.duration, v.replays FROM z_videos_favorites f INNER JOIN z_videos v ON v.id = f.video WHERE f.user = $userId AND f.is_deleted = 0 ORDER BY f.date DESC LIMIT %s, 10",
	GetSQLValueString($_SESSION['loadMoreVideos'.$userId], "int"));
	$loadMoreVideos = mysql_query($query_loadMoreVideos, $conexion) or die(mysql_error());
	$row_loadMoreVideos = mysql_fetch_assoc($loadMoreVideos);
	$totalRows_loadMoreVideos = mysql_num_rows($loadMoreVideos);
?>
<?php if ($totalRows_loadMoreVideos != 0){ ?>
	<?php do { ?>
		<li class="video<?php echo $row_loadMoreVideos['id'] ?>">
			<div class="video" onclick="openVideo(1, '<?php echo $row_loadMoreVideos['name']?>', '<?php echo $row_loadMoreVideos['title']?>', '<?php echo $row_loadMoreVideos['video']?>')">
				<div class="thumb" style="background-image: url(<?php echo $urlWeb ?>pages/user/videos/videos/thumbnails/<?php echo $urlWeb ?>pages/user/videos/videos/thumbnails/<?php echo substr($row_loadMoreVideos['name'], 0, -4)?>.jpg); width: 100%; height: 150px;"></div>
			</div>
			<div class="title">
				<div class="duration"><?php echo $row_loadMoreVideos['duration']?></div>
				<div class="text"><?php echo $row_loadMoreVideos['title']?></div>
				<div class="replays"><?php echo $row_loadMoreVideos['replays']?> viwes · <?php echo timeAgo($row_loadMoreVideos['time']) ?></div>
			</div>
			
			<?php  if (isset($_SESSION['MM_Id'])) { ?>
				<div class="actions">
                    <?php if ($userId == $_SESSION['MM_Id']) { ?>
                        <div class="add" onClick="deleteVideo(1, <?php echo $row_loadMoreVideos['id'] ?>, <?php echo $row_loadMoreVideos['video'] ?>)">
                            <?php include("../../../images/svg/close.php"); ?>
                        </div>
                        <div class="add added">
                            <?php include("../../../images/svg/add.php"); ?>
                        </div>
                    <?php } else { ?>
                        <div class="add" onClick="addVideo(1, <?php echo $row_loadMoreVideos['id'] ?>, <?php echo $row_loadMoreVideos['video'] ?>)">
                            <?php include("../../../images/svg/add.php"); ?>
                        </div>
                        <div class="add added">
                            <?php include("../../../images/svg/check.php"); ?>
                        </div>
                    <?php } ?>
				</div>
			<?php } ?>
		</li>
	<?php } while ($row_loadMoreVideos = mysql_fetch_assoc($loadMoreVideos)); ?>
<?php } ?>
<?php mysql_free_result($loadMoreVideos); ?>