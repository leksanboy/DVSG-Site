<div id="shopPostsContainer">
	<?php if ($totalRows_AllPosts !='') { ?>
		<?php if($_SESSION['shopSearch'] !='') {?>
			<div class="results">
				<?php echo $_SESSION['countSearchShop'] ?> Results found
			</div>
		<?php } ?>
		<?php do { $imagenesNotice=explode('-:#:-', $row_AllPosts['imagen1']); ?>
			<div class="postContainer" onclick="location.href='<?php echo $urlWeb ?><?php echo $row_AllPosts['urlamigable']; ?>.html'">
				<div class="box" >
					<div class="image">
						<img src="<?php echo $imagenesNotice[0]; ?>"/>
					</div>
					<div class="title"><?php echo $row_AllPosts['titulo']; ?></div>
					<div class="city"><?php echo $row_AllPosts['country']; ?> / <?php echo $row_AllPosts['city']; ?></div>
					<div class="price"><?php echo number_format($row_AllPosts['price'] , 0, ',', '.');?> <?php echo $row_AllPosts['currency']; ?></div>
					<div class="bottom">
						<div class="block"><?php echo $row_AllPosts['km']; ?> km</div>
						<div class="block"><?php echo $row_AllPosts['year']; ?></div>
						<div class="block"><?php echo $row_AllPosts['fuel']; ?></div>
					</div>
				</div>
			</div>
		<?php } while ($row_AllPosts = mysql_fetch_assoc($AllPosts));
	} else{ ?>
		<div class="errorContainer">
			<div class="circleOne">
				<div class="text">404</div>
				<div class="subText">not results</div>
			</div>
		</div>
	<?php }?>
</div>

<div class="loadingMore" style="display:none" id="loaderShortByShop">
	<?php include_once("images/svg/spinner.php");?>
</div>

<div class="loadMore" onclick="loadMoreShopPost(2);">
	<span class="loader">
		<?php include("images/svg/spinner.php");?>
	</span>
	LOAD MORE
</div>