<?php require_once('../../Connections/conexion.php');

	if (isset($_POST['dataImagen'])) {
		$insertSQL = sprintf("INSERT INTO z_post_img_temporal (usuario, imagen) VALUES (%s, %s)",
		GetSQLValueString($_SESSION['MM_Id'], "text"),
		GetSQLValueString($_POST['dataImagen'], "text"));
		mysql_select_db($database_conexion, $conexion);
		$Result1 = mysql_query($insertSQL, $conexion) or die(mysql_error());
	

		mysql_select_db($database_conexion, $conexion);
		$query_SacarPostGetImg = sprintf("SELECT * FROM z_post_img_temporal WHERE imagen=%s",
		GetSQLValueString($_POST['dataImagen'],"text"));
		$SacarPostGetImg = mysql_query($query_SacarPostGetImg, $conexion) or die(mysql_error());
		$row_SacarPostGetImg = mysql_fetch_assoc($SacarPostGetImg);
		$totalRows_SacarPostGetImg = mysql_num_rows($SacarPostGetImg);
	}else{
		mysql_select_db($database_conexion, $conexion);
		$query_SacarPostGetImg = sprintf("SELECT * FROM z_post_img_temporal WHERE usuario=%s",
		GetSQLValueString($_SESSION['MM_Id'],"text"));
		$SacarPostGetImg = mysql_query($query_SacarPostGetImg, $conexion) or die(mysql_error());
		$row_SacarPostGetImg = mysql_fetch_assoc($SacarPostGetImg);
		$totalRows_SacarPostGetImg = mysql_num_rows($SacarPostGetImg);
	}

?>
<?php if ($totalRows_SacarPostGetImg!=''){ ?>
	<?php do {?>
		<div class="miniImage" id="<?php echo $row_SacarPostGetImg['id'] ?>">
			<img id="carga-nueva<?php echo $row_SacarPostGetImg['id'] ?>" src="<?php echo $row_SacarPostGetImg['imagen'] ?>">	
			<div class="edit avatar-view<?php echo $row_SacarPostGetImg['id'] ?>" onClick="openEditNoticePostWindow()">
				<?php include("../../images/svg/edit.php");?>
			</div>
			<div class="remove" onclick="postOperations(3,'<?php echo $row_SacarPostGetImg['id'] ?>');">
				<?php include("../../images/svg/remove.php");?>
			</div>
		</div>

		<div class="modal fade" id="avatar-modal<?php echo $row_SacarPostGetImg['id'] ?>" aria-hidden="true" aria-labelledby="avatar-modal-label" role="dialog" tabindex="-1">
			<div class="modal-dialog modal-lg">
				<div class="modal-content">
					<form class="avatar-form" action="" enctype="multipart/form-data" method="post">
						<div class="modal-body">
							<div class="container">
								<div class="row">
									<div class="col-md-9">
										<div class="img-container img-container<?php echo $row_SacarPostGetImg['id'] ?>">
											<img src="<?php echo $row_SacarPostGetImg['imagen'] ?>">
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-md-9 docs-buttons">
										<div class="btn-group btn-group-crop">
											<button class="btn" id="closeModal<?php echo $row_SacarPostGetImg['id'] ?>" onclick="closeEditImgBox();" data-dismiss="modal" type="button">close</button>
											<button class="btn" data-method="getCroppedCanvas" type="button" onclick="saveEditImg('<?php echo $row_SacarPostGetImg['id'] ?>')">save</button>
										</div>
										<div class="modal fade docs-cropped" id="getCroppedCanvasModal<?php echo $row_SacarPostGetImg['id'] ?>" aria-hidden="true" aria-labelledby="getCroppedCanvasTitle" role="dialog" tabindex="-1">
											<div class="modal-dialog">
												<div class="modal-content">
													<div class="modal-body" id="modal-body<?php echo $row_SacarPostGetImg['id'] ?>"></div>
												</div>
											</div>
										</div>
										<textarea id="putData<?php echo $row_SacarPostGetImg['id'] ?>" style="display:none;"></textarea>
									</div>
								</div>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>

	    <script type="text/javascript">
			$(function() {
			    'use strict';

			    var console = window.console || {
			        log: function() {}
		        },
		        $alert = $('.docs-alert'),
		        $message = $alert.find('.message'),
		        showMessage = function(message, type) {
		            $message.text(message);

		            if (type) {
		                $message.addClass(type);
		            }

		            $alert.fadeIn();

		            setTimeout(function() {
		                $alert.fadeOut();
		            }, 3000);
		        };

			    // CROPPER
			    var idCrop = <?php echo $row_SacarPostGetImg['id'] ?>;

			    (function() {
			    	var imagenId = $('.img-container'+idCrop+' > img');
			    	var widthBox = $('body').width()-60;
			        var $image = imagenId,
			            $dataX = $('#dataX'),
			            $dataY = $('#dataY'),
			            $dataHeight = $('#dataHeight'),
			            $dataWidth = $('#dataWidth'),
			            options = {
			                minCanvasWidth: widthBox,
			                minCanvasHeight: 320,
			                minCropBoxWidth: 160,
			                minCropBoxHeight: 90,
			                minContainerWidth: widthBox,
			                minContainerHeight: 400,
			                aspectRatio: 16 / 9,
			                preview: '.img-preview'+idCrop,
			                crop: function(data) {
			                    $dataX.val(Math.round(data.x));
			                    $dataY.val(Math.round(data.y));
			                    $dataHeight.val(Math.round(data.height));
			                    $dataWidth.val(Math.round(data.width));
			                }
			            };
			        $image.on({
			            'build.cropper': function(e){},
			            'built.cropper': function(e){},
			            'cropstart.cropper': function(e){},
			            'cropmove.cropper': function(e){},
			            'cropend.cropper': function(e){},
			            'change.cropper': function(e){},
			            'zoom.cropper': function(e){}
			        }).cropper(options);

			        $(document.body).on('click', '[data-method]', function() {

			            var data = $(this).data(),
			                $target,
			                result;

			            if (!$image.data('cropper')) {
			                return;
			            }

			            if (data.method) {
			                data = $.extend({}, data); // Clone a new one

			                if (typeof data.target !== 'undefined') {
			                    $target = $(data.target);

			                    if (typeof data.option === 'undefined') {
			                        try {
			                            data.option = JSON.parse($target.val());
			                        } catch (e) {
			                            console.log(e.message);
			                        }
			                    }
			                }

			                result = $image.cropper(data.method, data.option);


			                if (data.method === 'getCroppedCanvas') {
			                    $('#getCroppedCanvasModal'+idCrop).find('#modal-body'+idCrop).html(result);

			                    var co = document.getElementById('modal-body'+idCrop).querySelector('canvas');
			                    var dast = co.toDataURL("image/jpeg", 0.9);
		                    	$('#putData'+idCrop).val(dast);
			                    // console.log('MMM-modalIMG-->',dast);
		                    	// var imp = $('#putData'+idCrop).val(dast);
		                    	// console.log('MMM-onINPUT-->',imp);

		                    	setTimeout(function(){
		                    		$('#closeModal'+idCrop).click();
		                    	},600);


			                    // document.getElementById(idData+idCrop).value = dast;
			                    // var coco = $('#modal-body'+idCrop).get(0);
			                    // console.log('modal-->',coco);
			                    // var urlCoco = coco.toDataURL("image/png").replace("image/png", "image/octet-stream");
			                    // console.log('modalURL-->',urlCoco);
			                    // var idData = $('#putData'+idCrop);
			                    // console.log('id--> ',idData);
			                    // console.log('base64--> ',dast);
			                }

			                if ($.isPlainObject(result) && $target) {
			                    try {
			                        $target.val(JSON.stringify(result));
			                    } catch (e) {
			                        console.log(e.message);
			                    }
			                }
			            }
			        });

			        // Options
			        $('.docs-options :checkbox').on('change', function() {
			            var $this = $(this),
			                cropBoxData,
			                canvasData;

			            if (!$image.data('cropper')) {
			                return;
			            }

			            options[$this.val()] = $this.prop('checked');

			            cropBoxData = $image.cropper('getCropBoxData');
			            canvasData = $image.cropper('getCanvasData');
			            options.built = function() {
			                $image.cropper('setCropBoxData', cropBoxData);
			                $image.cropper('setCanvasData', canvasData);
			            };

			            $image.cropper('destroy').cropper(options);
			        });

			    }());

				function CropAvatar($element) {
			        var idCrop = <?php echo $row_SacarPostGetImg['id'] ?>;
			        this.$container = $element;
			        this.$avatarModal = this.$container.find("#avatar-modal"+idCrop);
			        this.$avatarView = this.$container.find(".avatar-view"+idCrop);
			        this.$avatar = this.$avatarView.find("img");
			        this.$avatarPreview = this.$avatarModal.find(".avatar-preview");
			        this.init();
			    }

			    CropAvatar.prototype = {
			        constructor: CropAvatar,

			        support: {
			            fileList: !!$("<input type=\"file\">").prop("files"),
			            fileReader: !!window.FileReader,
			            formData: !!window.FormData
			        },

			        init: function() {
			            this.support.datauri = this.support.fileList && this.support.fileReader;

			            if (!this.support.formData){
			            	this.initIframe();
			            }

			            this.initModal();
			            this.addListener();

			        },

			        addListener: function() {
			            this.$avatarView.on("click", $.proxy(this.click, this));
			        },

			        initModal: function() {
			            this.$avatarModal.modal("hide");
			            this.initPreview();
			            
			        },

			        initPreview: function() {
			            var url = this.$avatar.attr("src");
			            this.$avatarPreview.empty().html('<img src="' + url + '">');
			        },

			        click: function() {
			            this.$avatarModal.modal("show");
			        }
			    };

			    $(function() {
			        var example = new CropAvatar($("#crop-avatar"));
			    });
			});	    
		</script>
	<?php } while($row_SacarPostGetImg = mysql_fetch_assoc($SacarPostGetImg)) ?>
	<script type="text/javascript">
		function saveEditImg(id){
			$('.header').removeClass('slideHeader');
			$('body').removeClass('bodyFixed');

			setTimeout(function(){
				var baseImg = $('#putData'+id).val();

				$.post(url + 'pages/postFunctions/subidaTemporalInsertBBDD.php',
					{ valor: baseImg, id: id }).done(function(htmlress) {
						$('#'+id).css('background-color','#09f');
				});
			},2300);
		}

		function openEditNoticePostWindow(id) {
	        $('body').addClass('bodyFixed');
	        $('.header').addClass('slideHeader');
	    }

		function closeEditImgBox(){
			$('body').removeClass('bodyFixed');
			$('.header').removeClass('slideHeader');
		}
	</script>
<?php } ?>
<?php mysql_free_result($SacarPostGetImg);?>