<?php require_once('../../../Connections/conexion.php');
	$titleValue = $_POST['titleValue'];

	//All music --> songsListSearch
	mysql_select_db($database_conexion, $conexion);
	$query_songsListSearch = sprintf("SELECT * FROM z_videos WHERE title LIKE %s ORDER BY title DESC", 
		GetSQLValueString("%" . $titleValue . "%", "text"));
	$songsListSearch = mysql_query($query_songsListSearch, $conexion) or die(mysql_error());
	$row_songsListSearch = mysql_fetch_assoc($songsListSearch);
	$totalRows_songsListSearch = mysql_num_rows($songsListSearch);
?>

<?php if ($totalRows_songsListSearch != 0){ ?>
	<ul class="videosListBox">
		<?php do { ?>
			<li class="song<?php echo $row_songsListSearch['id'] ?>">
				<video>
					<source src="<?php echo $urlWeb ?>pages/user/videos/videos/<?php echo $row_songsListSearch['name']?>"/>
				</video>
				<div class="title">
					<div class="duration"><?php echo $row_songsListSearch['duration']?></div>
					<div class="text"><?php echo $row_songsListSearch['title']?></div>
					<div class="replays"><?php echo $row_songsListSearch['replays']?> viwes · <?php echo timeAgo($row_songsListSearch['date']) ?></div>
				</div>
				
				<div class="actions">
					<div class="add" onClick="addVideo(<?php echo $row_songsListSearch['id'] ?>)">
						<?php include("../../../images/svg/add.php"); ?>
					</div>
					<div class="added">
						<?php include("../../../images/svg/check.php"); ?>
					</div>
				</div>
			</li>
		<?php } while ($row_songsListSearch = mysql_fetch_assoc($songsListSearch)); ?>
	</ul>
<?php } else { ?>
	<div class="noData">
		No result videos
	</div>
<?php } ?>
<?php mysql_free_result($songsListSearch); ?>