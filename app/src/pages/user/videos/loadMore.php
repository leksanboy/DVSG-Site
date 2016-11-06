<?php require_once('../../../Connections/conexion.php');
	$userId = $_POST['userId'];
	$cuantity = $_POST['cuantity'];
	$_SESSION['loadMorePhotos'.$userId] = $_SESSION['loadMorePhotos'.$userId] + $cuantity;

	//User photos  --> load more
	mysql_select_db($database_conexion, $conexion);
	$query_loadMorePhotos = sprintf("SELECT f.id, v.name FROM z_photos_favorites f INNER JOIN z_photos v ON v.id = f.photo WHERE f.user = $userId ORDER BY f.date DESC LIMIT %s, 15",
	GetSQLValueString($_SESSION['loadMorePhotos'.$userId], "int"));
	$loadMorePhotos = mysql_query($query_loadMorePhotos, $conexion) or die(mysql_error());
	$row_loadMorePhotos = mysql_fetch_assoc($loadMorePhotos);
	$totalRows_loadMorePhotos = mysql_num_rows($loadMorePhotos);
?>
<?php if ($totalRows_loadMoreVideos != 0){ ?>
	<?php do { ?>
		<li class="video<?php echo $row_loadMoreVideos['id'] ?>">
			<div class="video" onclick="openVideo(1, '<?php echo $row_loadMoreVideos['name']?>', '<?php echo $row_loadMoreVideos['title']?>', '<?php echo $row_loadMoreVideos['video']?>')">
				<div class="thumb" style="background-image: url(<?php echo $urlWeb ?>pages/user/videos/videos/thumbnails/<?php echo $row_loadMoreVideos['name']?>.jpg); width: 100%; height: 150px;"></div>
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