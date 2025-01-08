@extends("app")
@section("content")

        <!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1 class="pull-left">Backgrounds</h1>
        <a class="btn btn-danger pull-right" href="{{URL::previous()}}"> <i class="fa fa-undo"></i> Back</a>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary pad">
                    <div class="box-header with-border">
                        <h3 class="box-title">Create A New Background</h3>
                        <div class="box-tools pull-right">

                        </div>
                    </div>

                    <div class="box-body">

                        {{ HTML::ul($errors->all()) }}

                        {!! Form::open(array('route' => array('backgrounds.store'), 'method' => 'POST', 'files' => TRUE)) !!}
                        <div class="form-group">
                            {!! Form::label('title', 'Title: ') !!}
                            {!! Form::text('title', Input::old('title'), ['class' => 'form-control', 'placeholder' => 'Page Title']) !!}
                        </div>

                        <div class="form-group">
                            {!! Form::label('path', 'Path: ') !!}
                            {!! Form::text('path', Input::old('path'), ['class' => 'form-control', 'placeholder' => 'Page Path']) !!}
                            <p class="help-block">You may leave this blank, the system will generate it for you if you
                                do.</p>
                        </div>

                        <div class="form-group">
                            {!! Form::label('active', 'Active: ') !!}
                            {!! Form::checkbox('active', Input::old('active'), ['class' => 'form-control']) !!}
                            <p class="help-hint">Check to make active.</p>
                        </div>

                        <div class="form-group">
                            {!! Form::label('image', 'Image: ') !!}
                            {!! Form::file('image') !!}
                        </div>
                        @include('templates.partials.savebar')
                    </div>
                </div>
            </div>
        </div>
    </section><!-- /.content -->
</div><!-- /.content-wrapper -->

@stop


