@extends("app")
@section("content")

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>Nielsen Source</h1>
    </section>

    <!-- Main content -->
    <section class="content">
      <h3>Editing {{$item->title}}</h3>

      {{ HTML::ul($errors->all()) }}

      {!! Form::model($item, array('route' => array('nielsen.update', $item->id), 'method' => 'PUT')) !!}
        <div class="form-group">
          {!! Form::label('id', 'Source ID: ') !!}
          {!! Form::text('id', Input::old('id'), ['class' => 'form-control', 'placeholder' => 'Source ID']) !!}
        </div>

        <div class="form-group">
          {!! Form::label('description', 'Description: ') !!}
          {!! Form::text('description', Input::old('description'), ['class' => 'form-control', 'placeholder' => 'Description']) !!}
        </div>
  
        {!! Form::submit('Save Changes', ['class' => 'btn btn-primary']) !!} 
        or <a href="{{ URL::to('/nielsen/') }}">Cancel</a>

      {!! Form::close() !!}

    </section><!-- /.content -->
  </div><!-- /.content-wrapper -->

@stop


