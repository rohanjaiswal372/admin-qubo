@extends("app")
@section("header")

@stop
@section("content")

        <!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="pull-left">
            <h3>Editing Post: <strong>{{$item->title}}</strong> <a class='btn btn-default'
                                                                   href="{{ URL::to($item->url()) }}"
                                                                   target="_blank"><i class="fa fa-eye"></i> Preview
                    Post</a></h3>

        </div>
        <div class="pull-right">
            <div class="btn-group">
                <a href="{{ route('posts.create') }}" class="btn btn-success"><i class="fa fa-plus"></i>&nbsp;New
                    Post</a> <a href="{{ URL::to('posts/index/'.$item->type_id) }}"
                                class="btn btn-primary"><i class="fa fa-undo"></i> All Posts</a>
                <a href="{{ URL::to('/posts/remove/'.$item->id) }}"
                   title="Delete"
                   data-toggle="tooltip"
                   class="btn btn-danger"
                   onclick="return confirm('Are you sure you want to remove this?');"><i class="fa fa-times"></i> Delete
                    Post</a>
            </div>
        </div>
        <div class="clearfix"></div>

    </section>

    <!-- Main content -->
    <section class="content">

        {{ HTML::ul($errors->all()) }}
        {!! Form::model($item, array('route' => array('posts.update', $item->id), 'method' => 'PUT', 'files' => TRUE)) !!}
        <div class="row">
            <div class="col-md-6">
                <div class="box box-primary pad">
                    <div class="box-body">
                        <div class="form-group col-md-6">
                            {!! Form::label('active', 'Active (Push Live): ') !!}
                            {!! Form::checkbox('active', Input::old('active'), ['class' => 'form-control']) !!}
                            <p class="help-hint">Check to make active.</p>
                        </div>

                        <div class="form-group col-md-6">

                            {!! Form::label('activates_at', 'Start Date: ') !!}
                            <small> Date that post will go live on the site.</small>
                            <div class="input-group">
                                <div class="input-group-addon">
                                    <i class="fa fa-calendar"></i>
                                </div>
                                {!! Form::text('activates_at',Carbon::parse($item->activates_at)->format("m/d/Y"), ['class' => 'form-control datepicker', 'placeholder' => 'Start Date']) !!}
                            </div>

                        </div>
                        <div class="form-group col-md-6">
                            {!! Form::label('author', 'Author: ') !!}
                            {!! Form::text('author', Input::old('author'), ['class' => 'form-control', 'placeholder' => 'Author']) !!}
                        </div>
                        <div class="form-group col-md-6">
                            {!! Form::label('created_at', 'Posted On: ') !!}
                            <div class="input-group">
                                <div class="input-group-addon">
                                    <i class="fa fa-calendar"></i>
                                </div>
                                {!! Form::text('created_at', Carbon::parse($item->created_at)->format('m/d/Y g:i a'), ['class' => 'form-control datetimepicker ', 'placeholder' => 'Posted On']) !!}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="box box-primary pad">
                    <div class="box-header">
                        <div class="box-title"><i class="fa fa-info-circle"></i> Post Info:</div>
                    </div>
                    <div class="box-body">
                        <div class="form-group">
                            {!! Form::label('type_id', 'Type of Post: ') !!}
                            {!! Form::select('type_id',  [""=>"Select One"] +  $post_type_selector , $item->type_id , ['class'=>'type_id_selector form-control','required' => 'required'] ) !!}
                        </div>
                        <div class="form-group">
                            {!! Form::label('title', 'Title: ') !!}
                            {!! Form::text('title', $item->title, ['class' => 'form-control', 'placeholder' => 'Page Title', 'required' => 'required']) !!}
                        </div>

                        <div class="form-group">
                            {!! Form::label('pod_tagline', 'POD Tagline: ') !!}
                            {!! Form::text('pod_tagline', $item->pod_tagline, ['class' => 'form-control', 'placeholder' => 'POD Tagline']) !!}
                        </div>

                        <div class="form-group">
                            {!! Form::label('path', 'Path: ') !!}<span class="help-text"><small> This is auto-generated
                                    from the post title.
                                </small></span>
                            {!! Form::text('path', $item->path, ['class' => 'form-control', 'placeholder' => 'Page Path']) !!}
                        </div>

                    </div>
                </div>
            </div>
            <div class="col-md-6" id="video_section">
                @include('templates.partials.videoform')
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary pad">
                    <div class="box-header">
                        <h3 class="box-title"><i class="fa fa-file-text-o"></i> Content:</h3>
                    </div>
                    <div class="box-body">
                        <div class="form-group">
                            {!! Form::textarea('content', Input::old('content'), ['class' => 'form-control editor', 'id'=>'content-body']) !!}
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="box box-primary pad">
                    <div class="box-header">
                        <h3 class="box-title"><i class="fa fa-paragraph"></i> Summary:</h3>
                    </div>
                    <div class="box-body">

                        <div class="form-group">

                            {!! Form::textarea('summary', Input::old('summary'), ['class' => 'form-control editor', 'id'=>'content-summary']) !!}
                            <p class="help-hint">Keep the summary short.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary pad">
                    <div class="box-header">
                        <h3 class="box-title"><i class="fa fa-picture-o"></i> Images:</h3>
                    </div>
                    <div class="box-body">

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    {!! Form::label('image', 'Primary Blog Image (Large): ') !!}
                                    {!! Form::file('image') !!}
                                    <p class="help-block">This is the primary image that will be displayed when a user
                                        is reading the blog article.</p>
                                </div>

                                @if (count( $item->imageDefault) > 0 )
                                    <table class="table no-margin">
                                        <thead>
                                        <tr>
                                            <th>Image</th>
                                            <th>Action</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach( $item->imageDefault as $image )
                                            <tr>
                                                <td>
                                                    <img class="img img-thumbnail" src="{{ image($image->url) }}"/>
                                                </td>
                                                <td><a href="{{ URL::to('/image/remove/'.$image->id) }}"
                                                       data-toggle="tooltip"
                                                       title="Remove"
                                                       onclick="return confirm('Are you sure? This will remove the image from our system.');"><i
                                                                class="fa fa-trash fa-2x text-danger"></i></a>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                @endif

                            </div>
                            <div class="col-md-6">

                                <div class="form-group">
                                    {!! Form::label('image_secondary', 'Secondary Blog Image (Listing/Thumbnail): ') !!}
                                    {!! Form::file('image_secondary') !!}
                                    <p class="help-block">This image is used when listing the blog article on the main
                                        blog page.</p>
                                </div>

                                @if (count( $item->imageThumbnail) > 0 )
                                    <table class="table no-margin">
                                        <thead>
                                        <tr>
                                            <th>Image</th>
                                            <th>Action</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach( $item->imageThumbnail as $image )
                                            <tr>
                                                <td>
                                                    <img class="img img-thumbnail" src="{{ image($image->url) }}"/>
                                                </td>
                                                <td><a href="{{ URL::to('/image/remove/'.$image->id) }}"
                                                       data-toggle="tooltip"
                                                       title="Remove"
                                                       onclick="return confirm('Are you sure? This will remove the image from our system.');"><i
                                                                class="fa fa-trash fa-2x text-danger"></i></a>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                @endif

                            </div>
                            <div class="col-md-12">

                                <div class="form-group">
                                    {!! Form::label('image_general', 'General use images within blog: ') !!}
                                    {!! Form::file('image_general') !!}
                                    <p class="help-block">You can upload images to use within the blog content
                                        itself.</p>
                                </div>

                                @if (count( $item->imageGeneral) > 0 )
                                    <table class="table no-margin">
                                        <thead>
                                        <tr>
                                            <th>Image</th>
                                            <th>URL</th>
                                            <th>Action</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach( $item->imageGeneral as $image )
                                            <tr>
                                                <td>
                                                    <img src="{{ image($image->url) }}"
                                                         class="img img-thumbnail"/>
                                                </td>
                                                <td> {{ image($image->url) }} </td>
                                                <td><a href="{{ URL::to('/image/remove/'.$image->id) }}"
                                                       data-toggle="tooltip"
                                                       title="Remove"
                                                       onclick="return confirm('Are you sure? This will remove the image from our system.');"><i
                                                                class="fa fa-trash fa-2x text-danger"></i></a>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                @endif
                            </div>
                        </div>{{-- end row --}}
                    </div>
                </div>
            </div>
        </div>
        @include('templates.partials.savebar')
        <div class="box pad" id="uploader_drop_zone">
            <div class="box-header">
                <div class="box-title">Upload Form:</div>
            </div>
            <div class="box-body">
                {!! Form::open(array("role"=>"form","files"=>TRUE,"id"=>"upload-form")) !!}
                {!! Form::hidden('upload_object_id', $item->id ,['id' => 'upload_object_id']) !!}
                {!! Form::hidden('upload_media_type', '6' ,['id' => 'upload_media_type']) !!}

                @include('templates.uploader')
                {!! Form::close() !!}
            </div>
        </div>

    </section><!-- /.content -->
</div><!-- /.content-wrapper -->

@include('shows.partials.videomodal')
@stop
@section('footer_js')
    <script src="//sadmin.brightcove.com/js/BrightcoveExperiences.js"></script>
    <script src="{{ asset('js/brightcove/brightcove-player.js') }}"></script>
    <script>

        var upload_extensions = ['mp4','mov'];

        var manualUploader = new qq.FineUploader({
            element: document.getElementById('fine-uploader-manual-trigger'),
            template: 'qq-template-manual-trigger',
            request: {
                endpoint: _url('upload/endpoint/?_token=' + getUploadParams()._token + '&media_type=' + getUploadParams().media_type + '&object_id=' + getUploadParams().object_id)
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
            autoUpload: true,
            callbacks: {
                onError: function (id, name, errorReason, xhrOrXdr) {
                    console.log(errorReason);
                },
                onValidate: function (id, name, responseJSON, xhrOrXdr) {

                },
                onComplete: function (id, name, responseJSON, xhrOrXdr) {


                    $.ajax({
                        method: "get",
                        url: "/posts/videos/update/{{$item->id}}"

                    }).success(function (data) {
                           var response = JSON.parse(data);
                        $("#video_section").html(response.html);
                        scrollTo("#brightcove_id");
                    });
                }
            },
            debug: false
        });

        qq(document.getElementById("trigger-upload")).attach("click", function (event) {
            event.preventDefault();
            manualuploader.setParams(getUploadParams());
            manualUploader.uploadStoredFiles();
        });

        function getUploadParams() {
            var params = {
                "_token": $('meta[name="csrf-token"]').attr('content'),
                "object_id": $('#upload_object_id').val(),
                "media_type": (typeof($("#upload_media_type").val()) != "undefined" ) ? $("#upload_media_type").val() : false
            };
            return params;
        }

        function scrollTo(id) {
            $('html, body').animate({
                scrollTop: $(id).offset().top
            }, 1000);
        }

        function trigger_upload(media_type, uploader) {

            var dropZone = $('#uploader_drop_zone');
            dropZone.collapse('show');
            scrollTo(dropZone);
            $('#upload_media_type').val(media_type);
            dropZone.find('.box-title').html('Upload: <b>Post Video</b>');

            uploader.setParams(getUploadParams());

            if (dropZone.hasClass('in')) {
                $('input[type=file]').click();
                return false;
            } else {
                dropZone.toggleClass('box-warning').find('.box-header').toggleClass('with-border');
            }

        }

        // BUTTONS - ACTIONS //
        $("#upload_new_video").one('click', function () {
            trigger_upload(6, manualUploader);
        });

    </script>

@stop

