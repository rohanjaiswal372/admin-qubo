@extends("app")
@section('header');

@stop
@section("content")
        <!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="pull-left">
            <h3>Edit Advertisement @if(!$item->active):
                <span class="text-danger"> Inactive <i class="fa fa-ban"></i></span>
                @endif
                @if($item->expired)
                    <span class="text-danger"> This Ad has expired <i class="fa fa-ban"></i></span>
                @else
                    @if($item->morphable_type == "App\Post" || $item->morphable_type == "App\Page"  )
                        <a href="{{$item->advertisedItem->url()}}"
                           class="pull-right btn btn-sm btn-default"
                           target="_blank"><i class="fa fa-eye"></i> View</a>
                    @endif
                @endif

            </h3>
        </div>
        <div class="pull-right">
            <div class="btn-group">
                <a href="{{ URL::previous() }}" class="btn btn-primary"><i class="fa fa-undo"></i> Back</a>
                <a href="{{ route('advertisements.create') }}" class="btn btn-success"><i class="fa fa-plus"></i>&nbsp;New
                    Ad</a> <a href="{{ URL::to('/advertisements/remove/'.$item->id) }}"
                              title="Delete"
                              data-toggle="tooltip"
                              class="btn btn-danger"
                              @if($item->video)
                              onclick="return confirm('Are you sure you want to remove this ad? This will remove the attached video as well.');"
                              @else
                              onclick="return confirm('Are you sure you want to remove this ad?');"
                        @endif
                ><i class="fa fa-times"></i> Delete Ad</a>
            </div>
        </div>
        <div class="clearfix"></div>
    </section>

    <!-- Main content -->
    <section class="content">
        {{ HTML::ul($errors->all()) }}

        {!! Form::model($item->toArray(), array('route' => array('advertisements.update', $item->id), 'method' => 'PATCH', 'files' => TRUE)) !!}

        <div class="row">
            <div class="col-md-8 col-xs-12">
                <div class="box box-primary pad">
                    <div class="box-header with-border">
                        <h3 class="box-title">Platform:</h3>
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool resize" data-widget="expand">
                                <i class="fa fa-expand"></i></button>
                            <button type="button" class="btn btn-box-tool resize" data-widget="compress">
                                <i class="fa fa-compress"></i></button>
                        </div>
                    </div>
                    <div class="box-body">
                        <div class="col-md-12">
                            <div class="form-group col-md-12" title="Choose the Ad Platform" data-toggle="tooltip">
                                {!! Form::select('platform',  $platforms->pluck("name","id")->toArray(), $item->category->platform->id , ['class'=>'platform_selector form-control'] ) !!}
                            </div>
                        </div>
                        <div class="col-md-12" id="ad-id-0">
                            <div class="box box-primary box-solid " id="ad-info">
                                <div class="box-header with-border">
                                    <h3 class="box-title"></h3>
                                    <div class="box-tools pull-right">

                                        <button type="button" class="btn btn-box-tool" data-widget="collapse">
                                            <i class="fa fa-minus"></i></button>
                                    </div>
                                </div>
                                <div class="box-body">
                                    <div class="col-md-7">
                                        <div class="form-group has-warning col-md-12 category-id-selector-wrapper"
                                             id="category-id-selector-wrapper"></div>

                                        <div class="form-group has-warning col-md-12 morphable-id-selector-wrapper"
                                             id="morphable-id-selector-wrapper"></div>
                                        <div class="form-group"
                                             id="pod-title-wrapper"
                                             @if($item->category_id != 4) style="display:none" @endif>
                                            {!! Form::label('ad[pod_title]', 'Pod Title: ') !!}
                                            {!! Form::text('ad[pod_title]', $item->pod_title , ['class' => 'form-control' ,'placeholder' => 'Pod Title']) !!}
                                        </div>
                                        <div class="form-group col-md-6"
                                             id="type-id-selector-wrapper"
                                             @if($item->category_id == 4) style="display:none" @endif >
                                            {!! Form::label('ad[type_id]', 'Type of Ad: ') !!}
                                            {!! Form::select('ad[type_id]',  [""=>"Select One"] +   $types->pluck("name","id")->toArray() , $item->type_id , ['class'=>'type_id_selector form-control'] ) !!}
                                        </div>
                                        <div class="form-group col-md-6" id="alignment-selector-wrapper">
                                            {!! Form::label('ad[alignment]', 'Alignment: ') !!}
                                            {!! Form::select('ad[alignment]', [''=>'Default','left' => 'Align Left', 'right' => 'Align Right'], $item->alignment ,['class'=> 'form-control']) !!}
                                        </div>
                                        <div class="form-group col-md-12">
                                            {!! Form::label('ad[misc_content]', 'Ad Internal Name: ') !!}
                                            {!! Form::text('ad[misc_content]',  $item->misc_content ,['class'=> 'form-control','disabled'=>'disabled']) !!}
                                            <small class="text-muted text-center">This is auto generated on the ad
                                                creation- for internal use only.
                                            </small>
                                        </div>
                                    </div>
                                    <div class="@if(!$item->video) hidden @endif video-box col-md-5"
                                         id="video-box">
                                        @if(!$item->video)
                                             @include('templates.partials.videoform')
                                        @endif
                                    </div>

                                        <div class="@if($item->image) hidden @endif image-box col-md-5" id="image-box">
                                            @if($item->image)
                                                @include('templates.partials.imageform')
                                            @endif
                                        </div>


                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
            {{-- CAMPAIGN BOX --}}
            <div class="col-md-4 col-xs-12">
                <div class="box box-primary pad">
                    <div class="box-body">
                        <div class="form-group col-md-12">
                            {!! Form::label('campaign_id', 'Campaign: ') !!}
                            <select name="campaign_id"
                                    class="form-control select2 campaign_selector"
                                    data-live-search='true'>
                                <option value="" url="">Select one or Choose this to remove association</option>
                                @foreach($campaigns as $campaign)
                                    <option value="{{$campaign->id}}" @if($item->campaign) @if($item->campaign->campaign_id == $campaign->id) {{ "selected" }} @endif @endif>{{$campaign->name}}</option>
                                @endforeach
                            </select>
                            <small class="help-text">Associate this ad with an Ad Campaign</small>
                        </div>
                    </div>
                </div>
            </div>
            {{--  -------- ASSETS BOX ---------  --}}
            <div class="col-md-4 col-xs-12">
                @include('campaigns.partials.assets')
            </div>
            {{-- SPONSOR BOX --}}
            <div class="col-md-4 col-xs-12">
                <div class="box box-primary pad">
                    <div class="box-header with-border">
                        <h3 class="box-title">Sponsor:</h3>
                        <div class="box-tools pull-right">
                            <a id="sponsor-edit"
                               class="btn-box-tool"
                               title="Edit Sponsor"
                               data-toggle="tooltip"
                               href="{{ route('sponsors.edit', $sponsor->id) }}"><i class="fa fa-pencil-square fa-2x"></i></a>
                        </div>
                    </div>
                    <div class="box-body">
                        <div class="form-group col-md-6">
                            {!! Form::label('sponsor_id', 'Sponsor: ') !!}
                            <select name="ad[sponsor_id]"
                                    class="form-control sponsor_selector select2"
                                    data-live-search='true'>
                                <option value="" url="">Select One</option>
                                @foreach($sponsors as $sponsor)
                                    <option value="{{$sponsor->id}}"
                                            url="{{$sponsor->url}}" @if($item->sponsor_id == $sponsor->id) {{ "selected" }} @endif >{{$sponsor->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-md-6">
                            @if(!is_null($sponsor_logo))
                                <img id="sponsor-logo"
                                     class='img img-thumbnail'
                                     data-imgid="{{$sponsor_logo->id}}"
                                     data-adid="{{$item->id}}"
                                     data-type="Sponsor"
                                     src="{{ config('filesystems.disks.rackspace.public_url_ssl') .$sponsor_logo->url }}"/>
                            @endif
                        </div>
                        <div class="form-group col-md-6"
                             titl="Please include a full url - http://www.domain.com."
                             data-toggle="tooltip">
                            {!! Form::label('ad[url]', 'URL: ') !!}
                            {!! Form::text('ad[url]', ($item->url)? $item->url : $item->sponsor->url , ['class' => 'form-control ad_url', 'id'=>'ad_url' ,'placeholder' => 'http://www.domain.com']) !!}
                        </div>

                        <div class="form-group col-md-6" title="Use _blank to link to an outside website and leave it blank to stay
                                within the ION website." data-toggle="tooltip">
                            {!! Form::label('ad[link_target]', 'Link Target: ') !!}
                            {!! Form::select('ad[link_target]',
                            [
                            "_blank" => "_blank" ,
                            '_parent'=>'_parent',
                            '_self'=>'_self',
                            '_top'=>'_top'
                            ] ,Input::old('ad[link_target]'), ['class' => 'form-control', 'placeholder' => '_blank']) !!}
                        </div>
                    </div>
                </div>
            </div>
            {{-- CALL TO ACTION AND DATES --}}
            <div class="col-md-4 col-xs-12">
                <div class="box box-primary pad">
                    <div class="box-body">
                        <div id="call-to-action-wrapper">

                            <div class="form-group">
                                {!! Form::label('ad[call_to_action]', 'Call To Action: ') !!}
                                {!! Form::text('ad[call_to_action]', $item->call_to_action , ['class' => 'form-control' ,'placeholder' => 'Sponsored By']) !!}
                            </div>

                            <div class="form-group">
                                {!! Form::label('ad[call_to_action_color]', 'Call To Action Color: ') !!}
                                {!! Form::select('ad[call_to_action_color]', ['black' => 'Black', 'white' => 'White'], $item->call_to_action_color ) !!}
                            </div>

                        </div>
                        <div class="form-group">
                            {!! Form::label('start_end_time', 'Select Start & End Dates / Times: ') !!}
                            {!! Form::text('start_end_time', $item->start_end_time , ['class' => 'form-control', 'placeholder' => 'Click to select your range']) !!}
                        </div>

                        <div class="form-group">
                            {!! Form::label('active', 'Active: ') !!}
                            {!! Form::checkbox('active', 1 , $item->active ) !!}
                        </div>
                    </div>
                </div>
            </div>
            {{-- HEADLINE BOX --}}
            <div class="col-md-4 col-xs-12">
                <div class="box box-primary pad" id="headline-box">
                    <div class="box-body">
                        <div class="form-group" id="headline-and-tagline-wrapper">
                            <div class="form-group col-md-6">
                                {!! Form::label('ad[headline]', 'Headline: ') !!}
                                {!! Form::text('ad[headline]', $item->headline , ['class' => 'form-control', 'placeholder' => 'Bond Marathon']) !!}
                            </div>
                            <div class="form-group col-md-6" id="headline-alignment-wrapper">
                                {!! Form::label('ad[headline_alignment]', 'Headline Alignment: ') !!}
                                {!! Form::select('ad[headline_alignment]', ['left' => 'Align Left', 'right' => 'Align Right'], $item->headline_alignment,['class'=> 'form-control'] ) !!}
                            </div>
                            <div class="form-group col-md-12">
                                {!! Form::label('ad[tagline]', 'Tagline: ') !!}
                                {!! Form::text('ad[tagline]', $item->tagline , ['class' => 'form-control', 'placeholder' => 'Start watching this weekend']) !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {{-- SAVEBAR --}}
        @include('templates.partials.savebar')
        {{-- UPLOAD BOX --}}
        <div class="box pad" id="uploader_drop_zone">
            <div class="box-header">
                <div class="box-title">Video Uploads:</div>
            </div>
            <div class="box-body">
                {!! Form::open(array("role"=>"form","files"=>TRUE,"id"=>"upload-form")) !!}
                {!! Form::hidden('upload_object_id', $item->id ,['id' => 'upload_object_id']) !!}
                {!! Form::hidden('upload_media_type', '8' ,['id' => 'upload_media_type']) !!}

                @include('templates.uploader')
                {!! Form::close() !!}
            </div>
        </div>
</div>
</section><!-- /.content -->
</div><!-- /.content-wrapper -->

@include('shows.partials.videomodal')
@stop
@section('footer_js')
    <script src="//sadmin.brightcove.com/js/BrightcoveExperiences.js"></script>
    <script src="{{ asset('js/brightcove/brightcove-player.js') }}"></script>
    <script src="{{ asset("/js/ads.js") }}" type="text/javascript"></script>
    <script>
        $(document).ready(function () {

            @if($item->category->platform->id)
               getCategoryIdSelector( {{ $item->category->platform_id }} , {{ $item->category_id }} , '#ad-id-0');
            @endif

            @if($item->category_id)
                getMorphableIdSelector( {{ $item->category_id }} , {{ $item->morphable_id }} , '#ad-id-0');
            @endif

            $(main_id)
                    .find('.box-title').first()
                    .text($(".platform_selector option:selected").text() + " - settings");

            var upload_extensions = ['mp4'];

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

                        scrollTo("#brightcove_id");
                        $.ajax({
                            method: "get",
                            url: "/advertisements/videos/update/{{$item->id}}"

                        }).success(function (data) {
                            var response = JSON.parse(data);
                            $("#video-box").html(response.html);
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

            function trigger_upload(media_type, uploader) {

                var dropZone = $('#uploader_drop_zone');
                dropZone.collapse('show').removeClass('hidden');
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
                trigger_upload(8, manualUploader);
            });

        });
    </script>
@stop