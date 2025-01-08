@extends("app")
@section("content")

        <!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="pull-left">
            <h3>New Cast Member</h3>
        </div>
        <div class="pull-right">
            <div class="btn-group">
                <a href="{{ URL::previous() }}" class="btn btn-warning"><i class="fa fa-undo"></i> Back</a>
            </div>
        </div>
        <div class="clearfix"></div>
    </section>

    <!-- Main content -->
    <section class="content">
        {{ HTML::ul($errors->all()) }}

        {!! Form::open(array('route' => array('casts.store'), 'method' => 'POST')) !!}
        <div class="row">
            <div class="col-md-7">
                <div class="box box-primary pad">
                    <div class="box-body">
                        <div class="form-group">
                            <div class="row">
                                <div class="form-group col-md-3">
                                    {!! Form::label('show_id', 'Show: ') !!}
                                    {!! Form::select('show_id', ["Select One"] + $show_id_selector , $show_id , ['class' => 'form-control select2','autocomplete'=>"off"] ) !!}
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            {!! Form::label('active', 'Active: ') !!}
                            {!! Form::checkbox('active', Input::old('active'), ['class' => 'form-control']) !!}
                        </div>
                        <div class="form-group">
                            {!! Form::label('name', 'Name: ') !!}
                            {!! Form::text('name', Input::old('name'), ['class' => 'form-control', 'placeholder' => 'Cast Name']) !!}
                        </div>

                        <div class="form-group">
                            {!! Form::label('real_name', 'Real Name: ') !!}
                            {!! Form::text('real_name', Input::old('real_name'), ['class' => 'form-control', 'placeholder' => 'Real Name']) !!}
                        </div>
                        <div class="form-group">
                            {!! Form::label('age', 'Age: ') !!}
                            {!! Form::text('age', Input::old('age'), ['class' => 'form-control', 'placeholder' => 'Age']) !!}
                        </div>
                        <div class="form-group">
                            {!! Form::label('title', 'Title: ') !!}
                            {!! Form::text('title', Input::old('title'), ['class' => 'form-control', 'placeholder' => 'Real Name']) !!}
                        </div>

                        <div class="form-group">
                            {!! Form::label('description', 'Bio: ') !!}
                            {!! Form::textarea('description', Input::old('description'), ['class' => 'form-control', 'placeholder' => 'Bio']) !!}
                        </div>

                        <div class="form-group">
                            {!! Form::label('description', 'Sort Order: ') !!}
                            {!! Form::select('sort_order',range(0,10),Input::old('sort_order'), ['class' => 'form-control col-md-2', 'placeholder' => 'Sort Order']) !!}
                        </div>


                    </div>

                </div>
            </div>
            <div class="col-md-5">

                    <div class="box box-primary pad image-box" id="image-box">
                        <div class="box-header">
                            <h3 class="box-title"><i class="fa fa-file-image-o"></i> Cast Image:</h3>
                        </div>
                        <div class="box-body">
                            <div class="form-group">
                                {!! Form::label('image', 'Large: (320px X 550px)') !!}
                                {!! Form::file('image') !!}
                            </div>
                            <div class="form-group">
                                {!! Form::label('image_sm', 'Small: (320px x 240px) ') !!}
                                {!! Form::file('image_sm') !!}
                            </div>
                        </div>
                    </div>

            </div>
        </div>
        @include('templates.partials.savebar')

    </section><!-- /.content -->
</div><!-- /.content-wrapper -->

@stop


