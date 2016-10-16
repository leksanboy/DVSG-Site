<div class="cropperModalBox cropperModalBoxAccount">
	<div class="box">
		<div class="head">
			<div class="title">
				Change avatar
			</div>
		</div>
		<div class="container">
		    <div class="inner">
				<div class="wrapper">
					<img class="cropperAccount" src="<?php echo $row_userData['avatar_original']; ?>" alt="">
				</div>
				<div class="panel">
					<button type="button">
						<label for="inputImageAccount">
			        		<?php include("images/svg/upload.php"); ?>
			        	</label>
						<input type="file" name="file" id="inputImageAccount" accept="image/*">
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
				<button type="button" onclick="openModalAccount(1)">Close</button>
				<button type="button" onclick="openModalAccount(2)">Save</button>
			</div>
	  	</div>
	</div>
</div>

<script type="text/javascript">
    function openModalAccount(type){
		// Cropper starter
		var $imageAccount = $(".cropperAccount"),
	        $dataX = $("#dataX"),
	        $dataY = $("#dataY"),
	        $dataHeight = $("#dataHeight"),
	        $dataWidth = $("#dataWidth"),
	        cropper;

	    $imageAccount.cropper({
	        aspectRatio: 1 / 1,
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

	    cropper = $imageAccount.data("cropper");

	    $("#zoomIn").click(function() {
	        $imageAccount.cropper("zoom", 0.1);
	    });

	    $("#zoomOut").click(function() {
	        $imageAccount.cropper("zoom", -0.1);
	    });

	    $("#rotateLeft").click(function() {
	        $imageAccount.cropper("rotate", -90);
	    });

	    $("#rotateRight").click(function() {
	        $imageAccount.cropper("rotate", 90);
	    });

	    var $inputImageAccount = $("#inputImageAccount"),
	        blobURLAccount;

	    if (window.URL) {
	        $inputImageAccount.change(function() {
	            var files = this.files,
	                file;

	            if (files && files.length) {
	                file = files[0];

	                if (/^image\/\w+$/.test(file.type)) {
	                    if (blobURLAccount) {
	                        URL.revokeObjectURL(blobURLAccount); // Revoke the old one
	                    }

	                    blobURLAccount = URL.createObjectURL(file);
	                    $imageAccount.cropper("reset", true).cropper("replace", blobURLAccount);
	                    $inputImageAccount.val("");
	                }
	            }
	        });
	    } else {
	        $inputImageAccount.parent().remove();
	    }

		if (type==1) {
		    // Open/Close
			$('.cropperModalBoxAccount').toggleClass('modalDisplay');
			setTimeout(function() {
				$('.cropperModalBoxAccount').toggleClass('showModal');
			}, 100);
			$('body').toggleClass('modalHidden');
		}else if (type==2) {
			var dataURLOriginal = $imageAccount.cropper("getDataURL", "image/jpeg");
	        $("#formOne .avatarBox .avatar img").attr('src', dataURLOriginal);
	        $("#formOne .avatarBox .avatar input[name='avatar_original']").attr('value', dataURLOriginal);

			var dataURLLittle = $imageAccount.cropper("getDataURL", {
	            width: 164,
	            height: 164
	        });
	        $("#formOne .avatarBox .avatar input[name='avatar']").attr('value', dataURLLittle);

	        openModalAccount(1);
		}
	}
</script>