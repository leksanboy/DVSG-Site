<?php require_once('../../../../Connections/conexion.php');
	$photoId = $_POST['photoId'];
	$cuantity = $_POST['cuantity'];
	$_SESSION['moreCommentsPhoto'] = $_SESSION['moreCommentsPhoto'] + $cuantity;

	// More comments user photo
	mysql_select_db($database_conexion, $conexion);
	$query_GetMoreComments = sprintf ("SELECT * FROM z_photos_comments WHERE photo = %s ORDER BY date DESC LIMIT %s,  10",
		GetSQLValueString($photoId, "int"),
		GetSQLValueString($_SESSION['moreCommentsPhoto'], "int"));
	$GetMoreComments = mysql_query($query_GetMoreComments, $conexion) or die(mysql_error());
	$row_GetMoreComments = mysql_fetch_assoc($GetMoreComments);
	$totalRows_GetMoreComments = mysql_num_rows($GetMoreComments);
?>
<?php if ($row_GetMoreComments != ''){?>
	<?php do { ?>
	    <div class="item" id="comment<?php echo $row_GetMoreComments['id'] ?>">
	        <div class="avatar" onclick="userPage(<?php echo $row_GetMoreComments['user']; ?>)">
	            <img src="<?php echo userAvatar($row_GetMoreComments["user"]); ?>" width="36px" height="36px" style="border-radius: 50%"/>
	        </div>

	        <div class="name" onclick="userPage(<?php echo $row_GetMoreComments['user']; ?>)">
	            <?php echo userName($row_GetMoreComments['user']); ?>
	            <font size="-2"><?php echo timeAgo($row_GetMoreComments['date']);?></font>
	        </div>

	        <?php if (isset ($_SESSION['MM_Id'])){ ?> 
	            <?php  if (($row_GetMoreComments['user'] == $_SESSION['MM_Id']) || (rango_admin ($_SESSION['MM_Id']) ==4)) {?>
					<div class="delete" onClick="deleteComment(1, <?php echo $row_GetMoreComments['id'] ?>)">
						<?php include("../../../../images/svg/clear.php"); ?>
					</div>
					<div class="deleteBoxConfirmation" id="delete<?php echo $row_GetMoreComments['id'] ?>">
						<div class="text">Delete this comment?</div>
						<div class="buttons">
							<button onClick="deleteComment(1, <?php echo $row_GetMoreComments['id'] ?>)">NO</button>
							<button onClick="deleteComment(2, <?php echo $row_GetMoreComments['id'] ?>)">YES</button>
						</div>
					</div>
	            <?php }?>
	        <?php }?>

	        <div class="content">
	            <div class="inner">
	                <?php echo $row_GetMoreComments["comment"];?>
	            </div>
	        </div>
	    </div>
	<?php } while ($row_GetMoreComments = mysql_fetch_assoc($GetMoreComments)); ?>
<?php }?>
<?php mysql_free_result($GetMoreComments);?>