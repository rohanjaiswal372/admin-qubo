@extends("app")
@section("content")

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1 class="pull-left"><i class="fa fa-exclamation-triangle"></i> New Rescan Alert</h1>
            <div class="pull-right">
                <div class="btn-group">
                    <a class="btn btn-primary" href="{{route('rescan-alerts.index')}}"><i class="fa fa-list"></i> Show
                        All</a>
                </div>
            </div>
        </section>
        <hr class="clearfix"/>

        {!! Form::open(array('route' => array('rescan-alerts.store'), 'method' => 'POST', 'files'=> TRUE )) !!}

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
                                {!! Form::textarea('postal_codes', Input::old('postal_codes'), ['class' => 'form-control']) !!}
                            </div>

                            <div class="form-group">
                                {!! Form::label('start_end_time', 'Select Start & End Dates / Times: ') !!}
                                {!! Form::text('start_end_time', Input::old('starts_and_end_dates'), ['class' => 'form-control', 'required' => 'required','placeholder' => 'Click to select your range']) !!}
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

                <div class="col-md-6">
                    <div class="box box-primary pad">
                        <div class="box-header">
                            <h3 class="box-title"><i class="fa fa-video-camera"></i> Video:</h3>
                        </div>
                        <div class="box-body">

                            <div class="form-group">
                                {!! Form::label('brightcove_id', 'Brightcove Video ID: ') !!}
                                {!! Form::input('number','brightcove_id', NULL, ['class' => 'form-control', 'placeholder' => '44122312342321']) !!}
                                <p class="help-block">Copy the Brightcove Video ID found in the Medial Control panel on
                                    Brightcove.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        @include('templates.partials.savebar')

        {!! Form::close() !!}

    </div><!-- /.content-wrapper -->
@stop


