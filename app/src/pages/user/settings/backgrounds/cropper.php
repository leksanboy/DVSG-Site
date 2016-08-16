<div class="cropperModalBox cropperModalBoxBackgrounds">
	<div class="box">
		<div class="head">
			<div class="title">
				Change background
			</div>
		</div>
		<div class="container" id="backgroundImageOne">
		    <div class="inner">
				<div class="wrapper" >
					<img class="cropperBackground1" src="<?php echo $row_userData['avatar_bg1']; ?>" alt="">
				</div>

				<div class="panel">
					<button type="button">
						<label for="inputImageBackground1">
			        		<?php include("images/svg/upload.php"); ?>
			        	</label>
						<input type="file" name="file" id="inputImageBackground1" accept="image/*">
			        </button>
			        <button id="zoomIn" type="button">
			        	<?php include("images/svg/zoom-in.php"); ?>
			        </button>
			        <button id="zoomOut" type="button">
			        	<?php include("images/svg/zoom-out.php"); ?>
			        </button>
			        <button id="rotateLeft" type="button">
			        	<?php include("images/svg/rotate-left.php"); ?>
			        </button>
			        <button id="rotateRight" type="button">
			        	<?php include("images/svg/rotate-right.php"); ?>
			        </button>
			    </div>
		    </div>
		       
			<div class="actions">
				<button type="button" onclick="openModalBackground(1)">Close</button>
				<button type="button" onclick="openModalBackground(2, 1)">Save</button>
			</div>
	  	</div>
	  	<div class="container" id="backgroundImageTwo">
		    <div class="inner">
				<div class="wrapper" >
					<img class="cropperBackground2" src="<?php echo $row_userData['avatar_bg2']; ?>" alt="">
				</div>

				<div class="panel">
					<button id="zoomIn" type="button">
						<label for="inputImageBackground2">
			        		<?php include("images/svg/upload.php"); ?>
			        	</label>
						<input type="file" name="file" id="inputImageBackground2" accept="image/*">
			        </button>
			        <button id="zoomIn" type="button">
			        	<?php include("images/svg/zoom-in.php"); ?>
			        </button>
			        <button id="zoomOut" type="button">
			        	<?php include("images/svg/zoom-out.php"); ?>
			        </button>
			        <button id="rotateLeft" type="button">
			        	<?php include("images/svg/rotate-left.php"); ?>
			        </button>
			        <button id="rotateRight" type="button">
			        	<?php include("images/svg/rotate-right.php"); ?>
			        </button>
			    </div>
		    </div>
		       
			<div class="actions">
				<button type="button" onclick="openModalBackground(1)">Close</button>
				<button type="button" onclick="openModalBackground(2, 2)">Save</button>
			</div>
	  	</div>
	  	<div class="container" id="backgroundImageThree">
		    <div class="inner">
				<div class="wrapper" >
					<img class="cropperBackground3" src="<?php echo $row_userData['avatar_bg3']; ?>" alt="">
				</div>

				<div class="panel">
					<button id="zoomIn" type="button">
						<label for="inputImageBackground3">
			        		<?php include("images/svg/upload.php"); ?>
			        	</label>
						<input type="file" name="file" id="inputImageBackground3" accept="image/*">
			        </button>
			        <button id="zoomIn" type="button">
			        	<?php include("images/svg/zoom-in.php"); ?>
			        </button>
			        <button id="zoomOut" type="button">
			        	<?php include("images/svg/zoom-out.php"); ?>
			        </button>
			        <button id="rotateLeft" type="button">
			        	<?php include("images/svg/rotate-left.php"); ?>
			        </button>
			        <button id="rotateRight" type="button">
			        	<?php include("images/svg/rotate-right.php"); ?>
			        </button>
			    </div>
		    </div>
		       
			<div class="actions">
				<button type="button" onclick="openModalBackground(1)">Close</button>
				<button type="button" onclick="openModalBackground(2, 3)">Save</button>
			</div>
	  	</div>
	</div>
</div>

<style type="text/css">
	#backgroundImageOne,
	#backgroundImageTwo,
	#backgroundImageThree{
		display: none;
	}
</style>

<script type="text/javascript">
	function openModalBackground(type, value){
		console.log('value', value);

	    var $imageBackground = $(".cropperBackground"+value),
	        $dataX = $("#dataX"),
	        $dataY = $("#dataY"),
	        $dataHeight = $("#dataHeight"),
	        $dataWidth = $("#dataWidth"),
	        cropper;

	    $imageBackground.cropper({
	        aspectRatio: 16 / 9,
	        // autoCropArea: 1,
	        data: {
	            x: 420,
	            y: 50,
	            width: 640,
	            height: 360
	        },

	        done: function(data) {
	            $dataX.val(data.x);
	            $dataY.val(data.y);
	            $dataHeight.val(data.height);
	            $dataWidth.val(data.width);
	        }
	    });

	    cropper = $imageBackground.data("cropper");

	    $("#zoomIn").click(function() {
	        $imageBackground.cropper("zoom", 0.1);
	    });

	    $("#zoomOut").click(function() {
	        $imageBackground.cropper("zoom", -0.1);
	    });

	    $("#rotateLeft").click(function() {
	        $imageBackground.cropper("rotate", -90);
	    });

	    $("#rotateRight").click(function() {
	        $imageBackground.cropper("rotate", 90);
	    });

	    var $inputImageBackgroundOne = $("#inputImageBackground"+value),
	        blobURLBackground;

	    if (window.URL) {
	        $inputImageBackgroundOne.change(function() {
	            var files = this.files,
	                file;

	            if (files && files.length) {
	                file = files[0];

	                if (/^image\/\w+$/.test(file.type)) {
	                    if (blobURLBackground) {
	                        URL.revokeObjectURL(blobURLBackground); // Revoke the old one
	                    }

	                    blobURLBackground = URL.createObjectURL(file);

	                    console.log('blobURLBackground', blobURLBackground);
	                    $imageBackground.cropper("reset", true).cropper("replace", blobURLBackground);
	                    $inputImageBackgroundOne.val("");
	                }
	            }
	        });
	    } else {
	        $inputImageBackgroundOne.parent().remove();
	    }


		if (type==1) {
			$('.cropperModalBoxBackgrounds').toggleClass('modalDisplay');
			setTimeout(function() {
				$('.cropperModalBoxBackgrounds').toggleClass('showModal');
			}, 100);
			$('body').toggleClass('modalHidden');

			$('#backgroundImageOne').css('display', 'none');
			$('#backgroundImageTwo').css('display', 'none');
			$('#backgroundImageThree').css('display', 'none');

			if (value==1) {
				$('#backgroundImageOne').css('display', 'block');
			}else if (value==2) {
				$('#backgroundImageTwo').css('display', 'block');
			}else if (value == 3) {
				$('#backgroundImageThree').css('display', 'block');
			}
		}else if (type==2) {
			var dataURL = $imageBackground.cropper("getDataURL", "image/jpeg");

	        if (value==1) {
				$("#backgroundBoxOne .background img").attr('src', dataURL);
	        	$("#backgroundBoxOne .background input").attr('value', dataURL);
			}else if (value==2) {
				$("#backgroundBoxTwo .background img").attr('src', dataURL);
	        	$("#backgroundBoxTwo .background input").attr('value', dataURL);
			}else if (value == 3) {
				$("#backgroundBoxThree .background img").attr('src', dataURL);
	        	$("#backgroundBoxThree .background input").attr('value', dataURL);
			}

	        openModalBackground(1, 0);
		}
	}
</script>