<div class="search">
	<form id="formSearchBlog" onsubmit="return false">
		<input type="text" id="inputSearch" name="searchBlog" autocomplete="off" onkeyup="searchButtonEnable(this.value)" placeholder="Search..." value="<?php if (isset($_SESSION['textBlogSearch'])) echo $_SESSION['textBlogSearch'];?>"/>
		<?php include("images/svg/search.php");?>
		<div class="searchApply">
			<label for="submitButton">
				<div class="button" id="searchButton">
					<input type="submit" onclick="searchButton();" id="submitButton" style="display: none;"></input>
					<?php include("images/svg/send.php");?>
				</div>
			</label>
			<div class="button" onclick="resetSearch()" id="resetButton" <?php if(isset($_SESSION['textBlogSearch']) || isset($_SESSION['blogFilter'])){?>style="display:block"<?php }?>>
				<?php include("images/svg/remove.php");?>
			</div>
		</div>
	</form>
</div>
<?php 
	if(isset($_SESSION['advancedSearchShortByItemBlog'])){
		$sortByItem = $_SESSION['advancedSearchShortByItemBlog'];
	}else{
		$sortByItem = 0;
	}
?>
<div class="buttonAddNew" <?php if (isset($_SESSION['MM_Id'])){ ?> onclick="clickThePage(10)" <?php }else{ ?> onclick="addNewWindow()" <?php } ?>>ADD NEW</div>

<div class="addNewWindow">
	<div class="close" onclick="addNewWindow()"><?php include("images/svg/close.php");?></div>
	<div class="text">To add new, you must create an account</div>
	<div class="button" onclick="clickThePage(7)">create an account</div>
</div>

<div class="buttonShortBy">
	<select name="shortBy" onchange="shortBy(this.value)">
	    <option id="shortBy" disabled="disabled" <?php if($sortByItem == 0) echo 'selected' ?>>Short by</option>
	    <option value="like" <?php if($sortByItem == 1) echo 'selected' ?>>Most Liked</option>
	    <option value="view" <?php if($sortByItem == 2) echo 'selected' ?>>Most Viewed</option>
	    <option value="comment" <?php if($sortByItem == 3) echo 'selected' ?>>Most Commented</option>
	</select>
</div>

<script type="text/javascript">
	function addNewWindow(){
		$('.addNewWindow').toggle();
	}

	function shortBy(value){
		$('#resetButton').show();
		$('#loaderShortByShop').show();
		$('#blogPostsContainer').css("opacity","0.25");

		setTimeout(function() {
			$.ajax({
				type: 'POST',
				url: url + 'pages/blog/shortBy.php',
				data: 'valor='+value,
				success: function(response) {
					$('#loaderShortByShop').hide();
					$('#blogPostsContainer').css("opacity","1").html(response);

					if (response.length > 2) {
						$('.loadMore').show();
					}
				}
			});
		}, 1000);
	}

    function searchButtonEnable(value){
        if(value != ''){
            $("#searchButton svg").css("fill","#09f");
        }else{
            $("#searchButton svg").css("fill","#777");
            $('#resetButton').hide();
        }
    }

	function searchButton(){
		$('#resetButton').show();
		$("#searchButton svg").css("fill","#777");
		$('#loaderShortByShop').show();
		$('#blogPostsContainer').css("opacity","0.25");

		setTimeout(function() {
			$.ajax({
				type: 'POST',
				url: url + 'pages/blog/advancedSearch.php',
				data: $('#formSearchBlog').serialize(),
				success: function(response) {
					$('.loadMore').show();
					$('#loaderShortByShop').hide();
					$('#blogPostsContainer').css("opacity","1").html(response);
					$('#shortBy').attr('selected', 'selected');

					if (response.length > 2) {
						$('.loadMore').show();
					}
				}
			});
		}, 1000);
	}

	function resetSearch(){
		$('#resetButton').hide();
		$('#inputSearch').val('');
		$('#loaderShortByShop').show();
		$("#searchButton svg").css("fill","#777");
		$('#shortBy').attr('selected', 'selected');
		$('#blogPostsContainer').css("opacity","0.25");

		setTimeout(function() {
			$.ajax({
				type: 'POST',
				url: url + 'pages/blog/resetSearch.php',
				data: '',
				success: function(response) {
					$('#loaderShortByShop').hide();
					$('#blogPostsContainer').css("opacity","1").html(response);

					if (response.length > 2) {
						$('.loadMore').show();
					}
				}
			});
		}, 1000);
	}

	function loadMoreBlogPost(paginado) {
		$('.loadMore .loader').toggle();

	    $.ajax({
	        type: 'POST',
	        url: url + 'pages/blog/loadMore.php',
	        data: 'paginado=' + paginado,
	        success: function(response) {
	            setTimeout(function() {
                	$('.loadMore .loader').hide();

		            if (response !== '')
		                $('#blogPostsContainer').append(response);
		            else
		                $('.loadMore').hide();
            	}, 600);
	        }
	    });
	}
</script>