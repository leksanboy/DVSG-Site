<?php require_once('../../../Connections/conexion.php');
	$searchValue = $_GET['searchValue'];
	$userId = $_GET['userId'];

	//Default data for LoadMoreSearch
	$_SESSION['loadMoreSearchFriends'.$userId] = 0;

	//Search
	mysql_select_db($database_conexion, $conexion);
	$query_searchData = sprintf("SELECT * 
										FROM z_users 
										WHERE name LIKE %s ORDER BY name DESC LIMIT 10", 
	GetSQLValueString("%" . $searchValue . "%", "text"));
	$searchData = mysql_query($query_searchData, $conexion) or die(mysql_error());
	$row_searchData = mysql_fetch_assoc($searchData);
	$totalRows_searchData = mysql_num_rows($searchData);
?>
<?php if ($totalRows_searchData != 0){ ?>
	<ul class="friendsListBox">
		<?php do { ?>
			<li id="friend<?php echo $row_searchData['id'] ?>">
				<div class="head">
					<div class="image" onclick="userPage(<?php echo $row_searchData['id']; ?>)">
						<img src="<?php echo userAvatar($row_searchData['id']) ?>"/>
					</div>
					<div class="name" onclick="userPage(<?php echo $row_searchData['id']; ?>)">
						<?php echo userName($row_searchData['id']) ?>
					</div>
					<?php if ($row_searchData['id'] != $userId) { ?>
						<?php if(checkFriendStatus($row_searchData['id'], $_SESSION['MM_Id']) == 0) { ?>
							<div class="buttons">
								<button class="acceptButton" onClick="statusFriendSearch(0, <?php echo $row_searchData['id'] ?>, <?php echo $row_searchData['id'] ?>)">
									<?php include("../../../images/svg/add.php");?>
									Add to friends
								</button>
							</div>
						<?php } else if(checkFriendStatus($row_searchData['id'], $_SESSION['MM_Id']) == 1) { ?>
							<div class="buttons">
								<?php if (checkIfImSender($row_searchData['id'], $_SESSION['MM_Id']) == 'true') { ?>
									<button onClick="statusFriend(1, <?php echo $row_searchData['id'] ?>, <?php echo $row_searchData['id'] ?>)">
										<?php include("../../../images/svg/close.php");?>
										Cancel
									</button>
									<button onClick="statusFriend(2, <?php echo $row_searchData['id'] ?>, <?php echo $row_searchData['id'] ?>)">
										<?php include("../../../images/svg/check.php");?>
										Accept
									</button>
								<?php } else { ?>
									<button onClick="statusFriendSearch(1, <?php echo $row_searchData['id'] ?>, <?php echo $row_searchData['id'] ?>)">
										<?php include("../../../images/svg/close.php");?>
										Cancel request
									</button>
								<?php } ?>
							</div>
						<?php } else if(checkFriendStatus($row_searchData['id'], $_SESSION['MM_Id']) == 2) { ?>
							<div class="delete" onClick="deleteFriend(1, <?php echo $row_searchData['id'] ?>)">
								<?php include("../../../images/svg/close.php"); ?>
							</div>
							<div class="deleteBoxConfirmation" id="delete<?php echo $row_searchData['id'] ?>">
								<div class="text">Delete from friends?</div>
								<div class="confirmation">
									<button onClick="deleteFriend(1, <?php echo $row_searchData['id'] ?>)">NO</button>
									<button onClick="deleteFriend(2, <?php echo $row_searchData['id'] ?>, <?php echo $row_searchData['id'] ?>, <?php echo $_SESSION['MM_Id'] ?>)">YES</button>
								</div>
							</div>
						<?php } ?>
					<?php } ?>
				</div>
			</li>
		<?php } while ($row_searchData = mysql_fetch_assoc($searchData)); ?>
	</ul>
	
	<?php if ($totalRows_searchData == 10){ ?>
		<div class="loadMore" onclick="loadMoreSearch();"> + LOAD MORE</div>
	<?php } ?>
<?php } else { ?>
	<div class="noData">
		No results
	</div>
<?php } ?>
<?php mysql_free_result($searchData); ?>