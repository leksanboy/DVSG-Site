<?php require_once('../../../../Connections/conexion.php');
	$userId = $_GET['userId'];
	
	//My friends
	$query_myFriends = sprintf("SELECT * FROM z_friends WHERE receiver = %s AND status = 2 OR sender = %s AND status = 2",
	GetSQLValueString($userId,"int"),
	GetSQLValueString($userId,"int"));
	$myFriends = mysql_query($query_myFriends, $conexion) or die(mysql_error());
	$row_myFriends = mysql_fetch_assoc($myFriends);
	$totalRows_myFriends = mysql_num_rows($myFriends);
?>
<?php if ($totalRows_myFriends == 0){ ?>
	<center>Search a friend</center>
<?php } else { ?>
	<?php do { 
		if ($row_myFriends['sender'] == $userId){ 
			$receiver = $row_myFriends['receiver'];
		} else if ($row_myFriends['receiver'] == $userId){ 
			$receiver = $row_myFriends['sender'];
		} ?>

		<div class="friendBox" id="friend<?php echo $row_myFriends['id'] ?>">
			<div class="head">
				<div class="image" onclick="userPage(<?php echo $receiver ?>)">
					<img src="<?php echo userAvatar($receiver) ?>"/>
				</div>
				<div class="name" onclick="userPage(<?php echo $receiver ?>)">
					<?php echo userName($receiver) ?>
				</div>
				<?php if ($userId == $_SESSION['MM_Id']) { ?>
					<div class="delete" onClick="deleteFriend(1, <?php echo $row_myFriends['id'] ?>)">
						<?php include("../../../../images/svg/close.php"); ?>
					</div>
					<div class="deleteBoxConfirmation" id="delete<?php echo $row_myFriends['id'] ?>">
						<div class="text">Delete from friends?</div>
						<div class="buttons">
							<button onClick="deleteFriend(1, <?php echo $row_myFriends['id'] ?>)">NO</button>
							<button onClick="deleteFriend(2, <?php echo $row_myFriends['id'] ?>, <?php echo $receiver ?>, <?php echo $_SESSION['MM_Id'] ?>)">YES</button>
						</div>
					</div>
				<?php } else { 
					if ($receiver != $_SESSION['MM_Id']) {
						if(checkFriendStatus($row_myFriends['id'], $_SESSION['MM_Id']) == 0){ ?>
							<div class="buttons">
								<button onClick="statusFriendOtherUser(0, <?php echo $receiver ?>, <?php echo $row_myFriends['id'] ?>)">
									<?php include("../../../../images/svg/add.php");?>
									Add to friends
								</button>
							</div>
						<?php } else { ?>
							<div class="buttons">
								<button onClick="statusFriendOtherUser(1, <?php echo $receiver ?>, <?php echo $row_myFriends['id'] ?>)">
									<?php include("../../../../images/svg/close.php");?>
									Cancel request
								</button>
							</div>
						<?php }
					}
				} ?>
			</div>
		</div>
	<?php } while ($row_myFriends = mysql_fetch_assoc($myFriends)); ?>
<?php } ?>
<?php mysql_free_result($myFriends); ?>