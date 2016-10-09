<!-- user/loadPosts -pageLocation- user·/·news -->
<!-- user/slidePhotosPost -pageLocation- user·/·news -->

<br><br><br>
<div id="newsPosts"></div>

<script type="text/javascript">
	var userId 			= <?php echo $userPageId ?>;

	//·····> load News
	function defaultLoad(){
		$.ajax({
			type: 'POST',
			url: '<?php echo $urlWeb ?>' + 'pages/user/user/loadPosts.php',
			data: 'userId=' + userId + '&pageLocation=news',
			success: function(response) {
				$('#newsPosts').html(response);
			}
		});
	};
	defaultLoad();
</script>