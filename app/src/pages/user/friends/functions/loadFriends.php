<?php require_once('../../../../Connections/conexion.php');
	$userId = $_GET['userId'];
	
	//My friends
	$query_friendsList = sprintf("SELECT * 
									FROM z_friends 
									WHERE receiver = %s AND status = 2 OR sender = %s AND status = 2",
	GetSQLValueString($userId,"int"),
	GetSQLValueString($userId,"int"));
	$friendsList = mysql_query($query_friendsList, $conexion) or die(mysql_error());
	$row_friendsList = mysql_fetch_assoc($friendsList);
	$totalRows_friendsList = mysql_num_rows($friendsList);
?>
<?php if ($totalRows_friendsList != 0){ ?>
	<ul class="friendsListBox">
		<?php do { 
		if ($row_friendsList['sender'] == $userId){ 
			$receiver = $row_friendsList['receiver'];
		} else if ($row_friendsList['receiver'] == $userId){ 
			$receiver = $row_friendsList['sender'];
		} ?>
			<li id="friend<?php echo $row_friendsList['id'] ?>">
				<div class="head">
					<div class="image" onclick="userPage(<?php echo $receiver ?>)">
						<img src="<?php echo userAvatar($receiver) ?>"/>
					</div>
					<div class="name" onclick="userPage(<?php echo $receiver ?>)">
						<?php echo userName($receiver) ?>
					</div>
					<?php if ($userId == $_SESSION['MM_Id']) { ?>
						<div class="delete" onClick="deleteFriend(1, <?php echo $row_friendsList['id'] ?>)">
							<?php include("../../../../images/svg/close.php"); ?>
						</div>
						<div class="deleteBoxConfirmation" id="delete<?php echo $row_friendsList['id'] ?>">
							<div class="text">Delete from friends?</div>
							<div class="confirmation">
								<button onClick="deleteFriend(1, <?php echo $row_friendsList['id'] ?>)">NO</button>
								<button onClick="deleteFriend(2, <?php echo $row_friendsList['id'] ?>, <?php echo $receiver ?>, <?php echo $_SESSION['MM_Id'] ?>)">YES</button>
							</div>
						</div>
					<?php } else { 
						if ($receiver != $_SESSION['MM_Id']) {
							if(checkFriendStatus($row_friendsList['id'], $_SESSION['MM_Id']) == 0){ ?>
								<div class="buttons">
									<button onClick="statusFriendOtherUser(0, <?php echo $receiver ?>, <?php echo $row_friendsList['id'] ?>)">
										<?php include("../../../../images/svg/add.php");?>
										Add to friends
									</button>
								</div>
							<?php } else { ?>
								<div class="buttons">
									<button onClick="statusFriendOtherUser(1, <?php echo $receiver ?>, <?php echo $row_friendsList['id'] ?>)">
										<?php include("../../../../images/svg/close.php");?>
										Cancel request
									</button>
								</div>
							<?php }
						}
					} ?>
				</div>
			</li>
		<?php } while ($row_friendsList = mysql_fetch_assoc($friendsList)); ?>
	</ul>
<?php } else { ?>
	<div class="noData">
		No friends
	</div>
<?php } ?>
<?php mysql_free_result($friendsList); ?>