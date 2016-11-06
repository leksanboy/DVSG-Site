<?php require_once('../../../Connections/conexion.php');
	$userId = $_POST['userId'];
	$cuantity = $_POST['cuantity'];
	$_SESSION['loadMoreVideos'.$userId] = $_SESSION['loadMoreVideos'.$userId] + $cuantity;

	//User videos
	mysql_select_db($database_conexion, $conexion);
	$query_videosList = sprintf("SELECT f.id, f.video, f.date, f.time, v.name, v.title, v.duration, v.replays FROM z_videos_favorites f INNER JOIN z_videos v ON v.id = f.video WHERE f.user = $userId ORDER BY f.date DESC LIMIT %s, 10",
	GetSQLValueString($_SESSION['loadMoreVideos'.$userId], "int"));
	$videosList = mysql_query($query_videosList, $conexion) or die(mysql_error());
	$row_videosList = mysql_fetch_assoc($videosList);
	$totalRows_videosList = mysql_num_rows($videosList);
?>
<?php if ($totalRows_loadMorePhotos != 0){ ?>
	<?php do { 
		$_SESSION['counterPhotos'.$userId] = $_SESSION['counterPhotos'.$userId] + 1;
	?>
		<li class="photo<?php echo $row_loadMorePhotos['id'] ?>">
			<div class="image" onClick="openPhoto(1, <?php echo $_SESSION['counterPhotos'.$userId] ?>, <?php echo $row_loadMorePhotos['id'] ?>)">
				<div class="img" style="background-image: url(<?php echo $urlWeb ?>pages/user/photos/photos/<?php echo $row_loadMorePhotos['name']?>); width: 100%; height: 150px;"></div>
			</div>
			<?php if ($userId == $_SESSION['MM_Id']) { ?>
				<div class="actions">
					<div class="add" onClick="deletePhoto(1, <?php echo $row_photosList['id'] ?>)">
                        <?php include("../../../images/svg/close.php"); ?>
                    </div>
                    <div class="add added">
                        <?php include("../../../images/svg/add.php"); ?>
                    </div>
				</div>
			<?php } ?>
		</li>
	<?php } while ($row_loadMorePhotos = mysql_fetch_assoc($loadMorePhotos)); ?>
<?php } ?>
<?php mysql_free_result($loadMorePhotos); ?>