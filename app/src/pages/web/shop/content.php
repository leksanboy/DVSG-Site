<div class="search">
	<form id="formSearchShop" onsubmit="return false">
		<input type="text" id="inputSearch" name="searchShop" autocomplete="off" onkeyup="searchButtonShopEnable(this.value)" placeholder="Search..." value="<?php if (isset($_SESSION['textShopSearch'])) echo $_SESSION['textShopSearch'];?>"/>
		<?php include("images/svg/search.php");?>
		<div class="searchApply">
			<div class="button" onclick="searchButtonShop();" id="searchButton">
				<?php include("images/svg/send.php");?>
			</div>
			<div class="button" onclick="resetSearchShop()" id="resetButton" <?php if(isset($_SESSION['textShopSearch']) || isset($_SESSION['shopFilter'])){?>style="display:block"<?php }?>>
				<?php include("images/svg/remove.php");?>
			</div>
		</div>
		<div class="arrowDown" onclick="advancedSearch()"></div>
		<div class="advancedBox">
			<?php 
				if(isset($_SESSION['itemBrand'])){
					$selectedBrand = $_SESSION['itemBrand'];
				}else{
					$selectedBrand = 'selected';
				}

				if(isset($_SESSION['itemPriceFrom'])){
					$selectedPriceFrom = $_SESSION['itemPriceFrom'];
				}else{
					$selectedPriceFrom = 'selected';
				}
				if(isset($_SESSION['itemPriceTo'])){
					$selectedPriceTo = $_SESSION['itemPriceTo'];
				}else{
					$selectedPriceTo = 'selected';
				}

				if(isset($_SESSION['itemYearFrom'])){
					$selectedYearFrom = $_SESSION['itemYearFrom'];
				}else{
					$selectedYearFrom = 'selected';
				}
				if(isset($_SESSION['itemYearTo'])){
					$selectedYearTo = $_SESSION['itemYearTo'];
				}else{
					$selectedYearTo = 'selected';
				}

				if(isset($_SESSION['itemCountry'])){
					$selectedCountry = $_SESSION['itemCountry'];
				}else{
					$selectedCountry = '';
				}
				if(isset($_SESSION['itemCity'])){
					$selectedCity = $_SESSION['itemCity'];
				}else{
					$selectedCity = '';
				}
			?>
			<span>Advanced search</span>
			<div class="box">
				<div class="title">Brand</div>
				<div class="select">
					<select name="selectBrand">
						<option class="selectedItem" value="" <?php if($selectedBrand == 'selected') echo 'selected' ?>>Brand</option>
						<?php do { ?>
					    	<option value="<?php echo  $row_selectBrand['brand']; ?>" <?php if($selectedBrand == $row_selectBrand['brand']) echo 'selected' ?>><?php echo strtoupper($row_selectBrand['brand']); ?></option>
						<?php } while ($row_selectBrand = mysql_fetch_assoc($selectBrand)); ?>
					</select>
				</div>
			</div>
			<div class="box">
				<div class="title">Price</div>
				<div class="select">
					<select name="selectPriceFrom">
						<option class="selectedItem" value="" <?php if($selectedPriceFrom == 'selected') echo 'selected' ?>>From</option>
						<?php for ($i = 1; $i <= 9; $i++) { ?>
							<option value="<?php echo $i ?>000" <?php if($selectedPriceFrom == $i.'000') echo 'selected' ?>><?php echo $i ?>.000</option>
						<?php } ?>
						<?php for ($i = 10; $i <= 70; $i+=5) { ?>
							<option value="<?php echo $i ?>000" <?php if($selectedPriceFrom == $i.'000') echo 'selected' ?>><?php echo $i ?>.000</option>
						<?php } ?>
						<option value="100000" <?php if($selectedPriceFrom == 100000) echo 'selected' ?>>100.000</option>
						<option value="100000" <?php if($selectedPriceFrom == 100000) echo 'selected' ?>>100.000 +</option>
					</select>
				</div>
				<div class="select">
					<select name="selectPriceTo">
						<option class="selectedItem" value="" <?php if($selectedPriceTo == 'selected') echo 'selected' ?>>To</option>
						<?php for ($i = 1; $i <= 9; $i++) { ?>
							<option value="<?php echo $i ?>000" <?php if($selectedPriceTo == $i.'000') echo 'selected' ?>><?php echo $i ?>.000</option>
						<?php } ?>
						<?php for ($i = 10; $i <= 70; $i+=5) { ?>
							<option value="<?php echo $i ?>000" <?php if($selectedPriceTo == $i.'000') echo 'selected' ?>><?php echo $i ?>.000</option>
						<?php } ?>
						<option value="100000" <?php if($selectedPriceTo == 100000) echo 'selected' ?>>100.000</option>
						<option value="1000000000" <?php if($selectedPriceTo == 1000000000) echo 'selected' ?>>100.000 +</option>
					</select>
				</div>
			</div>
			<div class="box">
				<div class="title">Year</div>
				<div class="select">
					<select name="selectYearFrom">
						<option class="selectedItem" value="" <?php if($selectedYearFrom == 'selected') echo 'selected' ?>>From</option>
						<?php for ($i = date("Y"); $i >= 1987; $i--) { ?>
							<option value="<?php echo $i ?>" <?php if($selectedYearFrom == $i) echo 'selected' ?>><?php echo $i ?></option>
						<?php } ?>
					</select>
				</div>
				<div class="select">
					<select name="selectYearTo">
						<option class="selectedItem" value="" <?php if($selectedYearTo == 'selected') echo 'selected' ?>>To</option>
						<?php for ($i = date("Y"); $i >= 1987; $i--) { ?>
							<option value="<?php echo $i ?>" <?php if($selectedYearTo == $i) echo 'selected' ?>><?php echo $i ?></option>
						<?php } ?>
					</select>
				</div>
			</div>
			<div class="box">
				<div class="title">KM</div>
				<div class="select">
					<select name="selectKmFrom">
						<option class="selectedItem" value="" <?php if($selectedKmFrom == 'selected') echo 'selected' ?>>From</option>
						<?php for ($i = 1; $i <= 9; $i++) { ?>
							<option value="<?php echo $i ?>000" <?php if($selectedKmFrom == $i.'000') echo 'selected' ?>><?php echo $i ?>.000</option>
						<?php } ?>
						<?php for ($i = 10; $i <= 90; $i+=5) { ?>
							<option value="<?php echo $i ?>000" <?php if($selectedKmFrom == $i.'000') echo 'selected' ?>><?php echo $i ?>.000</option>
						<?php } ?>
						<?php for ($i = 100; $i <= 1000; $i+=50) { ?>
							<option value="<?php echo $i ?>000" <?php if($selectedKmFrom == $i.'000') echo 'selected' ?>><?php echo $i ?>.000</option>
						<?php } ?>
						<option value="1000000" <?php if($selectedKmFrom == 1000000) echo 'selected' ?>>1.000.000 +</option>
					</select>
				</div>
				<div class="select">
					<select name="selectKmTo">
						<option class="selectedItem" value="" <?php if($selectedKmTo == 'selected') echo 'selected' ?>>To</option>
						<?php for ($i = 1; $i <= 9; $i++) { ?>
							<option value="<?php echo $i ?>000" <?php if($selectedKmTo == $i.'000') echo 'selected' ?>><?php echo $i ?>.000</option>
						<?php } ?>
						<?php for ($i = 10; $i <= 90; $i+=5) { ?>
							<option value="<?php echo $i ?>000" <?php if($selectedKmTo == $i.'000') echo 'selected' ?>><?php echo $i ?>.000</option>
						<?php } ?>
						<?php for ($i = 100; $i <= 1000; $i+=50) { ?>
							<option value="<?php echo $i ?>000" <?php if($selectedKmTo == $i.'000') echo 'selected' ?>><?php echo $i ?>.000</option>
						<?php } ?>
						<option value="1000000000" <?php if($selectedKmTo == 1000000000) echo 'selected' ?>>1.000.000 +</option>
					</select>
				</div>
			</div>
			<div class="box">
				<div class="title">Country/City</div>
				<div class="select">
					<select name="selectCountry" id="countries_states1" class="input-medium bfh-countries" data-country="<?php echo $selectedCountry ?>"></select>
				</div>
				<div class="select">
					<select name="selectCity" class="input-medium bfh-states" data-country="countries_states1" data-state="<?php echo $selectedCity ?>"></select>
				</div>
			</div>
		</div>
		<div class="buttons">
			<input class="button" type="button" onclick="resetSearchShop();" value="RESET">
			<input class="button" type="submit" onclick="searchButtonShop();" value="APPLY">
		</div>
	</form>
</div>
<?php 
	if(isset($_SESSION['advancedSearchShortByItem'])){
		$sortByItem = $_SESSION['advancedSearchShortByItem'];
	}else{
		$sortByItem = 0;
	}
?>
<div class="buttonAddNew" <?php if (isset($_SESSION['MM_Id'])){ ?> onclick="clickThePage(9)" <?php }else{ ?> onclick="addNewWindow()" <?php } ?>>ADD NEW</div>

<div class="addNewWindow">
	<div class="close" onclick="addNewWindow()"><?php include("images/svg/close.php");?></div>
	<div class="text">To add new, you must create an account</div>
	<div class="button" onclick="clickThePage(7)">create an account</div>
</div>

<div class="buttonShortBy">
	<select name="shortBy" onchange="shortBy(this.value)">
	    <option id="shortBy" disabled="disabled" <?php if($sortByItem == 0) echo 'selected' ?>>Short by</option>
	    <option value="priceDESC" <?php if($sortByItem == 1) echo 'selected' ?>>Price: highest to lowest</option>
	    <option value="priceASC" <?php if($sortByItem == 2) echo 'selected' ?>>Price: lowest to highest</option>
	    <option value="yearDESC" <?php if($sortByItem == 3) echo 'selected' ?>>Year: highest to lowest</option>
	    <option value="yearASC" <?php if($sortByItem == 4) echo 'selected' ?>>Year: lowest to highest</option>
	    <option value="kmDESC" <?php if($sortByItem == 5) echo 'selected' ?>>Km: highest to lowest</option>
	    <option value="kmASC" <?php if($sortByItem == 6) echo 'selected' ?>>Km: lowest to highest</option>
	</select>
</div>

<script type="text/javascript">
	function addNewWindow(){
		$('.addNewWindow').toggle();
	}
	
	function advancedSearch(){
		$('.arrowDown').toggleClass("arrowUp");
		$('.search').toggleClass("advancedSearchBox");
		$('.searchApply').toggle();
	}

	function shortBy(value){
		$('#resetButton').show();
		$('#loaderShortByShop').show();
		$('#shopPostsContainer').css("opacity","0.25");

		setTimeout(function() {
			$.ajax({
				type: 'POST',
				url: url + 'pages/shop/shortBy.php',
				data: 'valor='+value,
				success: function(response) {
					$('#loaderShortByShop').hide();
					$('#shopPostsContainer').css("opacity","1").html(response);

					if (response.length > 2) {
						$('.loadMore').show();
					}
				}
			});
		}, 1000);
	}

	function searchButtonShopEnable(value){
        if(value != ''){
            $("#searchButton svg").css("fill","#09f");
        }else{
            $("#searchButton svg").css("fill","#777");
            $('#resetButton').hide();
        }
    }

	function searchButtonShop(){
		$('#resetButton').show();
		$("#searchButton svg").css("fill","#777");
		$('#loaderShortByShop').show();
		$('#shopPostsContainer').css("opacity","0.25");

		setTimeout(function() {
			$.ajax({
				type: 'POST',
				url: url + 'pages/shop/advancedSearch.php',
				data: $('#formSearchShop').serialize(),
				success: function(response) {
					$('#loaderShortByShop').hide();
					$('#shopPostsContainer').css("opacity","1").html(response);
					$('#shortBy').attr('selected', 'selected');

					if (response.length > 2) {
						$('.loadMore').show();
					}
				}
			});
		}, 1000);
	}

	function resetSearchShop(){
		$('#resetButton').hide();
		$('#inputSearch').val('');
		$('#loaderShortByShop').show();
		$("#searchButton svg").css("fill","#777");
		$('#shortBy').attr('selected', 'selected');
		$('#shopPostsContainer').css("opacity","0.25");
		$('.selectedItem').attr('selected', 'selected');

		setTimeout(function() {
			$.ajax({
				type: 'POST',
				url: url + 'pages/shop/resetSearch.php',
				data: '',
				success: function(response) {
					$('#loaderShortByShop').hide();
					$('#shopPostsContainer').css("opacity","1").html(response);

					if (response.length > 2) {
						$('.loadMore').show();
					}
				}
			});
		}, 1000);
	}

	function loadMoreShopPost(paginado) {
		$('.loadMore .loader').toggle();

	    $.ajax({
	        type: 'POST',
	        url: url + 'pages/shop/loadMore.php',
	        data: 'paginado=' + paginado,
	        success: function(response) {
	        	setTimeout(function() {
                	$('.loadMore .loader').hide();

		            if (response !== '')
		                $('#shopPostsContainer').append(response);
		            else
		                $('.loadMore').hide();
            	}, 600);
	        }
	    });
	}
</script>