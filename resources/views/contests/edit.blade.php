@extends("app")
@section("content")

        <!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>Contest Edit</h1>
    </section>

    <!-- Main content -->
    <section class="content">
        <h3>Editing {{$item->title}}</h3>


        {{ HTML::ul($errors->all()) }}

        {!! Form::model($item, array('route' => array('contests.update', $item->id), 'method' => 'PUT')) !!}
        <div class="form-group">
            {!! Form::label('name', 'Name: ') !!}
            {!! Form::text('name', Input::old('name'), ['class' => 'form-control', 'placeholder' => 'Contest Name']) !!}
        </div>

        <div class="form-group">
            {!! Form::label('description', 'Description: ') !!}
            {!! Form::text('description', Input::old('description'), ['class' => 'form-control', 'placeholder' => 'Description']) !!}
        </div>
        <div class="form-group">
            {!! Form::label('start_date', 'Start Date: ') !!}
            {!! Form::text('start_date', Input::old('start_date'), ['class' => 'form-control datepicker', 'placeholder' => 'Description']) !!}
        </div>
        <div class="form-group">
            {!! Form::label('end_date', 'End Date: ') !!}
            {!! Form::text('end_date', Input::old('end_date'), ['class' => 'form-control datepicker', 'placeholder' => 'End Date']) !!}
        </div>

        {!! Form::submit('Save Changes', ['class' => 'btn btn-primary']) !!}
        or <a href="{{ URL::to('/contests') }}">Cancel</a>

        {!! Form::close() !!}

    </section><!-- /.content -->
</div><!-- /.content-wrapper -->

@stop