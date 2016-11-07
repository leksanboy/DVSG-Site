<?php require_once('../../../Connections/conexion.php');
	$userId = $_GET['userId'];
	$cuantity = $_GET['cuantity'];
	$_SESSION['loadMorePhotos'.$userId] = $_SESSION['loadMorePhotos'.$userId] + $cuantity;

	//loadMorePhotos
	mysql_select_db($database_conexion, $conexion);
	$query_loadMorePhotos = sprintf("SELECT f.id, v.name FROM z_photos_favorites f INNER JOIN z_photos v ON v.id = f.photo WHERE f.user = $userId AND f.is_deleted = 0 ORDER BY f.date DESC LIMIT %s, 15",
	GetSQLValueString($_SESSION['loadMorePhotos'.$userId], "int"));
	$loadMorePhotos = mysql_query($query_loadMorePhotos, $conexion) or die(mysql_error());
	$row_loadMorePhotos = mysql_fetch_assoc($loadMorePhotos);
	$totalRows_loadMorePhotos = mysql_num_rows($loadMorePhotos);
?>
<?php if ($totalRows_loadMorePhotos != 0){ ?>
	<?php do { 
		$_SESSION['counterPhotos'.$userId] = $_SESSION['counterPhotos'.$userId] + 1;
	?>
		<li class="photo<?php echo $row_loadMorePhotos['id'] ?>">
			<div class="image" onClick="openPhoto(1, <?php echo $_SESSION['counterPhotos'.$userId] ?>, <?php echo $row_loadMorePhotos['id'] ?>)">
				<div class="img" style="background-image: url(<?php echo $urlWeb ?>pages/user/photos/photos/thumbnails/<?php echo $row_loadMorePhotos['name']?>); width: 100%; height: 100%;"></div>
			</div>
			<?php if ($userId == $_SESSION['MM_Id']) { ?>
				<div class="actions">
					<div class="add" onClick="deletePhoto(1, <?php echo $row_loadMorePhotos['id'] ?>)">
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