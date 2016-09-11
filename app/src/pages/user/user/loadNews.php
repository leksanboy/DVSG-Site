<?php require_once '../../../Connections/conexion.php';
    $userId = $_POST['userId'];

	//User news
	mysql_select_db($database_conexion, $conexion);
	$query_newsList = sprintf("SELECT * FROM z_news WHERE user=%s ORDER BY id DESC LIMIT 99", $userId, "int");
	$newsList = mysql_query($query_newsList, $conexion) or die(mysql_error());
	$row_newsList = mysql_fetch_assoc($newsList);
	$totalRows_newsList = mysql_num_rows($newsList);
?>
<?php if ($totalRows_newsList != 0){ ?>
	<ul class="newsListBox">
		<?php do { ?>
			<li id="news<?php echo $row_newsList['id'] ?>">
				<div class="head">
					<div class="image" onclick="userPage(<?php echo $row_newsList['user']; ?>)">
						<img src="<?php echo userAvatar($row_newsList['user']); ?>"/>
					</div>
					<div class="name" onclick="userPage(<?php echo $row_newsList['user']; ?>)">
						<?php echo userName($row_newsList['user']); ?>
						<div class="date">
							<?php echo timeAgo($row_newsList['time']); ?>
						</div>
					</div>
					<?php if ($userId == $_SESSION['MM_Id']) { ?>
						<div class="delete" onClick="deleteNews(1, '<?php echo $row_newsList['id'] ?>')">
							<?php include("../../../images/svg/close.php"); ?>
						</div>
						<div class="deleteBoxConfirmation" id="delete<?php echo $row_newsList['id'] ?>">
							<div class="text">Delete this post?</div>
							<div class="buttons">
								<button onClick="deleteNews(1, <?php echo $row_newsList['id'] ?>)">NO</button>
								<button onClick="deleteNews(2, <?php echo $row_newsList['id'] ?>)">YES</button>
							</div>
						</div>
					<?php } ?>
				</div>

				<div class="body">
					<?php echo $row_newsList['content'] ?>
				</div>

				<div class="foot"></div>
			</li>
		<?php } while ($row_newsList = mysql_fetch_assoc($newsList)); ?>
	</ul>
<?php } else { ?>
	<div class="noData">
		No news
	</div>
<?php } ?>
<?php mysql_free_result($newsList); ?>