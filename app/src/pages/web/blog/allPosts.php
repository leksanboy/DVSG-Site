<div id="blogPostsContainer">
	<?php if ($totalRows_AllPosts !='') { ?>
		<?php if($_SESSION['blogSearch'] !='') {?>
			<div class="results">
				<?php echo $_SESSION['countSearchBlog'] ?> Results found
			</div>
		<?php } ?>
		<?php do { ?>
			<div class="postContainer" onclick="location.href='<?php echo $urlWeb ?><?php echo $row_AllPosts['urlamigable']; ?>.html'">
				<div class="title"><?php echo $row_AllPosts['titulo']; ?></div>
				<div class="information">
					<div class="date">
						<?php echo $row_AllPosts['fecha']; ?>
					</div>
					<div class="data">
						<?php include("images/svg/likes.php");?>
						<?php echo $row_AllPosts['likes']; ?>
						<?php include("images/svg/views.php");?>
						<?php echo $row_AllPosts['visitas']; ?>
						<?php include("images/svg/comments.php");?>
						<?php echo $row_AllPosts['comments']; ?>
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

<div class="loadMore" onclick="loadMoreBlogPost(2);">
	<span class="loader">
		<?php include("images/svg/spinner.php");?>
	</span>
	LOAD MORE
</div>