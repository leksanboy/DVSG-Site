<?php if ($totalRows_newsList != 0){ ?>
	<ul class="newsListBox">
		<?php do { ?>
			<li>
				POST CONTENT: <?php echo $row_newsList['content'] ?>
			</li>
		<?php } while ($row_newsList = mysql_fetch_assoc($newsList)); ?>
	</ul>
<?php } else { ?>
	<div class="noData">
		No news
	</div>
<?php } ?>