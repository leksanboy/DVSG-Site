<?php if (isset ($_SESSION['MM_Id'])){ ?>
	<div class="defaultDataList">
		<div class="pageLoader">
			<?php include("images/svg/spinner.php");?>
		</div>
	</div>

	<script type="text/javascript">
		var userId = <?php echo $userPageId ?>;
		console.log('userId', userId);

		//·····> SVG icons
		var uploadIcon 			= '<?php include('images/svg/upload.php'); ?>',
			arrowUpIcon 		= '<?php include('images/svg/arrow-up.php'); ?>',
			progressIcon 		= '<?php include('images/svg/progress.php'); ?>',
			loaderIcon			= '<?php include('images/svg/spinner.php');?>',
			removeIcon 			= '<?php include('images/svg/close.php'); ?>';

		//·····> Get id element
		function getFile(el){
			return document.getElementById(el);
		}

		//·····> Add new photo
		var filesArray = [],
			fileId =  0;
		function uploadFile(type, event){
			var removeByAttr = function(arr, attr, value){ //·····> Remove from array by atribute
			    var i = arr.length;
			    while(i--){
			       if( arr[i] 
			           && arr[i].hasOwnProperty(attr) 
			           && (arguments.length > 2 && arr[i][attr] === value ) ){ 

			           arr.splice(i,1);
			       }
			    }
			    return arr;
			}

			if (type==1) { // Open
				$('.modalBox').toggleClass('modalDisplay');
				setTimeout(function() {
					$('.modalBox').toggleClass('showModal');
				}, 100);
				$('body').toggleClass('modalHidden');

				var box = "<div class='head'>\
								UPLOAD PHOTOS\
							</div>\
							<form enctype='multipart/form-data' method='post' onSubmit='return false'>\
								<div class='upload'>\
									<label for='fileUpload'>\
										" + uploadIcon + " Upload\
									</label>\
									<input type='file' name='fileUpload[]' multiple id='fileUpload' onChange='uploadFile(4, event)' accept='image/jpeg,image/png,image/gif'>\
								</div>\
								<div class='filesBox'></div>\
								<div class='buttons'>\
									<button onClick='uploadFile(3, this)'>UPLOAD</button>\
									<button onClick='uploadFile(2)'>CLOSE</button>\
								</div>\
							</form>"

				$('.modalBox .box').html(box);
			} else if (type==2) { //Close
				if (filesArray.length > 0)
					defaultLoad();

				$('.modalBox').toggleClass('modalDisplay');
				$('body').toggleClass('modalHidden');

				setTimeout(function() {
					$('.modalBox').toggleClass('showModal');
					$(".filesBox").html('');
					filesArray = [];
					fileId =  0;
				}, 100);
			} else if (type==3) { //Save
				$('.fileStatus .operations .action').hide();
				$('.fileStatus .operations #status').show();

				var i = 0;

				for (; i < filesArray.length; i++) {
					var file = filesArray[i],
						id = filesArray[i].id;
						formdata = new FormData(),
						ajax = new XMLHttpRequest();

					ajaxCall(file, formdata, ajax, id);
				}

				function ajaxCall(file, formdata, ajax, id){
					formdata.append("fileUpload", file);
					ajax.upload.addEventListener("progress", function(evt){ progressHandler(evt, id) }, false);
					ajax.addEventListener("load", function(evt){ completeHandler(evt, id) }, false);
					ajax.addEventListener("error", function(evt){ errorHandler(evt, id) }, false);
					ajax.addEventListener("abort", function(evt){ abortHandler(evt, id) }, false);
					ajax.open("POST", "pages/user/photos/upload.php");
					ajax.send(formdata);
				}

				if (filesArray.length > 0) // Disable button after upload
					$(event).attr("disabled", "disabled");
			} else if (type==4) { //Get photo data
				var	file,
					i = 0;

				for (;i < event.currentTarget.files.length; i++) {
	      			file = event.currentTarget.files[i];
	      			file.id = fileId++;
					filesArray.push(file);

		      		var title = "<div class='fileStatus' id='fileStatus"+ file.id +"'>\
									<div class='title' id='title'>" + file.name + "</div>\
									<div class='operations'>\
										<div class='status action' onclick='uploadFile(5, "+ file.id +")'>"+removeIcon+"</div>\
										<div class='status' id='status'>\
											<div class='progress'>" + progressIcon + "</div>\
											<div class='percentage'>0%</div>\
											<div class='loading'>" + arrowUpIcon + "</div>\
											<div class='result'></div>\
										</div>\
									</div>\
								</div>";

		      		$(".filesBox").append(title);
	      		}
			}else if (type==5) { //Remove from array
				removeByAttr(filesArray, 'id', event);
				$('#fileStatus' + event).fadeOut();
			}
		}

		//·····> Add new photo uploading progress bar
		/* https://www.developphp.com/video/JavaScript/File-
		Upload-Progress-Bar-Meter-Tutorial-Ajax-PHP */
		function progressHandler(event, i){
			var percent = (event.loaded / event.total) * 100;
			percent = Math.round(percent);

			if (percent == 100){
				$('#fileStatus' + i + ' .operations #status .loading').show();
				$('#fileStatus' + i + ' .operations #status .percentage').hide();
				$('#fileStatus' + i + ' #status .progress svg circle').css('stroke-dashoffset', 0);
			}else{
				$('#fileStatus' + i + ' #status .percentage').html(percent + '%');
				$('#fileStatus' + i + ' #status .progress svg circle').css('stroke-dashoffset', 100 - percent*93/100);
			}
		}
		function completeHandler(event, i){
			$('#fileStatus' + i + ' .operations #status .loading').hide();
			$('#fileStatus' + i + ' .operations #status .result').html(event.target.responseText);
		}
		function errorHandler(event, i){
			$('#fileStatus' + i + ' .operations #status .result').html('Failed');
		}
		function abortHandler(event, i){
			$('#fileStatus' + i + ' .operations #status .result').html('Aborted');
		}

		//·····> Delete photo
		function deletePhoto(type, id){
			if (type==1) {
				$.ajax({
					type: 'POST',
					url: '<?php echo $urlWeb ?>' + 'pages/user/photos/delete.php',
					data: 'type=delete' + '&id=' + id,
					success: function(response){
						$('.photo'+ id).css('opacity','0.5');
						$('.photo'+ id +' .actions .add').hide();
						$('.photo'+ id +' .actions .added').show();
						$('.photo'+ id +' .actions .added').attr("onclick","deletePhoto(2, "+ id +");");
					}
				});
			} else if (type==2) {
				$.ajax({
					type: 'POST',
					url: '<?php echo $urlWeb ?>' + 'pages/user/photos/delete.php',
					data: 'type=add' + '&id=' + id,
					success: function(response){
						$('.photo'+ id).css('opacity','1');
						$('.photo'+ id + ' .actions .add').show();
						$('.photo'+ id + ' .actions .added').hide();
					}
				});
			}
		}

		//·····> Default
		function defaultLoad(){
			$.ajax({
				type: 'GET',
				url: '<?php echo $urlWeb ?>' + 'pages/user/photos/default.php',
				data: 'userId=' + userId,
				success: function(response) {
					$('.defaultDataList').show();
					$('.defaultDataList').html(response);
					$('.searchBoxDataList').hide();
				}
			});
		}
		defaultLoad();

		//·····> Open photo
		function openPhoto(type, position, photoId){
			if (type==1) { // Open
				$('.modalBox').toggleClass('modalDisplay');
				setTimeout(function() {
					$('.modalBox').toggleClass('showModal');
				}, 100);
				$('body').toggleClass('modalHidden');

				$.ajax({
	                type: 'GET',
	                url: '<?php echo $urlWeb ?>' + 'pages/user/photos/slidePhotos.php',
	                data: 'position=' + position + '&photoId=' + photoId + '&userId=' + userId,
	                success: function(response){
	                    $('.slidePhotosBox').html(response);
	                } 
	            });

				var box = "<div class='slidePhotosBox'>\
								<div class='loader'>\
									"+loaderIcon+"\
								</div>\
							</div>\
							<div class='buttons'>\
								<button onClick='openPhoto(2)'>CLOSE</button>\
							</div>"

				$('.modalBox .box').html(box);
			} else if (type==2) { //Close
				$('.modalBox').toggleClass('modalDisplay');
				$('body').toggleClass('modalHidden');
			}
		}

		//·····> Load more
		function loadMore(){
			$.ajax({
	            type: "GET",
	            url: '<?php echo $urlWeb ?>' + 'pages/user/photos/loadMore.php',
	            data: 'cuantity=' + 15 + '&userId=' + userId,
	            success: function(response) {
	            	if (response != '')
	                	$('.pagePhotos .defaultDataList .photosListBox').append(response);
	                else
	                	$('.pagePhotos .defaultDataList .loadMore').hide();
	            }
	        });
		}
	</script>
<?php } else { ?>
	<center>
		<br><br><br>
		CREATE AN ACCOUNT
	</center>
<?php } ?>