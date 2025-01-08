@extends("app")
@section("content")

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>Meal Types</h1>
    </section>

    <!-- Main content -->
    <section class="content">
      <h3>Create A New Meal Type</h3>

      {{ HTML::ul($errors->all()) }}

      {!! Form::open(array('route' => array('mealtypes.store'), 'method' => 'POST', 'files' => true)) !!}
        <div class="form-group">
          {!! Form::label('title', 'Title: ') !!}
          {!! Form::text('title', Input::old('title'), ['class' => 'form-control', 'placeholder' => 'Title']) !!}
        </div>
        
         <div class="form-group">
          {!! Form::label('path', 'Path: ') !!}
          {!! Form::text('path', Input::old('path'), ['class' => 'form-control', 'placeholder' => 'Path']) !!}
        </div>

        <h2>Images</h2>

        <div class="form-group">
          {!! Form::label('image', 'Image: ') !!}
          {!! Form::file('image') !!}
        </div>
        
        {!! Form::submit('Save Changes', ['class' => 'btn btn-primary']) !!} 
        or <a href="{{ route('mealtypes.index') }}">Cancel</a>

      {!! Form::close() !!}

    </section><!-- /.content -->
  </div><!-- /.content-wrapper -->

@stop


