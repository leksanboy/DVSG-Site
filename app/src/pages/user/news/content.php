<!-- user/loadPosts -pageLocation- user·/·news -->
<!-- user/slidePhotosPost -pageLocation- user·/·news -->

<br><br><br>
<div id="newsPosts">
	<div class="pageLoader">
		<?php include("images/svg/spinner.php");?>
	</div>
</div>

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

	//·····> Delete message on Inbox
	function deleteNews(type, id){
		if (type==1) {
			$('#delete'+id).toggle();
		}else if (type==2) {
			console.log('DELETED', id);

			$.ajax({
				type: 'POST',
				url: url + 'pages/user/user/delete.php',
				data: 'id=' + id,
				success: function(response){
					$('#news'+id).fadeOut(300);
				}
			});
		}
	}
</script>