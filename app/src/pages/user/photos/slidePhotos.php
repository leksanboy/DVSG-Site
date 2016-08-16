<?php require_once('../../../Connections/conexion.php');
	$userId = $_SESSION['MM_Id'];
	$photoId = $_POST['photoId'];

	// User photos  --> photosList
	mysql_select_db($database_conexion, $conexion);
	$query_photosList = sprintf("SELECT * FROM z_photos WHERE user = $userId ORDER BY z_photos.date DESC");
	$photosList = mysql_query($query_photosList, $conexion) or die(mysql_error());
	$row_photosList = mysql_fetch_assoc($photosList);
	$totalRows_photosList = mysql_num_rows($photosList);
?>

<div class="container">
	<div class="boxPhoto">
		<div class="panel">
			<div class="counter">
			</div>
			<div class="close" onclick="openPhoto(2)">
				<?php include("../../../images/svg/close.php"); ?>
			</div>
		</div>
		<div class="prev" onclick="prevNext(1)">
			<?php include("../../../images/svg/arrow-left.php"); ?>
		</div>
		<div class="next" onclick="prevNext(2)">
			<?php include("../../../images/svg/arrow-right.php"); ?>
		</div>
		<div class="image">
			<img src=""/>
		</div>
	</div>
	<div class="boxData">
	</div>
</div>

<script type="text/javascript">
	// ···> SVG Icons
	var likeIcon 			= "<?php include('../../../images/svg/like.php'); ?>",
		unlikeIcon 			= "<?php include('../../../images/svg/unlike.php'); ?>",
	
	// ···> Init
    	position 			= <?php echo $_POST['position']; ?>,
    	totalCount 			= <?php echo $totalRows_photosList; ?>,
		photosDataList 		= [	<?php do { ?>
				        				{
						                    "id": 	<?php echo $row_photosList['id'] ?>,
						                    "name": "<?php echo $row_photosList['name'] ?>",
						                    "date": "<?php echo $row_photosList['date'] ?>"
						                },
						            <?php } while ($row_photosList = mysql_fetch_assoc($photosList)); ?>
						        ],

	// ···> Set variables
		imagePhoto 			= $('.modalBox .box .slidePhotosBox .container .boxPhoto img'),
		counterPhoto 		= $('.modalBox .box .slidePhotosBox .container .boxPhoto .panel .counter'),
		datePhoto 			= $('.modalBox .box .slidePhotosBox .container .boxData .user .name .date'),
		commentsBoxPhoto	= $('.modalBox .box .slidePhotosBox .container .boxData .comments');

	// ···> Set default
	imagePhoto.attr("src","pages/user/photos/photos/" + photosDataList[position].name); // Default position image in view box
	counterPhoto.html(position + 1 + ' / ' + totalCount); // Photo position "current/total"
	datePhoto.html(photosDataList[position].date); // Photo publication date
	setPosition(position); // Call data information: likes comments count

	// ···> Prev/Next photo
	function prevNext(type){
		if (type==1) { //Prev
			position = position - 1;
			if (position == -1)
				position = totalCount - 1;

			setPosition(position);
		}else if (type==2) { //Next
			position = position + 1;
			if (position == totalCount)
				position = 0;

			setPosition(position);
		}
	}

	//·····> Prev/Next set position data
	function setPosition(position){
		counterPhoto.html(position + 1 + ' / ' + totalCount);
		imagePhoto.attr("src","pages/user/photos/photos/" + photosDataList[position].name);
		datePhoto.html(photosDataList[position].date);

		$.ajax({
	        type: 'POST',
	        url: '<?php echo $urlWeb ?>' + 'pages/user/photos/setPositionData.php',
	        data: 'photoId=' + photosDataList[position].id,
	        success: function(response){
	        	$('.container .boxData').html(response);
	        }
	    });
	}

	//·····> Like photo
	function like(){
		var countLikesPhoto 	= $('.modalBox .box .slidePhotosBox .container .boxData .user .actions .analytics .likes .count'),
			likeIconPhoto		= $('.modalBox .box .slidePhotosBox .container .boxData .user .actions .analytics .likes .like'),
			countLikes 			= parseInt(countLikesPhoto.html());

		$.ajax({
	        type: 'POST',
	        url: '<?php echo $urlWeb ?>' + 'pages/user/photos/like.php',
	        data: 'photoId=' + photosDataList[position].id,
	        success: function(response){
	            if (response == 'like'){ // Like
	            	countLikes = countLikes +1;

	            	countLikesPhoto.html(countLikes);
	            	likeIconPhoto.html(likeIcon);
	        	} else { // Unlike
	            	countLikes = countLikes -1;

	            	countLikesPhoto.html(countLikes);
	            	likeIconPhoto.html(unlikeIcon);
	        	}
	        }
	    });
	}

	//·····> New comment
	function newComment(type, value){
		var buttonSendCommentPhoto 	= $(".modalBox .box .slidePhotosBox .container .boxData .comments .newComment .button svg"),
			inputSendCommentPhoto 	= $(".modalBox .box .slidePhotosBox .container .boxData .comments .newComment .inputBox"),
			commentsList 			= $('.modalBox .box .slidePhotosBox .container .boxData .comments .commentsList'),
			countCommentsPhoto 		= $('.modalBox .box .slidePhotosBox .container .boxData .user .actions .analytics .comments .count'),
			countComments 			= parseInt(countCommentsPhoto.html());

		if (type == 1) {
            if(value != ''){
                buttonSendCommentPhoto.css("fill","#09f");
            }else{
                buttonSendCommentPhoto.css("fill","#333");
            }
		} else if (type == 2) {
			if (value != '') {
                $.ajax({
                    type: "POST",
                    url: '<?php echo $urlWeb ?>' + 'pages/user/photos/comments/new.php',
                    data: 'commentText=' + value + '&photoId=' + photosDataList[position].id,
                    success: function(response) {
                        commentsList.prepend(response);
                        inputSendCommentPhoto.val('');
                        countCommentsPhoto.html(countComments + 1);
                    }
                });
            }
		}
	}

	//·····> Delete comment
	function deleteComment(type, id){
		var countCommentsPhoto 	= $('.modalBox .box .slidePhotosBox .container .boxData .user .actions .analytics .comments .count'),
			countComments 		= parseInt(countCommentsPhoto.html()),
			deleteComment		= $('.modalBox .box .slidePhotosBox .container .boxData .comments .commentsList .item #delete' + id),
			boxComment			= $('.modalBox .box .slidePhotosBox .container .boxData .comments .commentsList #comment' + id);

		if (type == 1) {
			deleteComment.toggle();
		}else if (type==2) {
			$.ajax({
				type: 'POST',
				url: '<?php echo $urlWeb ?>' + 'pages/user/photos/comments/delete.php',
				data: 'id=' + id,
				success: function(response){
					boxComment.fadeOut(300);
					deleteComment.fadeOut(300);

					countCommentsPhoto.html(countComments - 1);
				}
			});
		}
	}

	//·····> Load more comment
	function loadMorecomments(photoId){
		var commentsList 					= $('.modalBox .box .slidePhotosBox .container .boxData .comments .commentsList'),
			buttonLoadMoreCommentsPhoto 	= $('.modalBox .box .slidePhotosBox .container .boxData .comments .loadMore');

		$.ajax({
            type: "POST",
            url: '<?php echo $urlWeb ?>' + 'pages/user/photos/comments/loadMore.php',
            data: 'cuantity=' + 10 + '&photoId=' + photosDataList[position].id,
            success: function(response) {
            	if (response != '')
                	commentsList.append(response);
                else
                	buttonLoadMoreCommentsPhoto.hide();

            }
        });
	}
</script>
<?php mysql_free_result($photosList); ?>