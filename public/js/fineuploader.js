var upload_params = {"_token":$('meta[name="csrf-token"]').attr('content'),
					 "object_id":$('#upload_object_id').val(),
					 "media_type":(typeof($("#upload_media_type").val()) != "undefined" ) ? $("#upload_media_type").val() : false,
					 "create_thumbnail": (typeof($("#upload_create_thumbnail").val()) != "undefined" ) ? $("#upload_create_thumbnail").val() : false,
					 "thumbnail_crop_options":$('#upload_thumbnail_crop_options').val()};
					 
var upload_extensions = ['jpg','jpeg','png','swf'];

$(document).ready(function(){
				
		if(typeof(brightcove_upload ) !="undefined" && brightcove_upload == true && typeof(bulk_upload) == "undefined" ){
				
			upload_extensions = ["mp4", "mov"];
			upload_types = {"show_preview":1,"episode_preview":2};
			
			if($("#upload_media_type option:selected").val()== upload_types.show_preview ){
				upload_params.object_id = $("#show_id option:selected").val();
			}else{
				upload_params.object_id = $("#upload_object_id option:selected").val();
			}
			
			$("#upload_media_type").change(function(){
				if($(this).val() == 1){
					upload_params.object_id = $("#show_id option:selected").val();
				}else{
					upload_params.object_id = $("#upload_object_id option:selected").val();
				}
			});
			
			$("#upload_object_id").change(function(){
				upload_params.media_type = upload_types.episode_preview;
				upload_params.object_id = $("#upload_object_id option:selected").val();
			});
			
			

		}else if(typeof(brightcove_upload ) !="undefined" && brightcove_upload == true){
			
			upload_extensions = ["mp4", "mov"];
			
			if(typeof(bulk_upload ) !="undefined" && bulk_upload == true){
			
				upload_params = {"_token":$('meta[name="csrf-token"]').attr('content'),
							     "object_id":$('#upload_object_id').val(),
								 "bulk":true}
								 
			}
			
		}

	
		$("#upload_create_thumbnail").click(function(){
			if($(this).is(":checked")){
				$(".thumbnail-crop-options").show();
				upload_params.create_thumbnail = true;
			}else{
				$(".thumbnail-crop-options").hide();
				upload_params.create_thumbnail = false;
			}
		});
	
		$(".upload-param").change(function(){
			if($(this).attr("id")=="upload_object_id"){
				upload_params.object_id = $(this).val();
			}else if($(this).attr("id")=="upload_object_id_episode"){
				upload_params.object_id = $(this).val();
			}else if($(this).attr("id")=="upload_media_type"){
				upload_params.media_type = $(this).val();
			}else if($(this).attr("id")=="upload_thumbnail_crop_options"){
				upload_params.thumbnail_crop_options = $(this).val();
			}
			manualUploader.setParams(upload_params);
			console.log(upload_params);
		});


       var manualUploader = new qq.FineUploader({
            element: document.getElementById('fine-uploader-manual-trigger'),
            template: 'qq-template-manual-trigger',
            request: {
			    endpoint: _url('upload/endpoint/?_token='+upload_params._token)
            },
            validation: {
                allowedExtensions: upload_extensions,
				sizeLimit: 5000000000 // 500 MiB				
            },			
		    chunking: {
				enabled: false,
				concurrent: {
					enabled: false
				},
				success: {
					endpoint: _url('upload/chunking/endpoint')
				}
			},			
            thumbnails: {
				
                placeholders: {
                    waitingPath: _url('plugins/fine-uploader/v5.2.1/placeholders/waiting-generic.png'),
                    notAvailablePath: _url('plugins/fine-uploader/v5.2.1/placeholders/not_available-generic.png')
                }
            },
            autoUpload: false,
		   callbacks: {
				onError: function(id, name, errorReason, xhrOrXdr) {
					//alert(qq.format("Error on file number {} - {}.  Reason: {}", id, name, errorReason));
					console.log(errorReason);
				},
				onComplete:  function(id, name, responseJSON , xhrOrXdr) {
					if(typeof upload_complete == 'function'){
						upload_complete();
					}
				}
			},			
            debug: true
        });

        qq(document.getElementById("trigger-upload")).attach("click", function(event) {
			event.preventDefault();
            manualUploader.uploadStoredFiles();
        });	
		
		manualUploader.setParams(upload_params);

	
});
