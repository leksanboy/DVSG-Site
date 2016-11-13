<?php require_once('../../../Connections/conexion.php');
	$userId = $_GET['userId'];
	$cuantity = $_GET['cuantity'];
	$searchValue = $_GET['searchValue'];
	$_SESSION['loadMoreSearchFriends'.$userId] = $_SESSION['loadMoreSearchFriends'.$userId] + $cuantity;

	// Load more
	mysql_select_db($database_conexion, $conexion);
	$query_loadMoreSearch = sprintf("SELECT * 
										FROM z_users 
										WHERE name LIKE %s ORDER BY name DESC LIMIT %s, 10", 
	GetSQLValueString("%" . $searchValue . "%", "text"),
	GetSQLValueString($_SESSION['loadMoreSearchFriends'.$userId], "int"));
	$loadMoreSearch = mysql_query($query_loadMoreSearch, $conexion) or die(mysql_error());
	$row_loadMoreSearch = mysql_fetch_assoc($loadMoreSearch);
	$totalRows_loadMoreSearch = mysql_num_rows($loadMoreSearch);
?>
<?php if ($totalRows_loadMoreSearch != 0){ ?>
	<?php do { ?>
		<li id="friend<?php echo $row_loadMoreSearch['id'] ?>">
			<div class="head">
				<div class="image" onclick="userPage(<?php echo $row_loadMoreSearch['id']; ?>)">
					<img src="<?php echo userAvatar($row_loadMoreSearch['id']) ?>"/>
				</div>
				<div class="name" onclick="userPage(<?php echo $row_loadMoreSearch['id']; ?>)">
					<?php echo userName($row_loadMoreSearch['id']) ?>
				</div>
				<?php if ($row_loadMoreSearch['id'] != $userId) { ?>
					<?php if(checkFriendStatus($row_loadMoreSearch['id'], $_SESSION['MM_Id']) == 0) { ?>
						<div class="buttons">
							<button class="acceptButton" onClick="statusFriendSearch(0, <?php echo $row_loadMoreSearch['id'] ?>, <?php echo $row_loadMoreSearch['id'] ?>)">
								<?php include("../../../images/svg/add.php");?>
								Add to friends
							</button>
						</div>
					<?php } else if(checkFriendStatus($row_loadMoreSearch['id'], $_SESSION['MM_Id']) == 1) { ?>
						<div class="buttons">
							<?php if (checkIfImSender($row_loadMoreSearch['id'], $_SESSION['MM_Id']) == 'true') { ?>
								<button onClick="statusFriend(1, <?php echo $row_loadMoreSearch['id'] ?>, <?php echo $row_loadMoreSearch['id'] ?>)">
									<?php include("../../../images/svg/close.php");?>
									Cancel
								</button>
								<button onClick="statusFriend(2, <?php echo $row_loadMoreSearch['id'] ?>, <?php echo $row_loadMoreSearch['id'] ?>)">
									<?php include("../../../images/svg/check.php");?>
									Accept
								</button>
							<?php } else { ?>
								<button onClick="statusFriendSearch(1, <?php echo $row_loadMoreSearch['id'] ?>, <?php echo $row_loadMoreSearch['id'] ?>)">
									<?php include("../../../images/svg/close.php");?>
									Cancel request
								</button>
							<?php } ?>
						</div>
					<?php } else if(checkFriendStatus($row_loadMoreSearch['id'], $_SESSION['MM_Id']) == 2) { ?>
						<div class="delete" onClick="deleteFriend(1, <?php echo $row_loadMoreSearch['id'] ?>)">
							<?php include("../../../images/svg/close.php"); ?>
						</div>
						<div class="deleteBoxConfirmation" id="delete<?php echo $row_loadMoreSearch['id'] ?>">
							<div class="text">Delete from friends?</div>
							<div class="confirmation">
								<button onClick="deleteFriend(1, <?php echo $row_loadMoreSearch['id'] ?>)">NO</button>
								<button onClick="deleteFriend(2, <?php echo $row_loadMoreSearch['id'] ?>, <?php echo $row_loadMoreSearch['id'] ?>, <?php echo $_SESSION['MM_Id'] ?>)">YES</button>
							</div>
						</div>
					<?php } ?>
				<?php } ?>
			</div>
		</li>
	<?php } while ($row_loadMoreSearch = mysql_fetch_assoc($loadMoreSearch)); ?>
<?php } ?>
<?php mysql_free_result($loadMoreSearch); ?>