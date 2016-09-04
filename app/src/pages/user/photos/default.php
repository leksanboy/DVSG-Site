<?php require_once('../../../Connections/conexion.php');
	$userId = $_POST['userId'];

	//User photos  --> photosList
	mysql_select_db($database_conexion, $conexion);
	$query_photosList = sprintf("SELECT f.id, v.name FROM z_photos_favorites f INNER JOIN z_photos v ON v.id = f.photo WHERE f.user = $userId ORDER BY f.date DESC");
	$photosList = mysql_query($query_photosList, $conexion) or die(mysql_error());
	$row_photosList = mysql_fetch_assoc($photosList);
	$totalRows_photosList = mysql_num_rows($photosList);
?>

<?php if ($totalRows_photosList != 0){ ?>
	<ul class="photosListBox">
		<?php 
			$contador = - 1; 
			do { 
			$contador = $contador + 1;
		?>
			<li class="photo<?php echo $row_photosList['id'] ?>">
				<div class="image" onClick="openPhoto(1, <?php echo $contador ?>, <?php echo $row_photosList['id'] ?>)">
					<div class="img" style="background-image: url(<?php echo $urlWeb ?>pages/user/photos/photos/<?php echo $row_photosList['name']?>); width: 100%; height: 100%;"></div>
				</div>
				<?php if ($userId == $_SESSION['MM_Id']) { ?>
					<div class="actions">
						<div class="delete" onClick="deletePhoto(1, <?php echo $row_photosList['id'] ?>)">
							<?php include("../../../images/svg/clear.php"); ?>
						</div>
					</div>
					<div class="deleteBoxConfirmation" id="delete<?php echo $row_photosList['id'] ?>">
						<div class="text">Delete this photo?</div>
						<div class="buttons">
							<button onClick="deletePhoto(1, <?php echo $row_photosList['id'] ?>)">NO</button>
							<button onClick="deletePhoto(2, <?php echo $row_photosList['id'] ?>)">YES</button>
						</div>
					</div>
				<?php } ?>
			</li>
		<?php } while ($row_photosList = mysql_fetch_assoc($photosList)); ?>
	</ul>
<?php } else { ?>
	<div class="noData">
		No photos
	</div>
<?php } ?>
<?php mysql_free_result($photosList); ?>