<?php require_once('../../../../Connections/conexion.php');
	$postId = $_POST['postId'];
	$cuantity = $_POST['cuantity'];
	$_SESSION['moreCommentsNews'.$postId] = $_SESSION['moreCommentsNews'.$postId] + $cuantity;

	// More comments user photo
	mysql_select_db($database_conexion, $conexion);
	$query_GetMoreComments = sprintf ("SELECT * FROM z_news_comments WHERE post = %s ORDER BY id DESC LIMIT %s,  5",
		GetSQLValueString($postId, "int"),
		GetSQLValueString($_SESSION['moreCommentsNews'.$postId], "int"));
	$GetMoreComments = mysql_query($query_GetMoreComments, $conexion) or die(mysql_error());
	$row_GetMoreComments = mysql_fetch_assoc($GetMoreComments);
	$totalRows_GetMoreComments = mysql_num_rows($GetMoreComments);

?>
<?php if ($row_GetMoreComments != ''){?>
	<?php do { ?>
	    <div class="item" id="comment<?php echo $row_GetMoreComments['id'] ?>">
	        <div class="avatar" onclick="userPage(<?php echo $row_GetMoreComments['user']; ?>)">
	            <img src="<?php echo userAvatar($row_GetMoreComments["user"]); ?>" width="28px" height="28px" style="border-radius: 50%"/>
	        </div>

	        <div class="name" onclick="userPage(<?php echo $row_GetMoreComments['user']; ?>)">
	            <?php echo userName($row_GetMoreComments['user']); ?>
	            <font><?php echo timeAgo($row_GetMoreComments['date']);?></font>
	        </div>

	        <?php if (isset ($_SESSION['MM_Id'])){ ?> 
	            <?php  if (($row_GetMoreComments['user'] == $_SESSION['MM_Id']) || (rango_admin ($_SESSION['MM_Id']) ==4)) {?>
					<div class="delete" onClick="deleteCommentNews(1, <?php echo $row_GetMoreComments['id'] ?>)">
						<?php include("../../../../images/svg/clear.php"); ?>
					</div>
					<div class="deleteBoxConfirmation" id="delete<?php echo $row_GetMoreComments['id'] ?>">
						<div class="text">Delete this comment?</div>
						<div class="buttons">
							<button onClick="deleteCommentNews(1, <?php echo $row_GetMoreComments['id'] ?>)">NO</button>
							<button onClick="deleteCommentNews(2, <?php echo $row_GetMoreComments['id'] ?>)">YES</button>
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