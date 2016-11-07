<?php require_once('../../../Connections/conexion.php');
	$userId = $_GET['userId'];
	$photoId = $_GET['photoId'];
	$_SESSION['moreCommentsPhoto'] = 0;

	// Update views
	// $updateSQL = sprintf("UPDATE z_photos SET replays = replays+1 WHERE id = %s",
	// GetSQLValueString($photoId, "int"));
	// mysql_select_db($database_conexion, $conexion);
	// $Result1 = mysql_query($updateSQL, $conexion) or die(mysql_error());

	// Likes user photo
	mysql_select_db($database_conexion, $conexion);
	$query_GetLikes = sprintf ("SELECT * FROM z_photos_likes WHERE photo = %s",
		GetSQLValueString($photoId, "int"));
	$GetLikes = mysql_query($query_GetLikes, $conexion) or die(mysql_error());
	$row_GetLikes = mysql_fetch_assoc($GetLikes);
	$totalRows_GetLikes = mysql_num_rows($GetLikes);

	// Comments user photo
	mysql_select_db($database_conexion, $conexion);
	$query_GetComments = sprintf ("SELECT * FROM z_photos_comments WHERE photo = %s ORDER BY date DESC",
		GetSQLValueString($photoId, "int"));
	$GetComments = mysql_query($query_GetComments, $conexion) or die(mysql_error());
	$row_GetComments = mysql_fetch_assoc($GetComments);
	$totalRows_GetComments = mysql_num_rows($GetComments);
?>

<div class="user">
	<div class="avatar" onclick="userPage(<?php echo $userId ?>)">
		<img src="<?php echo userAvatar($userId); ?>">
	</div>
	<div class="name" onclick="userPage(<?php echo $userId ?>)">
		<?php echo userName($userId); ?>
	</div>
	<div class="actions">
		<div class="date"><?php echo timeAgo(timeUserPhoto($photoId)); ?></div>
    	<div class="analytics">
			<div class='comments'>
				<?php include('../../../images/svg/comments.php'); ?>
				<span class='count'><?php echo $totalRows_GetComments ?></span>
			</div>
			<div class='likes' <?php  if (isset($_SESSION['MM_Id'])) { ?> onClick='like(<?php echo $photoId ?>)' <?php } ?>>
				<span class='like'>
					<?php if (checkLikeUserPhoto($_SESSION['MM_Id'], $photoId) == true ){ ?>
						<?php include('../../../images/svg/unlike.php'); ?>
					<?php } else {?>
						<?php include('../../../images/svg/like.php'); ?>
					<?php } ?>
				</span>
				<span class='count'><?php echo $totalRows_GetLikes ?></span>
			</div>
    	</div>
	</div>
</div>
<div class="comments">
	<?php  if (isset($_SESSION['MM_Id'])) {?>
	    <div class="newComment">
	        <form onsubmit="return false">
	            <textarea name="comment" id="comentario" class="inputBox" onkeyup="newComment(1, this.value, <?php echo $photoId ?>)" placeholder="Write a comment..."></textarea>
	            <input type="hidden" name="page" value="<?php echo $photoId; ?>" />
	            <input type="button" style="display:none" onclick="newComment(2, comment.value, <?php echo $photoId ?>)" id="btn_comentario">
	            <label for="btn_comentario">
	                <div class="button">
	                    <?php include("../../../images/svg/send.php");?>
	                </div>
	            </label>
	        </form>
	    </div>
	<?php } else {?>
	    <div class="registerToComment">to comment create an account</div>
	<?php }?>

	<div class="commentsList">
    	<?php if ($row_GetComments != '') {?>
	        <?php do { $countComments++; ?>
	            <div class="item" id="comment<?php echo $row_GetComments['id'] ?>">
	                <div class="avatar" onclick="userPage(<?php echo $row_GetComments['user'] ?>)">
	                    <img src="<?php echo userAvatar($row_GetComments["user"]); ?>" width="36px" height="36px" style="border-radius: 50%"/>
	                </div>

	                <div class="name" onclick="userPage(<?php echo $row_GetComments['user'] ?>)">
	                    <?php echo userName($row_GetComments['user']); ?>
	                    <font size="-2"><?php echo timeAgo($row_GetComments['time']);?></font>
	                </div>

	                <?php if (isset ($_SESSION['MM_Id'])){ ?> 
	                    <?php  if (($row_GetComments['user'] == $_SESSION['MM_Id']) || (rango_admin ($_SESSION['MM_Id']) ==4)) {?>
							<div class="delete" onClick="deleteComment(1, <?php echo $row_GetComments['id'] ?>)">
								<?php include("../../../images/svg/clear.php"); ?>
							</div>
							<div class="deleteBoxConfirmation" id="delete<?php echo $row_GetComments['id'] ?>">
								<div class="text">Delete this comment?</div>
								<div class="buttons">
									<button onClick="deleteComment(1, <?php echo $row_GetComments['id'] ?>)">NO</button>
									<button onClick="deleteComment(2, <?php echo $row_GetComments['id'] ?>)">YES</button>
								</div>
							</div>
	                    <?php } ?>
	                <?php } ?>

	                <div class="content">
	                    <div class="inner">
	                        <?php echo $row_GetComments["comment"];?>
	                    </div>
	                </div>
	            </div>
	        <?php } while ($countComments < 10 && $row_GetComments = mysql_fetch_assoc($GetComments)); ?>
	    <?php } else { ?>
	    	<div class="noComents">NO COMMENTS</div>
	    <?php } ?>
	</div>

    <?php if ($totalRows_GetComments > 10) { ?>
    	<div class="loadMore" onclick="loadMoreComments(<?php echo $photoId ?>);"> + LOAD MORE</div>
    <?php } ?>
</div>

<?php mysql_free_result($GetLikes); ?>
<?php mysql_free_result($GetComments); ?>