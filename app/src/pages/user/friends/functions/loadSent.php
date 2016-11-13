<?php require_once('../../../../Connections/conexion.php');
	$userId = $_GET['userId'];
	
	//Pending friends 'sent'
	$query_sentList = sprintf("SELECT * FROM z_friends WHERE sender = %s AND status = 1",
	GetSQLValueString($userId, "int"));
	$sentList = mysql_query($query_sentList, $conexion) or die(mysql_error());
	$row_sentList = mysql_fetch_assoc($sentList);
	$totalRows_sentList = mysql_num_rows($sentList);
?>
<?php if ($totalRows_sentList != 0){ ?>
	<ul class="friendsListBox">
		<?php do { ?>
			<li id="friend<?php echo $row_sentList['id'] ?>">
				<div class="head">
					<div class="image" onclick="userPage(<?php echo $row_sentList['receiver'] ?>)">
						<img src="<?php echo userAvatar($row_sentList['receiver']) ?>"/>
					</div>
					<div class="name" onclick="userPage(<?php echo $row_sentList['receiver'] ?>)">
						<?php echo userName($row_sentList['receiver']) ?>
					</div>
					<div class="buttons">
						<button onClick="statusFriend(1, <?php echo $row_sentList['receiver'] ?>, <?php echo $row_sentList['id'] ?>)">
							<?php include("../../../../images/svg/close.php");?>
							Cancel Request
						</button>
					</div>
				</div>
			</li>
		<?php } while ($row_sentList = mysql_fetch_assoc($sentList)); ?>
	</ul>
<?php } else { ?>
	<div class="noData">
		No friends sent requests
	</div>
<?php } ?>
<?php mysql_free_result($sentList); ?>