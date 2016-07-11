<div class="swiperHome" id="swiper-container">
	<div class="swiper-wrapper">
		<?php do { $imagenes=explode('-:#:-', $row_SwiperSlider['imagen1']); ?>
			<div class="swiper-slide" onclick="location.href='<?php echo $row_SwiperSlider['urlamigable']; ?>.html'">
				<img src="<?php echo $imagenes[0]; ?>"/>
				<span style="color:#<?php echo $row_SwiperSlider['color']; ?>;">
					<div class="text"><?php echo $row_SwiperSlider['titulo']; ?></div>
					<div class="data">
						<?php include("images/svg/likes.php");?>
						<?php echo countLikesPosts($row_AllNotices['id']); ?>
						<?php include("images/svg/views.php");?>
						<?php echo $row_AllNotices['visitas']; ?>
						<?php include("images/svg/comments.php");?>
						<?php echo countCommentsPosts($row_AllNotices['id']); ?>
					</div>
					<div class="button">see more</div>
				</span>
			</div>
		<?php } while ($row_SwiperSlider = mysql_fetch_assoc($SwiperSlider)); ?>
	</div>
	<div class="swiper-arrow-left"></div>
	<div class="swiper-arrow-right"></div>
	<center>
		<div class="pagination" id="pagination"></div>
	</center>
</div>