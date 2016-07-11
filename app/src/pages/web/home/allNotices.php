<?php do { $imagenesNotice=explode('-:#:-', $row_AllNotices['imagen1']); ?>
	<div class="noticeContainer">
		<div class="box" onclick="location.href='<?php echo $urlWeb ?><?php echo $row_AllNotices['urlamigable']; ?>.html'">
			<img src="<?php echo $imagenesNotice[1]; ?>"/>
			<div class="panel">
				<?php echo $row_AllNotices['titulo']; ?>
				<div class="information">
					<?php echo $row_AllNotices['fecha']; ?>
					<div class="data">
						<?php include("images/svg/likes.php");?>
						<?php echo countLikesPosts($row_AllNotices['id']); ?>
						<?php include("images/svg/views.php");?>
						<?php echo $row_AllNotices['visitas']; ?>
						<?php include("images/svg/comments.php");?>
						<?php echo countCommentsPosts($row_AllNotices['id']); ?>
					</div>
				</div>
			</div>
		</div>
	</div>
<?php } while ($row_AllNotices = mysql_fetch_assoc($AllNotices)); ?>