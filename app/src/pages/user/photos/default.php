<?php require_once('../../../Connections/conexion.php');
	$userId = $_SESSION['MM_Id'];

	//User photos  --> photosList
	mysql_select_db($database_conexion, $conexion);
	$query_photosList = sprintf("SELECT * FROM z_photos WHERE user = $userId ORDER BY z_photos.date DESC");
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
			<li class="song<?php echo $row_photosList['id'] ?>">
				<div class="image" onClick="openPhoto(1, <?php echo $contador ?>, <?php echo $row_photosList['id'] ?>)">
					<div class="img" style="background-image: url(<?php echo $urlWeb ?>pages/user/photos/photos/<?php echo $row_photosList['name']?>); width: 100%; height: 100%;"></div>
				</div>
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
			</li>
		<?php } while ($row_photosList = mysql_fetch_assoc($photosList)); ?>
	</ul>
<?php } else { ?>
	<div class="noData">
		No photos
	</div>
<?php } ?>
<?php mysql_free_result($photosList); ?>