@extends("app")
@section("content")

        <!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>Carousels</h1>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary pad">
                    <div class="box-header with-border">
                        <h3 class="box-title">Create A New Carousel</h3>
                        <div class="box-tools pull-right">

                        </div>
                    </div>

                    <div class="box-body">

                        {{ HTML::ul($errors->all()) }}

                        {!! Form::open(array('route' => array('carousels.store'), 'method' => 'POST', 'file' => TRUE)) !!}
                        <div class="form-group">
                            {!! Form::label('title', 'Title: ') !!}
                            {!! Form::text('title', Input::old('title'), ['class' => 'form-control', 'placeholder' => 'Title']) !!}
                        </div>

                        <div class="form-group">
                            {!! Form::label('position', 'Position:') !!}
                            {!! Form::select('position', ['home-page' => 'Home Page','holiday-movies' => 'Holiday Movies', 'mobile' => 'Mobile'], Input::old('position')) !!}
                        </div>

                        <div class="form-group">
                            {!! Form::label('description', 'Description: ') !!}
                            {!! Form::textarea('description', Input::old('description'), ['class' => 'form-control']) !!}
                        </div>

                        <div class="form-group">
                            {!! Form::label('active', 'Active: ') !!}
                            {!! Form::checkbox('active', Input::old('active'), ['class' => 'form-control']) !!}
                            <p class="help-hint">Check to make active.</p>
                        </div>
                        @include('templates.partials.savebar')
                    </div>
                </div>
            </div>
        </div>
    </section><!-- /.content -->
</div><!-- /.content-wrapper -->

@stop


