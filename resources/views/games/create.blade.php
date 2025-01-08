@extends("app")
@section("content")

        <!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1 class="pull-left">Games</h1>
        <div class="box-tools pull-right">
            <a href="{{ URL::previous() }}" class="btn btn-danger"><i class="fa fa-undo"></i> Back</a>
        </div>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary pad">
                    <div class="box-header with-border">
                        <h3 class="box-title">Create A New Game</h3>
                        <div class="box-tools pull-right">

                        </div>
                    </div>

                    <div class="box-body">

                        {{ HTML::ul($errors->all()) }}

                        {!! Form::open(array('route' => array('games.store'), 'method' => 'POST', 'file' => TRUE)) !!}
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group col-md-4">
                                    {!! Form::label('title', 'Title: ') !!}
                                    {!! Form::text('title', Input::old('title'), ['class' => 'form-control', 'placeholder' => 'Game Title']) !!}
                                </div>

                                <div class="form-group col-md-4">
                                    {!! Form::label('path', 'Path: ') !!}
                                    {!! Form::text('path', Input::old('path'), ['class' => 'form-control', 'placeholder' => 'Game Path']) !!}
                                </div>
                                <div class="form-group col-md-4">
                                    {!! Form::label('embed_id', 'Embed ID: ') !!}
                                    {!! Form::text('embed_id', Input::old('embed_id'), ['class' => 'form-control', 'placeholder' => 'external embeded id']) !!}
                                </div>
                                <div class="form-group col-md-4">
                                    {!! Form::label('scope', 'Scope(ex:Qubo): ') !!}
                                    {!! Form::text('scope', Input::old('scope'), ['class' => 'form-control', 'placeholder' => 'Qubo']) !!}
                                </div>
                                <div class="form-group col-md-4">
                                    {!! Form::label('active', 'Active: ') !!}
                                    {!! Form::checkbox('active', Input::old('active'), ['class' => 'form-control']) !!}
                                    <p class="help-hint">Check to make active.</p>
                                </div>

                                <div class="form-group col-md-4">
                                    {!! Form::label('sort_order', 'Sort Order: ') !!}
                                    {!! Form::text('sort_order', Input::old('sort_order'), ['class' => 'form-control', 'placeholder' => 'Sort Order']) !!}
                                </div>

                                <div class="form-group col-md-12">
                                    {!! Form::label('tags[]', 'Tags: ') !!}
                                    {!! Form::select('tags[]',  $game_tags->pluck('name','id'),Input::old('tags[]'), ['class' => 'select2 tags form-control' ,'multiple'=>'multiple']) !!}

                                </div>

                                <div class="form-group col-md-12">
                                    {!! Form::label('description', 'Description: ') !!}
                                    {!! Form::textarea('description', Input::old('description'), ['class' => 'form-control']) !!}
                                </div>
                            </div>

                            <div class="col-md-6">

                                <div class="form-group">
                                    {!! Form::label('image', 'Game Image: ') !!}
                                    {!! Form::file('image') !!}
                                </div>
                            </div>
                        </div>
                        @include('templates.partials.savebar')
                    </div>
                </div>
            </div>
        </div>
    </section><!-- /.content -->
</div><!-- /.content-wrapper -->
@stop
@section('footer_js')
    <script src="{{ asset("/js/games.js") }}" type="text/javascript"></script>
@stop



