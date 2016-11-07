<?php require_once('../../../Connections/conexion.php');
	$userId = $_GET['userId'];

	//Default data for LoadMore
	$_SESSION['loadMorePhotos'.$userId] = 0;

	//User photos  --> photosList
	mysql_select_db($database_conexion, $conexion);
	$query_photosList = sprintf("SELECT f.id, v.name FROM z_photos_favorites f INNER JOIN z_photos v ON v.id = f.photo WHERE f.user = $userId AND f.is_deleted = 0 ORDER BY f.date DESC LIMIT 15");
	$photosList = mysql_query($query_photosList, $conexion) or die(mysql_error());
	$row_photosList = mysql_fetch_assoc($photosList);
	$totalRows_photosList = mysql_num_rows($photosList);
?>
<?php if ($totalRows_photosList != 0){ ?>
	<ul class="photosListBox">
		<?php 
			$_SESSION['counterPhotos'.$userId] = - 1; 
			do { 
			$_SESSION['counterPhotos'.$userId] = $_SESSION['counterPhotos'.$userId] + 1;
		?>
			<li class="photo<?php echo $row_photosList['id'] ?>">
				<div class="image" onClick="openPhoto(1, <?php echo $_SESSION['counterPhotos'.$userId] ?>, <?php echo $row_photosList['id'] ?>)">
					<div class="img" style="background-image: url(<?php echo $urlWeb ?>pages/user/photos/photos/thumbnails/<?php echo $row_photosList['name']?>); width: 100%; height: 100%;"></div>
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
		<?php } while ($row_photosList = mysql_fetch_assoc($photosList)); ?>
	</ul>

	<?php if ($totalRows_photosList == 15){ ?>
		<div class="loadMore" onclick="loadMore();"> + LOAD MORE</div>
	<?php } ?>
<?php } else { ?>
	<div class="noData">
		No photos
	</div>
<?php } ?>
<?php mysql_free_result($photosList); ?>