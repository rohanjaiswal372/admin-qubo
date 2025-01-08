@extends("app")
@section("header")
    <style>
        .remove-btn {
            position: absolute;
            bottom: 7px;
            right: 21px;
        }

        .video_preview img {
            width: 100%;
            max-width: 100%;
        }

        .video_preview em {
            position: absolute;
            left: 50%;
            top: 40%;
        }

        .video_preview em.play-btn {
            color: white;
        }

    </style>
    @stop
    @section("content")
            <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="pull-left">
                <h3>Editing : <strong style="color:{{$episode->show->color}}">{{ $episode->show->name}}</strong>
                    Episode {{$episode->episode_number.": ".$episode->name}}</h3>
                @if($episode->show->logo)
                    <img class="img img-thumbnail col-sm-6" style="background-color:{{$episode->show->color}}"
                         src="{{image($episode->show->logo->url)}}"/>
                @endif
            </div>
            <div class="pull-right">
                <div class="btn-group">
                    <a class="btn btn-default @if(is_null($episode_buttons['prev'])) disabled @endif"
                       href="{{ url('shows/'.$episode->show->id.'/episodes/'.$episode_buttons['prev']."/edit") }}"
                       title="Previous Episode"
                       data-toggle="tooltip"><i class="fa fa-arrow-left"></i></a>
                    <a href="{{ url('shows/episodes/'.$episode->show->id) }}"
                       class="btn btn-primary"><i class="fa fa-undo"></i> All Episodes</a>
                    <a class="btn btn-default  @if(is_null($episode_buttons['next'])) disabled @endif"
                       href="{{ url('shows/'.$episode->show->id.'/episodes/'.$episode_buttons['next']."/edit") }}"
                       title="Next Episode"
                       data-toggle="tooltip"><i class="fa fa-arrow-right"></i></a>
                </div>
            </div>
            <div class="clearfix"></div>
        </div>
        <!-- Main content -->
        <div class="content">
            <div class="row">
                <div class="col-md-7">
                    <div class="box pad">
                        <div class="box-header">
                            <h3 class="box-title">Episode Info: @if($episode->new)<span class="label label-danger">NEW EPISODE</span>@endif
                            </h3>
                            <div class="box-tools">
                                @if($episode->upcoming_program()->first())
                                    <label>Next Air Date:
                                        <span class="label label-info"><i class="fa fa-calendar"></i> {{Carbon::parse($episode->upcoming_program()->first()->airdate)->toDayDateTimeString()}}</span></label>
                                @endif
                            </div>
                        </div>
                        <div class="box-body">
                            {!! Form::model(array('episode' => $episode),
                                         array('route' => array('shows.episodes.update', $show->id,$episode->id ) , 'method' => 'PATCH')) !!}

                            <div class="form-group">
                                {!! Form::label('episode[name]', 'Episode Name: ') !!}
                                {!! Form::text('episode[name]', ($episode->name)? $episode->name : "No Name entered", ['class' => 'form-control', 'placeholder' => '']) !!}
                            </div>
                            <div class="form-group col-md-4">
                                {!! Form::label('episode[season]', 'Season: ') !!}
                                {!! Form::text('episode[season]', $episode->season, ['class' => 'form-control', 'placeholder' => '']) !!}
                            </div>

                            <div class="form-group col-md-4">
                                {!! Form::label('episode[episode_number]', 'Episode Number: ') !!}
                                {!! Form::text('episode[episode_number]', $episode->episode_number, ['class' => 'form-control', 'placeholder' => '']) !!}
                            </div>
                            <div class="form-group col-md-4">
                                {!! Form::label('episode[rating]', 'Rating: ') !!}
                                {!! Form::text('episode[rating]', $episode->rating, ['class' => 'form-control', 'placeholder' => '']) !!}
                            </div>
                            <div class="form-group col-md-6">
                                {!! Form::label('episode[new_episode_starts_at]', 'Show as New Start Date: ') !!}
                                <div class="input-group">
                                    <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                                    {!! Form::text('episode[new_episode_starts_at]', ($episode->new_episode_starts_at)?Carbon::parse($episode->new_episode_starts_at)->format('m/d/Y') : "", ['class' => 'form-control datepicker', 'placeholder' => '']) !!}
                                </div>
                            </div>
                            <div class="form-group col-md-6">
                                {!! Form::label('episode[new_episode_ends_at]', 'Show as New End Date: ') !!}
                                <div class="input-group">
                                    <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                                    {!!  Form::text('episode[new_episode_ends_at]', ($episode->new_episode_ends_at)?Carbon::parse($episode->new_episode_ends_at)->format('m/d/Y'): "", ['class' => 'form-control datepicker', 'placeholder' => '']) !!}
                                </div>
                            </div>

                            <div class="form-group col-md-12">
                                {!! Form::label('episode[description]', 'Description: ') !!}
                                {!! Form::textarea('episode[description]', $episode->description, ['class' => 'form-control', 'placeholder' => '']) !!}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-5">
                    <div class="box pad">
                        <div class="box-header">
                            <h3 class="box-title">Episode Video:</h3>

                            <div class="box-tools pull-right">
                                @if($episode->videos)
                                    <a href="#" title="Preview" data-toggle="tooltip"
                                       data-videoid="{{ $episode->videos->brightcove_id }}"
                                       class="video_preview btn btn-box-tool"><i
                                                class="fa fa-play-circle fa-2x"></i></a>
                                    <a class="btn btn-box-tool"
                                       href="{{ route('videos.edit', $episode->videos->id) }}"
                                       title="Edit"
                                       data-toggle="tooltip"><i class="fa fa-pencil-square fa-2x"></i></a>
                                    {{--<a href="{{ URL::to('shows/episodes/thumbnail/'.$episode->videos->id) }}" title="Upload New Thumbnail" data-toggle="tooltip"
                                        data-videoid="{{ $episode->videos->brightcove_id }}"
                                        class="btn btn-box-tool"><i
                                                 class="fa fa-cloud-upload fa-2x"></i></a> todo: add in thumbnail uploader replacement--}}
                                    <a href="{{ URL::to('shows/videos/delete/'.$episode->videos->id) }}"
                                       title="Remove"
                                       data-toggle="tooltip"
                                       class="btn btn-box-tool"
                                       onclick="return confirm('Are you Sure you want to delete this episode video preview - This will remove it from the Brightcove site and all other locations')"
                                    ><i class="fa fa-trash fa-2x text-danger"></i></a>
                                @endif
                            </div>
                        </div>

                        <div class="box-body">
                            <div class="video-preview pad text-center">
                                @if($episode->videos && $episode->videos->brightcove_id)
                                    <a href="#"
                                       data-videoid="{{ $episode->videos->brightcove_id  }}"
                                       class="video_preview"> <img
                                                class="img-responsive"
                                                src="{{ (!is_null($episode->preview->still()))? $episode->preview->still() : "missing image" }}"/>
                                        <em class="fa fa-play-circle-o play-btn fa-4x"></em></a>

                                @else
                                    <a class="video_preview_add"
                                       title="Enter a Brightcove Video ID below and save to add a video preview to this episode"
                                       data-toggle="tooltip"> <em class="fa fa-plus-circle text-success fa-4x"></em>
                                    </a>
                                    <a href="#" title="Upload new Preview"
                                       data-toggle="tooltip" id="upload_preview">
                                        <em class="fa fa-cloud-upload fa-4x"></em></a>
                                @endif
                                <br class="cleafix">
                                <div class="form-group col-md-6">
                                    @if($episode->videos)
                                        {!! Form::label('episode[brightcove_id]', 'Video ID: ') !!}
                                        {!! Form::input('number','episode[brightcove_id]', ($episode->videos->brightcove_id)? $episode->videos->brightcove_id : " No ID Entered", ['class' => 'form-control','id' => 'brightcove_id']) !!}
                                    @else
                                        {!! Form::label('episode[brightcove_id]', 'Video ID: ') !!}
                                        {!! Form::input('number','episode[brightcove_id]', '', ['class' => 'form-control', 'placeholder' => 'Brightcove ID','id' => 'brightcove_id']) !!}
                                    @endif
                                </div>
                                <div class="form-group col-md-6">
                                    {!! Form::label('episode[published_at]', 'Publication Date: ') !!}
                                    <div class="input-group">
                                        <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                                        {!! Form::text('episode[published_at]', ($episode->published_at)? Carbon::parse($episode->published_at)->format('m/d/Y'): "", ['class' => 'form-control datepicker', 'placeholder' => '']) !!}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            @include('templates.partials.savebar')
            @include('shows.episodes.partials.images')

            <div class="box pad" id="uploader_drop_zone">
                <div class="box-header">
                    <h3 class="box-title">Upload Form:</h3>
                    <div class="box-tools pull-right"></div>
                </div>
                <div class="box-body pad">
                    {!! Form::open(array("role"=>"form","files"=>TRUE,"id"=>"upload-form")) !!}
                    {!! Form::hidden('upload_object_id', $episode->id ,['id' => 'upload_object_id']) !!}
                    <div class="form-group col-md-3 col-md-offset-1">
                        {!! Form::label('upload_media_type', 'Upload Type:') !!}
                        {!! Form::select('upload_media_type', ['6'=>'Image', '2'=>'Video'], "" ,['class'=> 'form-control upload_media_type', 'id' => 'upload_media_type']) !!}

                    </div>
                    <div class="form-group upload_create_thumbnail col-md-3 col-md-offset-1">

                        {!! Form::label('upload_create_thumbnail', 'Create a Thumbnail for this image') !!}
                        <input id="upload_create_thumbnail"
                               type="checkbox"
                               value="false"
                               class="upload-param"/>

                    </div>

                    <div class="form-group thumbnail-crop-options col-md-2 ">

                        {!! Form::label('upload_thumbnail_crop_options', 'Thumbnail Crop Region:') !!}
                        {!! Form::select('upload_thumbnail_crop_options', ["Select One"] + ["T" => "Top", "B" => "Bottom", "L" => "Left", "R" => "Right", "C" => "Center"] , "" , ['class' => 'form-control upload-param', 'autocomplete'=>"off"] ) !!}
                        <small>The System will create a thumbnail based on the selected crop region.</small>
                    </div>
                    <div class="col-md-12">
                        @include('templates.uploader')
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div><!-- /.content -->
    </div><!-- /.content-wrapper -->
    @include('shows.partials.videomodal')
@stop
@section('footer_js')
    <script src="{{ asset('js/brightcove/videojs-player.js') }}"></script>
    <script>

        var upload_extensions = ['jpg', 'jpeg', 'png', 'swf', 'mp4'];
        var dropZone = $('#uploader_drop_zone');
        var fields = ['.thumbnail-crop-options', '.upload_create_thumbnail'];

        var manualUploader = new qq.FineUploader({
            element: document.getElementById('fine-uploader-manual-trigger'),
            template: 'qq-template-manual-trigger',
            request: {
                endpoint: _url('upload/endpoint/?_token=' + getUploadParams()._token)
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
                onError: function (id, name, errorReason, xhrOrXdr) {
                    console.log(errorReason);
                },
                onValidate: function (id, name, responseJSON, xhrOrXdr) {

                },
                onComplete: function (id, name, responseJSON, xhrOrXdr) {

                    $.ajax({
                        method: "get",
                        url: "/shows/episodes/images",
                        data: {
                            id: {{$episode->id}}
                        },

                    }).success(function (data) {
                        $("#episode_images").html(data.html);
                    });
                }
            },
            debug: false
        });

        qq(document.getElementById("trigger-upload")).attach("click", function (event) {
            event.preventDefault();
            manualUploader.setParams(getUploadParams());
            manualUploader.uploadStoredFiles();
        });

        // BUTTONS - ACTIONS //
        $("#upload_preview").one('click', function (e) {
            trigger_upload(2);
        });

        $(".add_episode_photo").one('click', function (e) {
            trigger_upload(6);
        });

        // END BUTTONS

        $('#upload_media_type').on('change', function () {

            var media_type = $('#upload_media_type option:selected').val();

            if (media_type == "2") {
                // episode previews
                dropZone.find('.box-title').html('Upload: <b>Episode Preview Video</b>');
                $(fields).each(function (idx, val) {
                    $(val).hide();
                });
            }
            else {
                // episode images   case 6:
                dropZone.find('.box-title').html('Upload: <b>Episode Images</b>');
                $(fields).each(function (idx, val) {
                    $(val).show()
                });
            }
        });

        function getUploadParams() {
            var params = {
                "_token": $('input[name="_token"]').val(),
                "object_id": $('#upload_object_id').val(),
                "media_type": (typeof($("#upload_media_type").val()) != "undefined" ) ? $("#upload_media_type").val() : false,
                "create_thumbnail": (typeof($("#upload_create_thumbnail").val()) != "undefined" ) ? $("#upload_create_thumbnail").val() : false,
                "thumbnail_crop_options": $('#upload_thumbnail_crop_options').val()
            };
            return params;
        }

        function trigger_upload(media_type) {

//            $('#upload_media_type').val(media_type);
//
//            if(media_type == "2") {
//                // episode previews
//                dropZone.find('.box-title').html('Upload: <b>Episode Preview Video</b>');
//                $(fields).each(function (idx, val) {
//                    $(val).hide();
//                });
//            }
//            else{
//                // episode images   case 6:
//                dropZone.find('.box-title').html('Upload: <b>Episode Images</b>');
//                $(fields).each(function (idx, val) {
//                    $(val).show()
//                });
//            }

            dropZone.find('input[type="file"]').trigger('click', function (e) {
                e.preventDefault();
                alert("clicked");
                manualUploader.setParams(getUploadParams());
            });

        }
    </script>
@stop
