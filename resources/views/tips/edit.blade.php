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
      <h3>Editing {{$item->title}}</h3>

      {{ HTML::ul($errors->all()) }}

      {!! Form::model($item, array('route' => array('tips.update', $item->id), 'method' => 'PUT', 'files' => true)) !!}

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

        <div class="form-group">
          {!! Form::label('created_at', 'Posted On: ') !!}
          {!! Form::text('created_at', Input::old('created_at'), ['class' => 'form-control', 'placeholder' => 'Posted On']) !!}
        </div>

        <h2>Images</h2>

        <div class="form-group">
          {!! Form::label('image', 'Primary Image (Thumbnail on listing): ') !!}
          {!! Form::file('image') !!}
          <p class="help-block">This is the primary image that will be displayed when a user is reading the blog article.</p>
        </div>

        @if (count( $item->imageDefault) > 0 )
          <table class="table no-margin">
          <thead><tr><th>Image</th><th>Action</th></tr></thead>
          <tbody>
            @foreach( $item->imageDefault as $image )
            <?php $image->toArray(); ?>
              <tr><td>
              <img src="{{  image($image['url']) }}" style="max-width: 250px; height: auto;" /> 
              </td><td><a href="{{ URL::to('/image/remove/'.$image['id']) }}" onclick="return confirm('Are you sure? This will remove the image from our system.');">Remove</a>
              </tr>
            @endforeach
          </tbody>
          </table>
        @endif



        {!! Form::submit('Save Changes', ['class' => 'btn btn-primary']) !!} 
        or <a href="{{ URL::to('/tips/') }}">Cancel</a>

      {!! Form::close() !!}

    </section><!-- /.content -->
  </div><!-- /.content-wrapper -->

@stop


