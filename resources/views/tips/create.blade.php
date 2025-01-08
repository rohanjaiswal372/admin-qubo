@extends("app")
@section("content")

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>Tips</h1>
    </section>

    <!-- Main content -->
    <section class="content">
      <h3>Create A New Blog Post</h3>

      {{ HTML::ul($errors->all()) }}

      {!! Form::open(array('route' => array('tips.store'), 'method' => 'POST','files' => true)) !!}

    	<div class="form-group">
    		{!! Form::label('active', 'Active (Push Live): ') !!}
    		{!! Form::checkbox('active', Input::old('active'), ['class' => 'form-control']) !!}
    		<p class="help-hint">Check to make active.</p>
    	</div>

      <div class="form-group">
          {!! Form::label('title', 'Title: ') !!}
          {!! Form::text('title', Input::old('title'), ['class' => 'form-control', 'placeholder' => 'Title']) !!} 
      </div>

        <div class="form-group">
          {!! Form::label('summary', 'Summary: ') !!}
          {!! Form::textarea('summary', Input::old('summary'), ['class' => 'form-control']) !!}
          <p class="help-hint">Keep the summary short.</p>
        </div>

        <div class="form-group">
          {!! Form::label('video', 'Brightcove Video ID: ') !!}
          {!! Form::text('video', Input::old('video'), ['class' => 'form-control', 'placeholder' => '44122312342321']) !!}
          <p class="help-block">Copy the Brightcove Video ID found in the Medial Control panel on Brightcove.</p>
        </div>

        <h2>Images</h2>

        <div class="form-group">
          {!! Form::label('image', 'Primary Image (Thumbnail on listing): ') !!}
          {!! Form::file('image') !!}
        </div>
  

        {!! Form::submit('Save Changes', ['class' => 'btn btn-primary']) !!} 
        or <a href="{{ route('tips.index') }}">Cancel</a>

      {!! Form::close() !!}

    </section><!-- /.content -->
  </div><!-- /.content-wrapper -->

@stop


