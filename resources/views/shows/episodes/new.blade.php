@extends("app")
@section("content")

        <!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="box pad">
        <section class="content-header box-header">
            <div class="pull-left">New Episode: <strong>{{$show->name}}</strong></div>
            <div class="pull-right"><a href="{{ URL::to('shows/episodes/'.$show->id) }}" class="btn btn-primary"><i
                            class="fa fa-undo"></i> Back to All Episodes</a></div>
        </section>

        <!-- Main content -->
        <section class="content box">

            <div class="box-body">
                {!! Form::open(array('route' => array('shows.episodes.store', $show->id), 'method' => 'POST')) !!}
                <div class="row">

                    <div class="form-group col-md-6">
                        {!! Form::label('show_id', 'Show: ') !!}
                        {!! Form::select('show_id', (!is_null($show))? [$show->id => $show->name] + $show_id_selector  : ["Select One"] + $show_id_selector  , NULL, ['class' => 'form-control select'] ) !!}
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-md-6">
                        {!! Form::label('name', 'Episode Name: ') !!}
                        {!! Form::text('name', Input::old('name'), ['class' => 'form-control', 'placeholder' => '', 'required' => TRUE]) !!}
                    </div>
                    <div class="form-group col-md-3">
                        {!! Form::label('season', 'Season: ') !!}
                        {!! Form::input('number','season', Input::old('season'), ['class' => 'form-control', 'placeholder' => '', 'required' => TRUE]) !!}
                    </div>

                    <div class="form-group col-md-3">
                        {!! Form::label('episode_number', 'Episode Number: ') !!}
                        {!! Form::text('episode_number', Input::old('episode_number'), ['class' => 'form-control', 'placeholder' => '', 'required' => TRUE]) !!}
                    </div>
                    <div class="form-group col-md-3">
                        {!! Form::label('episode[new_episode_starts_at]', 'Show as New Start Date: ') !!}
                        {!! Form::text('episode[new_episode_starts_at]', Input::old('new_episode_starts_at'), ['class' => 'form-control datepicker', 'placeholder' => '']) !!}
                    </div>
                    <div class="form-group col-md-3">
                        {!! Form::label('episode[new_episode_ends_at]', 'Show as New End Date: ') !!}
                        {!! Form::text('episode[new_episode_ends_at]', Input::old('new_episode_ends_at'), ['class' => 'form-control datepicker', 'placeholder' => '']) !!}
                    </div>

                    <div class="form-group col-md-12">
                        {!! Form::label('description', 'Description: ') !!}
                        {!! Form::textarea('description', Input::old('description'), ['class' => 'form-control', 'placeholder' => '', 'required' => TRUE]) !!}
                    </div>
                    <div class="form-group col-md-3">
                        {!! Form::label('brightcove_id', 'Video(brightcove ID): ') !!}
                        {!! Form::input('number','brightcove_id', Input::old('brightcove_id'), ['class' => 'form-control', 'placeholder' => '']) !!}
                    </div>
                </div>

                <br class="clearfix">
                {!! Form::submit('Save Changes', ['class' => 'btn btn-primary']) !!}

                {!! Form::close() !!}

            </div>

        </section><!-- /.content -->
    </div>
</div><!-- /.content-wrapper -->
@stop
@section('footer_js')

@stop