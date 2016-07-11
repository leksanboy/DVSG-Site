<?php do { ?>
	<a class="carsBox" onclick="return createTimedLink(this, myFunction, 600);" href="<?php echo $urlWeb ?>brands/<?php echo $row_CarsBrands['url']; ?>">
		<img src="<?php echo $row_CarsBrands['image']; ?>">
		<p><?php echo $row_CarsBrands['brand']; ?></p>
		<squarebox></squarebox>
	</a>
<?php } while ($row_CarsBrands = mysql_fetch_assoc($CarsBrands)); ?>