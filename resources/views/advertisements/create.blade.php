@extends("app")
@section('header')
<style>
    input[type="checkbox"]:before {
        font-size: 21px;
        font-family: FontAwesome;
        display: inline-block;
    }

    input[type=checkbox]:before {
        position: absolute;
        background-color: #FFFFFF;
        content: "\f096";
        letter-spacing: 10px;
    }

    /* space between checkbox and label */
    input[type=checkbox]:checked:before {
        color: green;
        content: "\f046";
        letter-spacing: 5px;
    }

</style>
@stop
@section("content")
        <!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="pull-left"><h3>Create Advertisement(s)</h3>
            <div id="debug"></div>
        </div>
        <div class="pull-right">
            <div class="button-group">
                <a href="{{ URL::to('advertisements') }}" class="btn btn-primary"><i class="fa fa-undo"></i> All Ads</a>
            </div>
        </div>
        <div class="clearfix"></div>
    </section>

    <!-- Main content -->
    <section class="content">

        {{ HTML::ul($errors->all()) }}

        {!! Form::open(array('route' => array('advertisements.store'), 'method' => 'POST', 'files' => TRUE)) !!}
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

                        <div class="form-group col-md-12" title="Choose the Ad Platform" data-toggle="tooltip">
                            {!! Form::select('platform[]',$platforms->pluck("name","id")->toArray(), Input::old('platform[]'), ['class'=>'platform_selector_multi form-control select2','placeholder'=>'Select for each Ad type needed','required' => 'required', 'multiple' =>'multiple'] ) !!}
                        </div>
                        {{--<div class="form-group hide col-md-12" id="additionalPlatforms" title="Select for each platform needed. A Duplicate copy of the Ad will be made for this." data-toggle="tooltip">--}}

                        {{--{!! Form::select('add_platform[]', $platforms->pluck("name","id")->toArray(), Input::old('add_platform[]'), ['class'=>'form-control','id'=>'add_platform','placeholder'=>'Select for each Ad type needed', 'multiple' =>'multiple'] ) !!}--}}
                        {{--</div>--}}
                        <div id="ad-placeholder"></div>

                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-sm-6 col-xs-12">
                <div class="box box-primary pad">
                    <div class="box-body">

                        <div class="form-group col-md-12">

                            {!! Form::label('campaign_id', 'Campaign: ') !!}
                            <select name="campaign_id"
                                    class="form-control select2 campaign_selector"
                                    data-live-search='true'>
                                <option value="" url="">Select One</option>
                                @foreach($campaigns as $campaign)
                                    <option value="{{$campaign->id}}">{{$campaign->name}}</option>
                                @endforeach
                            </select>
                            <small class="help-text">Associate this ad with an Ad Campaign</small>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-sm-6 col-xs-12">
                <div class="box box-primary pad">
                    <div class="box-body">

                        <div class="form-group col-md-6">

                            {!! Form::label('sponsor_id', 'Sponsor: ') !!}
                            <select name="ad[sponsor_id]" id="ad[sponsor_id]"
                                    class="form-control sponsor_selector"
                                    data-live-search='true' required disabled>
                                <option value="" url="">Select One</option>
                                @foreach($sponsors as $sponsor)
                                    <option value="{{$sponsor->id}}"
                                            url="{{$sponsor->url}}">{{$sponsor->name}}</option>
                                @endforeach
                            </select>

                        </div>

                        <div class="form-group col-md-6">

                                <img id="sponsor-logo" class='img img-thumbnail' src="" />

                        </div>

                        <div class="form-group col-md-6 fade hidden"
                             title="Please include a full url - http://www.domain.com."
                             data-toggle="tooltip">
                            {!! Form::label('ad[url]', 'URL: ') !!}
                            {!! Form::text('ad[url]', Input::old('ad[url]'), ['class' => 'form-control ad_url', 'id'=>'ad_url' ,'placeholder' => 'http://www.domain.com']) !!}
                        </div>

                        <div class="form-group col-md-6 fade hidden" title="Use _blank to link to an outside website and leave it blank to stay
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
            <div class="col-lg-4 col-sm-6 col-xs-12">
                <div class="box box-primary pad">
                    <div class="box-body">
                        <div id="call-to-action-wrapper">
                            <div class="form-group col-md-6">
                                {!! Form::label('ad[call_to_action]', 'Call To Action: ') !!}
                                {!! Form::text('ad[call_to_action]', "Sponsored By" , ['class' => 'form-control' ,'placeholder' => 'Sponsored By' , 'maxlength' => '47']) !!}
                            </div>

                            <div class="form-group col-md-6">
                                {!! Form::label('ad[call_to_action_color]', 'Call To Action Color: ') !!}
                                {!! Form::select('ad[call_to_action_color]', ['black' => 'Black', 'white' => 'White'], Input::old('call_to_action_color') ,['class'=> 'form-control'] ) !!}
                            </div>

                        </div>
                        <div class="form-group col-md-6">
                            {!! Form::label('start_end_time', 'Select Start & End Dates / Times: ') !!}
                            {!! Form::text('start_end_time', Input::old('start_end_time'), ['class' => 'form-control', 'required' => 'required','placeholder' => 'Click to select your range']) !!}
                        </div>
                        <div class="form-group col-md-6">
                            {!! Form::label('active', 'Active: ') !!}
                            {!! Form::checkbox('active', 1 ,TRUE) !!}
                        </div>

                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-sm-6 col-xs-12">
                <div class="box box-primary hidden pad" id="headline-box">
                    <div class="box-body">
                        <div class="form-group" id="headline-and-tagline-wrapper">

                            <div class="form-group col-md-6">
                                {!! Form::label('ad[headline]', 'Headline: ') !!}
                                {!! Form::text('ad[headline]', Input::old('ad[headline]'), ['class' => 'form-control', 'placeholder' => 'Bond Marathon', 'maxlength' => '47']) !!}
                            </div>

                            <div class="form-group col-md-6" id="headline-alignment-wrapper">
                                {!! Form::label('ad[headline_alignment]', 'Headline Alignment: ') !!}
                                {!! Form::select('ad[headline_alignment]', ['left' => 'Align Left', 'right' => 'Align Right'], Input::old('ad[headline_alignment]'),['class'=> 'form-control']) !!}
                            </div>

                            <div class="form-group col-md-12">
                                {!! Form::label('ad[tagline]', 'Tagline: ') !!}
                                {!! Form::text('ad[tagline]', Input::old('ad[tagline]'), ['class' => 'form-control', 'placeholder' => 'Start watching this weekend','maxlength' => '47']) !!}
                            </div>

                        </div>
                    </div>
                </div>
            </div>

            @include('templates.partials.savebar')
            <div class="col-md-12 hide" id="ad-id-0">
                <div class="box box-primary box-solid " id="ad-info">
                    <div class="box-header with-border">
                        <h3 class="box-title"></h3>
                        <div class="box-tools pull-right">

                            <button type="button" class="btn btn-box-tool" data-widget="collapse">
                                <i class="fa fa-minus"></i></button>
                            <button type="button" class="btn btn-box-tool remove" data-widget="remove">
                                <i class="fa fa-times"></i></button>
                        </div>
                    </div>
                    <div class="box-body">
                        <div class="col-md-7">
                            <div class="form-group has-warning col-md-12 category-id-selector-wrapper"
                                 id="category-id-selector-wrapper"></div>

                            <div class="form-group has-warning col-md-12 morphable-id-selector-wrapper"
                                 id="morphable-id-selector-wrapper"></div>

                            <div class="form-group has-warning col-md-12"
                                 id="pod-title-wrapper"
                                 style="display:none">
                                {!! Form::label('ad[pod_title]', 'Pod Title: ') !!}
                                {!! Form::text('ad[pod_title]', Input::old('ad[pod_title][]') , ['class' => 'form-control' ,'placeholder' => 'Pod Title', 'maxlength' => '47']) !!}
                            </div>

                            <div class="form-group col-md-6" id="type-id-selector-wrapper">
                                {!! Form::label('ad[type_id][]', 'Type of Ad: ') !!}
                                {!! Form::select('ad[type_id][]',  [""=>"Select One"] +   $types->pluck("name","id")->toArray() , Input::old('ad[type_id][]'), ['class'=>'type_id_selector form-control'] ) !!}
                            </div>

                            <div class="form-group col-md-6" id="alignment-selector-wrapper">
                                {!! Form::label('ad[alignment][]', 'Alignment: ') !!}
                                {!! Form::select('ad[alignment][]', [''=>'Default','left' => 'Align Left', 'right' => 'Align Right'], Input::old('ad[alignment][]'),['class'=> 'form-control']) !!}
                            </div>
                        </div>
                        <div class="col-md-5">
                            <div class="box box-primary pad hidden video-box" id="video-box">
                                <div class="box-header">
                                    <h3 class="box-title"><i class="fa fa-video-camera"></i> Video:</h3>
                                </div>
                                <div class="box-body">
                                    <div class="form-group">
                                        {!! Form::label('brightcove_id', 'Brightcove Video ID: ') !!}
                                        {!! Form::input('number','brightcove_id', NULL, ['class' => 'form-control', 'placeholder' => '44122312342321']) !!}
                                        <p class="help-block">Copy the Brightcove Video ID found in the Medial Control
                                            panel on Brightcove.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-5">
                            <div class="box box-primary pad image-box" id="image-box">
                                <div class="box-header">
                                    <h3 class="box-title"><i class="fa fa-file-image-o"></i> Image:</h3>
                                </div>
                                <div class="box-body">
                                    <div class="form-group">
                                        {!! Form::label('image[]', 'Image or SWF: ') !!}
                                        {!! Form::file('image[]') !!}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>

    </section><!-- /.content -->
</div><!-- /.content-wrapper -->
@stop

@section('footer_js')
    <script src="{{ asset("/js/ads.js") }}"></script>
@stop