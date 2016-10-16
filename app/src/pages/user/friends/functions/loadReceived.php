<?php require_once('../../../../Connections/conexion.php');
	$userId = $_GET['userId'];
	
	//Pending friends 'received'
	$query_pendingReceived = sprintf("SELECT * FROM z_friends WHERE receiver = %s AND status = 1",
	GetSQLValueString($userId, "int"));
	$pendingReceived = mysql_query($query_pendingReceived, $conexion) or die(mysql_error());
	$row_pendingReceived = mysql_fetch_assoc($pendingReceived);
	$totalRows_pendingReceived = mysql_num_rows($pendingReceived);
?>
<?php if ($totalRows_pendingReceived == 0){ ?>
	<center>Have not received requests</center>
<?php } else { ?>
	<?php do { ?>
		<div class="friendBox" id="friend<?php echo $row_pendingReceived['id'] ?>">
			<div class="head">
				<div class="image" onclick="userPage(<?php echo $row_pendingReceived['sender'] ?>)">
					<img src="<?php echo userAvatar($row_pendingReceived['sender']) ?>"/>
				</div>
				<div class="name" onclick="userPage(<?php echo $row_pendingReceived['sender'] ?>)">
					<?php echo userName($row_pendingReceived['sender']) ?>
				</div>
				<div class="buttons">
					<button onClick="statusFriend(1, <?php echo $row_pendingReceived['sender'] ?>, <?php echo $row_pendingReceived['id'] ?>)">
						<?php include("../../../../images/svg/close.php");?>
						Cancel
					</button>
					<button onClick="statusFriend(2, <?php echo $row_pendingReceived['sender'] ?>, <?php echo $row_pendingReceived['id'] ?>)">
						<?php include("../../../../images/svg/check.php");?>
						Accept
					</button>
				</div>
			</div>
		</div>
	<?php } while ($row_pendingReceived = mysql_fetch_assoc($pendingReceived)); ?>
<?php }?>
<?php mysql_free_result($pendingReceived); ?>