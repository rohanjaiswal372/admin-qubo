@extends("app")
@section("content")

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>Prizes</h1>
    </section>

    <!-- Main content -->
    <section class="content">
      <h3>Create A New Prize</h3>

      {{ HTML::ul($errors->all()) }}

      {!! Form::open(array('route' => array('prizes.store'), 'method' => 'POST', 'files' => true)) !!}
        <div class="form-group">
          {!! Form::label('title', 'Title: ') !!}
          {!! Form::text('title', Input::old('title'), ['class' => 'form-control', 'placeholder' => 'Title']) !!}
        </div>

        <div class="form-group">
          {!! Form::label('stock', 'Stock: ') !!}
          {!! Form::text('stock', Input::old('stock'), ['class' => 'form-control', 'placeholder' => '5']) !!}
        </div>

        <div class="form-group">
          {!! Form::label('points', 'Points to Purchase: ') !!}
          {!! Form::text('points', Input::old('points'), ['class' => 'form-control', 'placeholder' => '5000']) !!}
        </div>

        <div class="form-group">
            {!! Form::label('active', 'Active: ') !!}
            {!! Form::checkbox('active', Input::old('active')) !!}
            <p class="help-hint">Check to make active.</p>
        </div>

        <div class="form-group">
          {!! Form::label('image', 'Image: ') !!}
          {!! Form::file('image') !!}
        </div>
        
        {!! Form::submit('Save Changes', ['class' => 'btn btn-primary']) !!} 
        or <a href="{{ route('prizes.index') }}">Cancel</a>

      {!! Form::close() !!}

    </section><!-- /.content -->
  </div><!-- /.content-wrapper -->

@stop


