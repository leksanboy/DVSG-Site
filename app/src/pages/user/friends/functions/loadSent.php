<?php require_once('../../../../Connections/conexion.php');
	$userId = $_GET['userId'];
	
	//Pending friends 'sent'
	$query_pendingSent = sprintf("SELECT * FROM z_friends WHERE sender = %s AND status = 1",
	GetSQLValueString($userId, "int"));
	$pendingSent = mysql_query($query_pendingSent, $conexion) or die(mysql_error());
	$row_pendingSent = mysql_fetch_assoc($pendingSent);
	$totalRows_pendingSent = mysql_num_rows($pendingSent);
?>
<?php if ($totalRows_pendingSent == 0){ ?>
	<center>Have not sent requests</center>
<?php } else { ?>
	<?php do { ?>
		<div class="friendBox" id="friend<?php echo $row_pendingSent['id'] ?>">
			<div class="head">
				<div class="image" onclick="userPage(<?php echo $row_pendingSent['receiver'] ?>)">
					<img src="<?php echo userAvatar($row_pendingSent['receiver']) ?>"/>
				</div>
				<div class="name" onclick="userPage(<?php echo $row_pendingSent['receiver'] ?>)">
					<?php echo userName($row_pendingSent['receiver']) ?>
				</div>
				<div class="buttons">
					<button onClick="statusFriend(1, <?php echo $row_pendingSent['receiver'] ?>, <?php echo $row_pendingSent['id'] ?>)">
						<?php include("../../../../images/svg/close.php");?>
						Cancel Request
					</button>
				</div>
			</div>
		</div>
	<?php } while ($row_pendingSent = mysql_fetch_assoc($pendingSent)); ?>
<?php }?>
<?php mysql_free_result($pendingSent); ?>