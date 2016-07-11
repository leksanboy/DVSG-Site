<?php require_once('../../Connections/conexion.php');

	mysql_select_db($database_conexion, $conexion);

	$_SESSION['indice']= $_SESSION['indice'] + $_POST['paginado'];
	$query_LoadMoreNotices = sprintf("SELECT * FROM z_posts WHERE tipo=1 ORDER BY id DESC LIMIT %s,2",
	GetSQLValueString($_SESSION['indice'], "int"));
		
	$LoadMoreNotices = mysql_query($query_LoadMoreNotices, $conexion) or die(mysql_error());
	$row_LoadMoreNotices = mysql_fetch_assoc($LoadMoreNotices);
	$totalRows_LoadMoreNotices = mysql_num_rows($LoadMoreNotices);

?>
<?php if ($totalRows_LoadMoreNotices!='') { ?>
	<?php do { $imagenesNotice=explode('-:#:-', $row_LoadMoreNotices['imagen1']); ?>
		<div class="noticeContainer">
			<div class="box" onclick="location.href='<?php echo $urlWeb ?><?php echo $row_LoadMoreNotices['urlamigable']; ?>.html'">
				<img src="<?php echo $imagenesNotice[1]; ?>"/>
				<span>
					<?php echo $row_LoadMoreNotices['titulo']; ?>
					<div class="information">
						<?php echo $row_LoadMoreNotices['fecha']; ?>
						<div class="data">
							<?php include("../../images/svg/likes.php");?>
							<?php echo countLikesPosts($row_LoadMoreNotices['id']); ?>
							<?php include("../../images/svg/views.php");?>
							<?php echo $row_LoadMoreNotices['visitas']; ?>
							<?php include("../../images/svg/comments.php");?>
							<?php echo countCommentsPosts($row_LoadMoreNotices['id']); ?>
						</div>
					</div>
				</span>
			</div>
		</div>
	<?php } while ($row_LoadMoreNotices = mysql_fetch_assoc($LoadMoreNotices)); ?>
<?php } ?>
<?php mysql_free_result($LoadMoreNotices); ?>