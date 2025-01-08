@extends("app")
@section("content")

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>Hopes</h1>
    </section>

    <!-- Main content -->
    <section class="content">
      <h3>Editing {{$item->title}}</h3>

      {{ HTML::ul($errors->all()) }}

      {!! Form::model($item, array('route' => array('hopes.update', $item->id), 'method' => 'PUT', 'files' => true)) !!}
          <div class="form-group">
          {!! Form::label('uid', 'UID: ') !!}
          {!! Form::text('uid', Input::old('uid'), ['class' => 'form-control', 'placeholder' => 'UID']) !!}
        </div>

        <div class="form-group">
          {!! Form::label('firstname', 'First Name: ') !!}
          {!! Form::text('firstname', Input::old('firstname'), ['class' => 'form-control', 'placeholder' => 'First Name']) !!}
        </div>

        <div class="form-group">
          {!! Form::label('lastname', 'Last Name: ') !!}
          {!! Form::text('lastname', Input::old('lastname'), ['class' => 'form-control', 'placeholder' => 'Last Name']) !!}
        </div>

        <div class="form-group">
          {!! Form::label('email', 'Email: ') !!}
          {!! Form::text('email', Input::old('email'), ['class' => 'form-control', 'placeholder' => 'Email']) !!}
        </div>

        <div class="form-group">
          {!! Form::label('description', 'Description: ') !!}
          {!! Form::textarea('description', Input::old('description'), ['class' => 'form-control']) !!}
        </div>

        <div class="form-group">
            {!! Form::label('active', 'Active: ') !!}
            {!! Form::checkbox('active', 1, Input::old('active')) !!}
            <p class="help-hint">Check to make active.</p>
        </div>

      
        {!! Form::submit('Save Changes', ['class' => 'btn btn-primary']) !!} 
        or <a href="{{ URL::to('/hopes/') }}">Cancel</a>

      {!! Form::close() !!}

    </section><!-- /.content -->
  </div><!-- /.content-wrapper -->

@stop

