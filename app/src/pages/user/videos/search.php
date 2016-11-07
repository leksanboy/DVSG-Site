<?php require_once('../../../Connections/conexion.php');
	$searchValue = $_GET['searchValue'];
	$userId = $_GET['userId'];

	//Default data for LoadMoreSearch
	$_SESSION['loadMoreSearchVideos'.$userId] = 0;

	//SEARCH
	mysql_select_db($database_conexion, $conexion);
	$query_searchData = sprintf("SELECT * FROM z_videos WHERE title LIKE %s ORDER BY title DESC LIMIT 10", 
	GetSQLValueString("%" . $searchValue . "%", "text"));
	$searchData = mysql_query($query_searchData, $conexion) or die(mysql_error());
	$row_searchData = mysql_fetch_assoc($searchData);
	$totalRows_searchData = mysql_num_rows($searchData);
?>
<?php if ($totalRows_searchData != 0){ ?>
	<ul class="videosListBox">
		<?php do { ?>
			<li class="videoSearch<?php echo $row_searchData['id'] ?>">
				<div class="video" onclick="openVideo(1, '<?php echo $row_searchData['name']?>', '<?php echo $row_searchData['title']?>', '<?php echo $row_searchData['video']?>')">
					<div class="thumb" style="background-image: url(<?php echo $urlWeb ?>pages/user/videos/videos/thumbnails/<?php echo substr($row_searchData['name'], 0, -4)?>.jpg); width: 100%; height: 150px;"></div>
				</div>
				<div class="title">
					<div class="duration"><?php echo $row_searchData['duration']?></div>
					<div class="text"><?php echo $row_searchData['title']?></div>
					<div class="replays"><?php echo $row_searchData['replays']?> viwes · <?php echo timeAgo($row_searchData['date']) ?></div>
				</div>
				<?php if (isset ($_SESSION['MM_Id'])){ ?>
					<div class="actions">
						<div class="add" onClick="addVideoSearch(1, <?php echo $row_searchData['id'] ?>)">
							<?php include("../../../images/svg/add.php"); ?>
						</div>
						<div class="add added">
							<?php include("../../../images/svg/check.php"); ?>
						</div>
					</div>
				<?php } ?>
			</li>
		<?php } while ($row_searchData = mysql_fetch_assoc($searchData)); ?>
	</ul>

	<?php if ($totalRows_searchData == 10){ ?>
		<div class="loadMore" onclick="loadMoreSearch();"> + LOAD MORE</div>
	<?php } ?>
<?php } else { ?>
	<div class="noData">
		No result videos
	</div>
<?php } ?>
<?php mysql_free_result($searchData); ?>