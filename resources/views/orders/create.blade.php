@extends("app")
@section("content")

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>Ad Placements</h1>
    </section>

    <!-- Main content -->
    <section class="content">
      <h3>Create A New Ad Placement</h3>

      {{ HTML::ul($errors->all()) }}

      {!! Form::open(array('route' => array('ad-placements.store'), 'method' => 'POST')) !!}
        <div class="form-group">
          {!! Form::label('title', 'Title: ') !!}
          {!! Form::text('title', Input::old('title'), ['class' => 'form-control', 'placeholder' => 'Page Title']) !!}
        </div>

        <div class="form-group">
          {!! Form::label('description', 'Description: ') !!}
          {!! Form::text('description', Input::old('description'), ['class' => 'form-control', 'placeholder' => 'What types of banners this area will except']) !!}
        </div>

        <div class="form-group">
          {!! Form::label('path', 'Path: ') !!}
          {!! Form::text('path', Input::old('path'), ['class' => 'form-control', 'placeholder' => 'Page Path']) !!}
        </div>
        
        {!! Form::submit('Save Changes', ['class' => 'btn btn-primary']) !!} 
        or <a href="{{ route('ad-placements.index') }}">Cancel</a>

      {!! Form::close() !!}

    </section><!-- /.content -->
  </div><!-- /.content-wrapper -->

@stop


