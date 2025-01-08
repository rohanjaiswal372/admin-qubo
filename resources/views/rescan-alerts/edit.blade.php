@extends("app")

@section("content")
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1 class="pull-left"><i class="fa fa-exclamation-triangle"></i> Edit Rescan Alert - {{$item->name}}</h1>
            <div class="pull-right">
                <div class="btn-group">
                    <a href="{{ route('rescan-alerts.create') }}" class="btn btn-success"><i class="fa fa-plus"></i>&nbsp;New
                    </a>
                    <a class="btn btn-primary" href="{{route('rescan-alerts.index')}}"><i class="fa fa-list"></i> Show
                        All</a>
                </div>
            </div>
        </section>
        <hr class="clearfix"/>

        {!! Form::model($item, array('route' => array('rescan-alerts.update', $item->id), 'method' => 'PUT', 'files'=> TRUE)) !!}

        <section class="content">
            <div class="row">

                <div class="col-md-12">

                    <div class="box box-primary pad">
                        <div class="box-header">
                            <h2>Rescan Page Content</h2>
                        </div>
                        <div class="box-body">

                            {{ HTML::ul($errors->all()) }}

                            <div class="form-group">
                                {!! Form::label('name', 'Name: ') !!}
                                {!! Form::text('name', Input::old('name'), ['class' => 'form-control', 'placeholder' => 'Name']) !!}
                            </div>

                            <div class="form-group">
                                {!! Form::label('title', 'Page Title: ') !!}
                                {!! Form::text('title', Input::old('title'), ['class' => 'form-control', 'placeholder' => 'Page Title']) !!}
                            </div>

                            <div class="form-group">
                                {!! Form::label('path', 'Page Path: ') !!}
                                {!! Form::text('path', Input::old('path'), ['class' => 'form-control', 'placeholder' => 'Page Path']) !!}
                            </div>

                            <div class="form-group">
                                {!! Form::label('meta_keywords', 'Meta Keywords: ') !!}
                                {!! Form::text('meta_keywords', Input::old('meta_keywords'), ['class' => 'form-control', 'placeholder' => 'Meta Keywords']) !!}
                            </div>

                            <div class="form-group">
                                {!! Form::label('meta_description', 'Meta Description: ') !!}
                                {!! Form::text('meta_description', Input::old('meta_description'), ['class' => 'form-control', 'placeholder' => 'Meta Description']) !!}
                            </div>

                            <div class="form-group">
                                <div class="checkbox">
                                    <label title="" data-toggle="tooltip">
                                        {!! Form::checkbox('active','active', ('active' ) ? "0":"" ,['class'=> 'checkbox']) !!}
                                        Active </label>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="checkbox">
                                    <label title="" data-toggle="tooltip">
                                        {!! Form::checkbox('content_geo_enabled','content_geo_enabled', ('content_geo_enabled' ) ? "0":"" ,['class'=> 'checkbox']) !!}
                                        Display this rescan page to targeted postal code areas only </label>
                                </div>
                            </div>

                            <div class="form-group">
                                {!! Form::label('content', 'Content: ') !!}
                                {!! Form::textarea('content', Input::old('content'), ['class' => 'form-control ckeditor']) !!}
                            </div>

                            <div class="form-group">
                                {!! Form::label('postal_codes', 'Postal Codes: ') !!}
                                <p>Ex: 33401,33402,33403 <br/>( Separate by white spaces, commas or line breaks) </p>
                                {!! Form::textarea('postal_codes', is_array($item->postal_codes) ? implode(",",$item->postal_codes) : "" , ['class' => 'form-control']) !!}
                            </div>

                            <div class="form-group">
                                {!! Form::label('start_end_time', 'Select Start & End Dates / Times: ') !!}
                                {!! Form::text('start_end_time', $item->starts_and_end_dates , ['class' => 'form-control', 'required' => 'required','placeholder' => 'Click to select your range']) !!}
                            </div>

                        </div>
                    </div>
                </div>

                <div class="col-md-6">

                    <div class="box box-primary pad">
                        <div class="box-header">
                            <h2>Rescan Navbar</h2>
                            <br/>
                            <p>This navbar works with cookies, and will be displayed until it is manually closed by the
                                user. The cookie is set to expire in 24 hrs.</p>

                        </div>
                        <div class="box-body">

                            <div class="form-group">
                                <div class="checkbox">
                                    <label title="" data-toggle="tooltip">
                                        {!! Form::checkbox('navbar_active','navbar_active', ('navbar_active' ) ? "0":"" ,['class'=> 'checkbox']) !!}
                                        Active </label>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="checkbox">
                                    <label title="" data-toggle="tooltip">
                                        {!! Form::checkbox('navbar_ubiquitous','navbar_ubiquitous', ('navbar_ubiquitous' ) ? "0":"" ,['class'=> 'checkbox']) !!}
                                        Display this navbar on all pages </label>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="checkbox">
                                    <label title="" data-toggle="tooltip">
                                        {!! Form::checkbox('navbar_geo_enabled','navbar_geo_enabled', ('navbar_geo_enabled' ) ? "0":"" ,['class'=> 'checkbox']) !!}
                                        Display this navbar to targeted postal code areas only </label>
                                </div>
                            </div>

                            <div class="form-group">
                                {!! Form::label('navbar_content', 'Navbar Content: ') !!}
                                {!! Form::textarea('navbar_content', Input::old('navbar_content'), ['class' => 'form-control ckeditor']) !!}
                            </div>

                        </div>
                    </div>

                </div>

                <div class="col-md-6">

                    <div class="box box-primary pad">
                        <div class="box-header">
                            <h2>Rescan Modal Window</h2>
                            <br/>
                            <p>This modal window works with cookies, and will display when a user visits the site for
                                the first time. The cookie is set to expire in 24 hrs.</p>
                        </div>
                        <div class="box-body">

                            <div class="form-group">
                                <div class="checkbox">
                                    <label title="" data-toggle="tooltip">
                                        {!! Form::checkbox('modal_active','modal_active', ('modal_active' ) ? "0":"" ,['class'=> 'checkbox']) !!}
                                        Active </label>
                                </div>
                            </div>

                            <div class="form-group">
                                <div class="checkbox">
                                    <label title="" data-toggle="tooltip">
                                        {!! Form::checkbox('modal_geo_enabled','modal_geo_enabled', ('modal_geo_enabled' ) ? "0":"" ,['class'=> 'checkbox']) !!}
                                        Display this modal window to targeted postal code areas only </label>
                                </div>
                            </div>

                            <div class="form-group">
                                {!! Form::label('modal_title', 'Modal Window Title: ') !!}
                                {!! Form::text('modal_title', Input::old('modal_title'), ['class' => 'form-control', 'placeholder' => 'Modal Window Title']) !!}
                            </div>

                            <div class="form-group">
                                {!! Form::label('modal_content', 'Modal Window Content: ') !!}
                                {!! Form::textarea('modal_content', Input::old('modal_content'), ['class' => 'form-control ckeditor']) !!}
                            </div>

                        </div>
                    </div>
                </div>

                <div class="col-md-6" id="video_section">
                    @include('templates.partials.videoform')
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
                    {!! Form::hidden('upload_media_type', '10' ,['id' => 'upload_media_type']) !!}

                    @include('templates.uploader')
                    {!! Form::close() !!}
                </div>
            </div>
        </section><!-- /.content -->
    </div><!-- /.content-wrapper -->
    @include('shows.partials.videomodal')
@stop
@section('footer_js')
    <script>
        CKEDITOR.config.allowedContent = true;
    </script>
    <script src="//sadmin.brightcove.com/js/BrightcoveExperiences.js"></script>
    <script src="{{ asset('js/brightcove/brightcove-player.js') }}"></script>
    <script>

        var upload_extensions = ['mp4', 'mov'];

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
                        url: "/rescan-alerts/videos/update/{{$item->id}}"

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
            dropZone.find('.box-title').html('Upload: <b>Rescan Video</b>');

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
            trigger_upload(10, manualUploader);
        });

    </script>

@stop

