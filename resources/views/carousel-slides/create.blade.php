@extends("app")
@section("content")

        <!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="pull-left"><h3>Create a New Carousel Slide</h3>
            <div id="debug"></div>
        </div>
        <div class="pull-right">
            <div class="button-group">
                <a href="{{ URL::to('carousel-slides') }}" class="btn btn-primary"><i class="fa fa-undo"></i> All Slides</a>
            </div>
        </div>
        <div class="clearfix"></div>
    </section>

    <!-- Main content -->
    <section class="content">

        {{ HTML::ul($errors->all()) }}


        {!! Form::open(array('route' => array('carousel-slides.create'), 'method' => 'POST', 'files' => TRUE)) !!}
        <div class="row">
            <div class="col-md-8 col-xs-12">
                <div class="box box-primary pad">
                    <div class="box-header with-border">
                        <h3 class="box-title">Slide:</h3>
                        <div class="box-tools pull-right">

                        </div>
                    </div>
                    <div class="box-body">
                        <div class="form-group col-md-6">
                            {!! Form::label('title', 'Slide Title: ') !!}
                            {!! Form::text('title', Input::old('title'), ['class' => 'form-control', 'placeholder' => 'Title']) !!}
                        </div>

                        <div class="form-group col-md-6">
                            {!! Form::label('url', 'URL: ') !!}
                            {!! Form::text('url', Input::old('url'), ['class' => 'form-control', 'placeholder' => 'http://www.iontelevision.com/shows']) !!}
                            <p class="small help-block">If this slide is not connected to a show, please enter a URL
                                that we can send the user to.</p>
                        </div>

                        <div class="form-group col-md-12">
                            {!! Form::label('show_id', 'Show: ') !!}
                            {!! Form::select('show_id', ["Select One"] + $shows, Input::old('show_id'), ['class' => 'form-control select2']) !!}
                            <p class="small help-block">If this slide is to showcase a show, please select it here.</p>
                        </div>

                        <div class="form-group col-md-6">
                            {!! Form::label('headline', 'Headline: ') !!}
                            {!! Form::text('headline', Input::old('headline'), ['class' => 'form-control', 'placeholder' => 'Bond Marathon']) !!}
                        </div>

                        <div class="form-group col-md-6">
                            {!! Form::label('tagline', 'Tagline: ') !!}
                            {!! Form::text('tagline', Input::old('tagline'), ['class' => 'form-control', 'placeholder' => 'Start watching this weekend']) !!}
                        </div>

                        <div class="form-group col-md-3">
                            {!! Form::label('text_color', 'Text Color: ') !!}
                            {!! Form::text('text_color', Input::old('text_color'), ['class' => 'form-control colorpicker', 'placeholder' => 'white']) !!}
                        </div>

                        <div class="form-group col-md-3">
                            <div class="col-md-6">
                                {!! Form::label('headline_align', 'Headline Alignment: ') !!}
                            </div>
                            <div class="col-md-6">
                                {!! Form::select('headline_align', ['left' => 'Align Left', 'right' => 'Align Right'], Input::old('headline_align')) !!}
                            </div>
                        </div>

                        <div class="form-group col-md-3" title="Check to make active." data-toggle="tooltip">
                            <div class="col-md-6">
                                {!! Form::label('active', 'Active: ') !!}
                            </div>
                            <div class="col-md-6">
                                {!! Form::checkbox('active', Input::old('active'), ['class' => 'form-control']) !!}
                            </div>

                        </div>

                        <div class="form-group col-md-3" title="This option is only for show-specific slides and should be checked only for
                            slides that will appear dynamically based on promo scheduling percentages. Only 1 slide per
                            show will be dynamic at any given time" data-toggle="tooltip">
                            <div class="col-md-6">
                                {!! Form::label('dynamic', 'Dynamic: ') !!}
                            </div>
                            <div class="col-md-6">
                                {!! Form::checkbox('dynamic',  1 , NULL) !!}
                            </div>

                        </div>

                        <div class="form-group col-md-3"
                             title="Check if you want to ignore the tagline and display the next air date."
                             data-toggle="tooltip">
                            <div class="col-md-6">
                            {!! Form::label('pull_next_air', 'Pull Next Air Date: ') !!}
                            </div>
                            <div class="col-md-6">
                            {!! Form::checkbox('pull_next_air', Input::old('pull_next_air'), ['class' => 'form-control']) !!}
                                </div>
                        </div>

                        {{--<h3>Scheduling</h3>
                        <div class="form-group">
                          {!! Form::label('start_end_time', 'Select Start & End Dates / Times: ') !!}
                          {!! Form::text('start_end_time', Input::old('start_end_time'), ['class' => 'form-control', 'placeholder' => 'Click to select your range']) !!}
                        </div>

                        <hr />--}}
                    </div>
                </div>
            </div>

            <div class="col-md-4 col-xs-12">
                <div class="box box-primary pad">
                    <div class="box-body">

                        <h3>Images</h3>

                        <div class="form-group">
                            {!! Form::label('image', 'Image Upload: ') !!}
                            {!! Form::file('image') !!}
                            <p class="small help-block">The system is expecting a 1920 x 700 image for Web.</p>
                            <p class="small help-block">The system is expecting a 1536 x 2045 image for Mobile.</p>
                        </div>

                    </div>
                </div>
            </div>
        </div>
        @include('templates.partials.savebar')

    </section><!-- /.content -->
</div><!-- /.content-wrapper -->

@stop


