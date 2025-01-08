@extends("app")
@section("content")

        <!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>Carousel Slides</h1>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary pad">
                    <div class="box-header with-border">
                        <h3 class="box-title">Editing Slide {{ $slide->title }}</h3>
                        <div class="box-tools pull-right">

                        </div>
                    </div>

                    <div class="box-body">

                        {{ HTML::ul($errors->all()) }}

                        {!! Form::model($slide, array('route' => array('carousel-slides.update', $slide->id), 'method' => 'PUT', 'files' => TRUE)) !!}
                        <div class="form-group">
                            {!! Form::label('title', 'Slide Title: ') !!}
                            {!! Form::text('title', Input::old('title'), ['class' => 'form-control', 'placeholder' => 'Title']) !!}
                        </div>

                        <div class="form-group">
                            {!! Form::label('url', 'URL: ') !!}
                            {!! Form::text('url', Input::old('url'), ['class' => 'form-control', 'placeholder' => 'http://www.iontelevision.com/shows']) !!}
                        </div>

                        <div class="form-group">
                            {!! Form::label('show_id', 'Show: ') !!}
                            {!! Form::select('show_id', ["Select One"] + $shows, Input::old('show_id')) !!}
                            <p class="help-block">If this slide is to showcase a show, please select it here.</p>
                        </div>

                        <div class="form-group">
                            {!! Form::label('headline', 'Headline: ') !!}
                            {!! Form::text('headline', Input::old('headline'), ['class' => 'form-control', 'placeholder' => 'Bond Marathon']) !!}
                        </div>

                        <div class="form-group">
                            {!! Form::label('tagline', 'Tagline: ') !!}
                            {!! Form::text('tagline', Input::old('tagline'), ['class' => 'form-control', 'placeholder' => 'Start watching this weekend']) !!}
                        </div>

                        <div class="form-group">
                            {!! Form::label('text_color', 'Text Color: ') !!}
                            {!! Form::text('text_color', Input::old('text_color'), ['class' => 'form-control', 'placeholder' => 'white']) !!}
                        </div>

                        <div class="form-group">
                            {!! Form::label('headline_align', 'Headline Alignment: ') !!}
                            {!! Form::select('headline_align', ['left' => 'Align Left', 'right' => 'Align Right'], Input::old('headline_align')) !!}
                        </div>

                        <!--
        <div class="form-group">
          {!! Form::label('sort_order', 'Slide Position / Order:') !!}
                        {!! Form::text('sort_order', Input::old('sort_order'), ['class' => 'form-control col-xs-2', 'placeholder' => '5']) !!}
                                </div>
                                -->

                        <div class="form-group">
                            {!! Form::label('active', 'Active: ') !!}
                            {!! Form::checkbox('active', Input::old('active'), ['class' => 'form-control']) !!}
                            <p class="help-hint">Check to make active.</p>
                        </div>

                        <div class="form-group">
                            {!! Form::label('dynamic', 'Dynamic: ') !!}
                            {!! Form::checkbox('dynamic',  1 , Input::old('dynamic')) !!}
                            <p class="help-hint">This option is only for show-specific slides and should be checked only
                                for slides that will appear dynamically based on promo scheduling percentages. Only 1
                                slide per show will be dynamic at any given time</p>
                        </div>

                        <div class="form-group">
                            {!! Form::label('pull_next_air', 'Pull Next Air Date: ') !!}
                            {!! Form::checkbox('pull_next_air', Input::old('pull_next_air'), ['class' => 'form-control']) !!}
                            <p class="help-hint">Check if you want to ignore the tagline and display the next air
                                date.</p>
                        </div>

                        <hr/>
                        {{--<h3>Scheduling</h3>
                        <div class="form-group">
                          {!! Form::label('start_end_time', 'Select Start & End Dates / Times: ') !!}
                          {!! Form::text('start_end_time', Input::old('start_end_time'), ['class' => 'form-control', 'placeholder' => 'Click to select your range']) !!}
                        </div>

                        <hr />--}}

                        <h3>Images</h3>

                        @if( $slide->image )
                            <p>Click the image to view the full resolution.</p>
                            <table class="table no-margin">
                                <thead>
                                <tr>
                                    <th>Web Image</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td style="width:100%">
                                        <a target="_blank" href="{{ image($slide->image->url) }}">
                                            <img src="{{ image($slide->image->url) }}"
                                                 style="max-width: 500px; height: auto;"/> </a>
                                    </td>
                                    <td>
                                        <a href="{{ URL::to('/image/remove/'.$slide->image->id) }}"
                                           onclick="return confirm('Are you sure? This will remove the image from our system.');">Remove</a>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                            <hr/>
                        @endif


                        @if( $slide->mobile_image )
                            <p>Click the image to view the full resolution.</p>
                            <table class="table no-margin">
                                <thead>
                                <tr>
                                    <th>Mobile Image</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td style="width:100%">
                                        <a target="_blank" href="{{ image($slide->mobile_image->url) }}">
                                            <img src="{{ image($slide->mobile_image->url) }}"
                                                 style="max-width: 200px; height: auto;"/> </a>
                                    </td>
                                    <td>
                                        <a href="{{ URL::to('/image/remove/'.$slide->mobile_image->id) }}"
                                           onclick="return confirm('Are you sure? This will remove the image from our system.');">Remove</a>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                            <hr/>
                        @endif

                        <div class="form-group">
                            {!! Form::label('image', 'Image Upload: ') !!}
                            {!! Form::file('image') !!}
                            <p class="help-block">The system is expecting a 1920 x 700 image for Web.</p>
                            <p class="help-block">The system is expecting a 1536 x 2045 image for Mobile.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @include('templates.partials.savebar')
    </section><!-- /.content -->
</div><!-- /.content-wrapper -->

@stop


