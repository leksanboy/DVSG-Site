<?php require_once('../../../Connections/conexion.php');
	$titleValue = $_POST['titleValue'];
	$userId = $_SESSION['MM_Id'];

	//Search - All users
	mysql_select_db($database_conexion, $conexion);
	$query_friendsListSearch = sprintf("SELECT * FROM z_users WHERE name LIKE %s ORDER BY name DESC", 
		GetSQLValueString("%" . $titleValue . "%", "text"));
	$friendsListSearch = mysql_query($query_friendsListSearch, $conexion) or die(mysql_error());
	$row_friendsListSearch = mysql_fetch_assoc($friendsListSearch);
	$totalRows_friendsListSearch = mysql_num_rows($friendsListSearch);
?>

<?php if ($totalRows_friendsListSearch != 0){ ?>
	<?php do { ?>
		<div class="friendBox" id="friend<?php echo $row_friendsListSearch['id'] ?>">
			<div class="head">
				<div class="image" onclick="userPage(<?php echo $row_friendsListSearch['id']; ?>)">
					<img src="<?php echo userAvatar($row_friendsListSearch['id']) ?>"/>
				</div>
				<div class="name" onclick="userPage(<?php echo $row_friendsListSearch['id']; ?>)">
					<?php echo userName($row_friendsListSearch['id']) ?>
				</div>
				<?php if ($row_friendsListSearch['id'] != $userId) { ?>
					<?php if(checkFriendStatus($row_friendsListSearch['id'], $_SESSION['MM_Id']) == 0) { ?>
						<div class="buttons">
							<button class="acceptButton" onClick="statusFriendSearch(0, <?php echo $row_friendsListSearch['id'] ?>, <?php echo $row_friendsListSearch['id'] ?>)">
								<?php include("../../../images/svg/add.php");?>
								Add to friends
							</button>
						</div>
					<?php } else if(checkFriendStatus($row_friendsListSearch['id'], $_SESSION['MM_Id']) == 1) { ?>
						<div class="buttons">
							<?php if (checkIfImSender($row_friendsListSearch['id'], $_SESSION['MM_Id']) == 'true') { ?>
								<button onClick="statusFriend(1, <?php echo $row_friendsListSearch['id'] ?>, <?php echo $row_friendsListSearch['id'] ?>)">
									<?php include("../../../images/svg/close.php");?>
									Cancel
								</button>
								<button onClick="statusFriend(2, <?php echo $row_friendsListSearch['id'] ?>, <?php echo $row_friendsListSearch['id'] ?>)">
									<?php include("../../../images/svg/check.php");?>
									Accept
								</button>
							<?php } else { ?>
								<button onClick="statusFriendSearch(1, <?php echo $row_friendsListSearch['id'] ?>, <?php echo $row_friendsListSearch['id'] ?>)">
									<?php include("../../../images/svg/close.php");?>
									Cancel request
								</button>
							<?php } ?>
						</div>
					<?php } else if(checkFriendStatus($row_friendsListSearch['id'], $_SESSION['MM_Id']) == 2) { ?>
						<div class="delete" onClick="deleteFriend(1, <?php echo $row_friendsListSearch['id'] ?>)">
							<?php include("../../../images/svg/close.php"); ?>
						</div>
						<div class="deleteBoxConfirmation" id="delete<?php echo $row_friendsListSearch['id'] ?>">
							<div class="text">Delete from friends?</div>
							<div class="confirmation">
								<button onClick="deleteFriend(1, <?php echo $row_friendsListSearch['id'] ?>)">NO</button>
								<button onClick="deleteFriend(2, <?php echo $row_friendsListSearch['id'] ?>, <?php echo $row_friendsListSearch['id'] ?>, <?php echo $_SESSION['MM_Id'] ?>)">YES</button>
							</div>
						</div>
					<?php } ?>
				<?php } ?>
			</div>
		</div>
	<?php } while ($row_friendsListSearch = mysql_fetch_assoc($friendsListSearch)); ?>
<?php } else { ?>
	<div class="noData">
		No results
	</div>
<?php } ?>
<?php mysql_free_result($friendsListSearch); ?>