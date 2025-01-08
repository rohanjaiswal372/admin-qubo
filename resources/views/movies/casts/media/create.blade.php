@extends("app")

	  @section("content")
      <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
          <h1>
            Upload Media
            <small>Optional description</small>
          </h1>
          <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Level</a></li>
            <li class="active">Here</li>
          </ol>
        </section>

        <!-- Main content -->
        <section class="content">

  <!-- form start -->
                {!! Form::open(array("role"=>"form","files"=>true,"id"=>"upload-form")) !!}
				
                  <div class="box-body">				 
				  
				  
					<div class="form-group">
						  <div class="row">
						   <div class="form-group col-lg-3">
							  {!! Form::label('upload_object_id', 'Cast: ') !!}
							  {!! Form::select('upload_object_id', ["Select One"] + $object_id_selector , $object_id , ['class' => 'form-control upload-param', 'autocomplete'=>"off"] ) !!}
							</div>	   
						  </div>
					</div>
	
					
					<div class="form-group">
						  <div class="row">
						   <div class="form-group col-lg-3">
							  {!! Form::label('upload_media_type', 'Type: ') !!}
							  {!! Form::select('upload_media_type', ["Select One"] + $media_type_selector , "" , ['class' => 'form-control upload-param', 'autocomplete'=>"off"] ) !!}
							</div>	   
						  </div>
					</div>
				  
					<div class="form-group">

									
                                    <!-- Fine Uploader Thumbnails template w/ customization
                                ====================================================================== -->
                                <script type="text/template" id="qq-template-manual-trigger">
                                    <div class="qq-uploader-selector qq-uploader" qq-drop-area-text="Drop files here">
                                        <div class="qq-total-progress-bar-container-selector qq-total-progress-bar-container">
                                            <div role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" class="qq-total-progress-bar-selector qq-progress-bar qq-total-progress-bar"></div>
                                        </div>
                                        <div class="qq-upload-drop-area-selector qq-upload-drop-area" qq-hide-dropzone>
                                            <span class="qq-upload-drop-area-text-selector"></span>
                                        </div>
                                        <div class="buttons">
                                            <div class="qq-upload-button-selector qq-upload-button  btn btn-primary">
                                                <div>Select files</div>
                                            </div>
                                            <button id="trigger-upload" class="btn btn-primary">
                                                <i class="icon-upload icon-white"></i> Upload
                                            </button>
                                        </div>
                                        <span class="qq-drop-processing-selector qq-drop-processing">
                                            <span>Uploading, please wait...</span>
                                            <span class="qq-drop-processing-spinner-selector qq-drop-processing-spinner"></span>
                                        </span>
                                        <ul class="qq-upload-list-selector qq-upload-list" aria-live="polite" aria-relevant="additions removals">
                                            <li>
                                                <div class="qq-progress-bar-container-selector">
                                                    <div role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" class="qq-progress-bar-selector qq-progress-bar"></div>
                                                </div>
                                                <span class="qq-upload-spinner-selector qq-upload-spinner"></span>
                                                <img class="qq-thumbnail-selector" qq-max-size="100" qq-server-scale>
                                                <span class="qq-upload-file-selector qq-upload-file"></span>
                                                <span class="qq-edit-filename-icon-selector qq-edit-filename-icon" aria-label="Edit filename"></span>
                                                <input class="qq-edit-filename-selector qq-edit-filename" tabindex="0" type="text">
                                                <span class="qq-upload-size-selector qq-upload-size"></span>
                                                <button class="qq-btn qq-upload-cancel-selector qq-upload-cancel">Cancel</button>
                                                <button class="qq-btn qq-upload-retry-selector qq-upload-retry">Re-Upload</button>
                                                <button class="qq-btn qq-upload-delete-selector qq-upload-delete">Delete</button>
                                                <span role="status" class="qq-upload-status-text-selector qq-upload-status-text"></span>
                                            </li>
                                        </ul>
                            
                                        <dialog class="qq-alert-dialog-selector">
                                            <div class="qq-dialog-message-selector"></div>
                                            <div class="qq-dialog-buttons">
                                                <button class="qq-cancel-button-selector">Close</button>
                                            </div>
                                        </dialog>
                            
                                        <dialog class="qq-confirm-dialog-selector">
                                            <div class="qq-dialog-message-selector"></div>
                                            <div class="qq-dialog-buttons">
                                                <button class="qq-cancel-button-selector">No</button>
                                                <button class="qq-ok-button-selector">Yes</button>
                                            </div>
                                        </dialog>
                            
                                        <dialog class="qq-prompt-dialog-selector">
                                            <div class="qq-dialog-message-selector"></div>
                                            <input type="text">
                                            <div class="qq-dialog-buttons">
                                                <button class="qq-cancel-button-selector">Cancel</button>
                                                <button class="qq-ok-button-selector">Ok</button>
                                            </div>
                                        </dialog>
                                    </div>
                                </script>
                            
                                <style>
                                    #trigger-upload {
                                        color: white;
                                        background-color: #00ABC7;
                                        font-size: 14px;
                                        padding: 7px 20px;
                                        background-image: none;
                                    }
                            
                                    #fine-uploader-manual-trigger .qq-upload-button {
                                        margin-right: 15px;
                                    }
                            
                                    #fine-uploader-manual-trigger .buttons {
                                        width: 36%;
                                    }
                            
                                    #fine-uploader-manual-trigger .qq-uploader .qq-total-progress-bar-container {
                                        width: 60%;
                                    }
									#trigger-upload {
   										 background: #3c8dbc!important;
									}
									
									.qq-total-progress-bar{
										display:none;
									}
									
									.qq-progress-bar{
										background:#3c8dbc;	
									}
									.qq-upload-button{
										width:auto;
									}
                                </style>
                                    
                                                         
                             
                            <div id="fine-uploader-manual-trigger"></div>                                 
                                                            
                           <br clear="all" />  

					</div>
					
					
                  </div><!-- /.box-body -->


				  
                {!! Form::close() !!}
		
		
		
		
        </section><!-- /.content -->
      </div><!-- /.content-wrapper -->

@endsection


@section("footer_js")
 <script src="{{ asset("/js/fineuploader.js") }}" type="text/javascript"></script>
@endsection

