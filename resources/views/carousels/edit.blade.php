@extends("app")
@section("content")

        <!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1 class="pull-left">Carousels</h1>
        <a class="btn btn-danger pull-right" href="{{URL::previous()}}"> <i class="fa fa-undo"></i> Back</a>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary pad">
                    <div class="box-header with-border">
                        <h3 class="box-title">Editing {{$item->title}}</h3>
                        <div class="box-tools pull-right">

                        </div>
                    </div>

                    <div class="box-body">

                        {{ HTML::ul($errors->all()) }}

                        {!! Form::model($item, array('route' => array('carousels.update', $item->id), 'method' => 'PUT')) !!}
                        <div class="form-group">
                            {!! Form::label('title', 'Title: ') !!}
                            {!! Form::text('title', Input::old('title'), ['class' => 'form-control', 'placeholder' => 'Page Title']) !!}
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


