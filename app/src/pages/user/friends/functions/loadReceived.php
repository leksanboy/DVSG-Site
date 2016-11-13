<?php require_once('../../../../Connections/conexion.php');
	$userId = $_GET['userId'];
	
	//Pending friends 'received'
	$query_receivedList = sprintf("SELECT * FROM z_friends WHERE receiver = %s AND status = 1",
	GetSQLValueString($userId, "int"));
	$receivedList = mysql_query($query_receivedList, $conexion) or die(mysql_error());
	$row_receivedList = mysql_fetch_assoc($receivedList);
	$totalRows_receivedList = mysql_num_rows($receivedList);
?>
<?php if ($totalRows_receivedList != 0){ ?>
	<ul class="friendsListBox">
		<?php do { ?>
			<li id="friend<?php echo $row_receivedList['id'] ?>">
				<div class="head">
					<div class="image" onclick="userPage(<?php echo $row_receivedList['sender'] ?>)">
						<img src="<?php echo userAvatar($row_receivedList['sender']) ?>"/>
					</div>
					<div class="name" onclick="userPage(<?php echo $row_receivedList['sender'] ?>)">
						<?php echo userName($row_receivedList['sender']) ?>
					</div>
					<div class="buttons">
						<button onClick="statusFriend(1, <?php echo $row_receivedList['sender'] ?>, <?php echo $row_receivedList['id'] ?>)">
							<?php include("../../../../images/svg/close.php");?>
							Cancel
						</button>
						<button onClick="statusFriend(2, <?php echo $row_receivedList['sender'] ?>, <?php echo $row_receivedList['id'] ?>)">
							<?php include("../../../../images/svg/check.php");?>
							Accept
						</button>
					</div>
				</div>
			</li>
		<?php } while ($row_receivedList = mysql_fetch_assoc($receivedList)); ?>
	</ul>
<?php } else { ?>
	<div class="noData">
		No friends received requests
	</div>
<?php } ?>
<?php mysql_free_result($receivedList); ?>