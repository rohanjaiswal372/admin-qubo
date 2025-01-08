@extends("app")
@section("content")

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>Movies</h1>
    </section>

    <!-- Main content -->
    <section class="content">
      <h3>Editing {{$item->title}}</h3>

      {{ HTML::ul($errors->all()) }}

      {!! Form::model($item, array('route' => array('movies.update', $item->id), 'method' => 'PUT')) !!}

        <div class="form-group">
          {!! Form::label('name', 'Name: ') !!}
          {!! Form::text('name', Input::old('name'), ['class' => 'form-control', 'placeholder' => 'Name']) !!}
        </div>

        <div class="form-group">
          {!! Form::label('headline', 'Carousel Headline: ') !!}
          {!! Form::text('headline', Input::old('headline'), ['class' => 'form-control', 'placeholder' => '']) !!}
        </div>

        <div class="form-group">
          {!! Form::label('slug', 'Slug: ') !!}
          {!! Form::text('slug', Input::old('slug'), ['class' => 'form-control', 'placeholder' => '']) !!}
        </div>

        <div class="form-group">
          {!! Form::label('short_name', 'Short Name: ') !!}
          {!! Form::text('short_name', Input::old('short_name'), ['class' => 'form-control', 'placeholder' => 'Name']) !!}
        </div>
  
        <div class="form-group">
          {!! Form::label('description', 'Description: ') !!}
          {!! Form::textarea('description', Input::old('description'), ['class' => 'form-control']) !!}
        </div>

        <div class="form-group">
          {!! Form::label('broadview_handle', 'Broadview Handle: ') !!}
          {!! Form::text('broadview_handle', Input::old('broadview_handle'), ['class' => 'form-control', 'placeholder' => '']) !!}
        </div>

        <div class="form-group">
            {!! Form::label('active', 'Active: ') !!}
            {!! Form::checkbox('active', Input::old('active')) !!}
            <p class="help-hint">Check to make active.</p>
        </div>

        <div class="form-group">
            {!! Form::label('featured', 'Featured: ') !!}
            {!! Form::checkbox('featured', Input::old('featured')) !!}
            <p class="help-hint">Check to make featured.</p>
        </div>

        <div class="form-group">
            {!! Form::label('holiday', 'Holiday Movie: ') !!}
            {!! Form::checkbox('holiday', Input::old('holiday')) !!}
            <p class="help-hint">Check to mark as a holiday movie.</p>
        </div>

        <div class="form-group">
          {!! Form::label('scope', 'Scope: ') !!}
          {!! Form::text('scope', Input::old('scope'), ['class' => 'form-control', 'placeholder' => '']) !!}
        </div>

        {!! Form::submit('Save Changes', ['class' => 'btn btn-primary']) !!} 
        or <a href="{{ URL::to('/movies/') }}">Cancel</a>

      {!! Form::close() !!}

    </section><!-- /.content -->
  </div><!-- /.content-wrapper -->

@stop


