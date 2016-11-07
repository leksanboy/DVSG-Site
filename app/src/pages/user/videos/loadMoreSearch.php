<?php require_once('../../../Connections/conexion.php');
	$userId = $_GET['userId'];
	$cuantity = $_GET['cuantity'];
	$searchValue = $_GET['searchValue'];
	$_SESSION['loadMoreSearchVideos'.$userId] = $_SESSION['loadMoreSearchVideos'.$userId] + $cuantity;

	// Load more videos
	mysql_select_db($database_conexion, $conexion);
	$query_loadMoreSearch = sprintf("SELECT * FROM z_videos WHERE title LIKE %s ORDER BY title DESC LIMIT %s, 10",
	GetSQLValueString("%" . $searchValue . "%", "text"),
	GetSQLValueString($_SESSION['loadMoreSearchVideos'.$userId], "int"));
	$loadMoreSearch = mysql_query($query_loadMoreSearch, $conexion) or die(mysql_error());
	$row_loadMoreSearch = mysql_fetch_assoc($loadMoreSearch);
	$totalRows_loadMoreSearch = mysql_num_rows($loadMoreSearch);
?>
<?php if ($totalRows_loadMoreSearch != 0){ ?>
	<?php do { ?>
		<li class="videoSearch<?php echo $row_loadMoreSearch['id'] ?>">
			<div class="video" onclick="openVideo(1, '<?php echo $row_loadMoreSearch['name']?>', '<?php echo $row_loadMoreSearch['title']?>', '<?php echo $row_loadMoreSearch['video']?>')">
				<div class="thumb" style="background-image: url(<?php echo $urlWeb ?>pages/user/videos/videos/thumbnails/<?php echo $urlWeb ?>pages/user/videos/videos/thumbnails/<?php echo substr($row_loadMoreSearch['name'], 0, -4)?>.jpg); width: 100%; height: 150px;"></div>
			</div>
			<div class="title">
				<div class="duration"><?php echo $row_loadMoreSearch['duration']?></div>
				<div class="text"><?php echo $row_loadMoreSearch['title']?></div>
				<div class="replays"><?php echo $row_loadMoreSearch['replays']?> viwes · <?php echo timeAgo($row_loadMoreSearch['time']) ?></div>
			</div>
			<?php if (isset ($_SESSION['MM_Id'])){ ?>
					<div class="actions">
						<div class="add" onClick="addVideoSearch(1, <?php echo $row_loadMoreSearch['id'] ?>)">
							<?php include("../../../images/svg/add.php"); ?>
						</div>
						<div class="add added">
							<?php include("../../../images/svg/check.php"); ?>
						</div>
					</div>
				<?php } ?>
		</li>
	<?php } while ($row_loadMoreSearch = mysql_fetch_assoc($loadMoreSearch)); ?>
<?php } ?>
<?php mysql_free_result($loadMoreSearch); ?>