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
      <h3>Editing {{$item->title}}</h3>

      {{ HTML::ul($errors->all()) }}

      {!! Form::model($item, array('route' => array('mealtypes.update', $item->id), 'method' => 'PUT', 'files' => true)) !!}
        <div class="form-group">
          {!! Form::label('title', 'Title: ') !!}
          {!! Form::text('title', Input::old('title'), ['class' => 'form-control', 'placeholder' => 'Title']) !!}
        </div>
        
         <div class="form-group">
          {!! Form::label('path', 'Path: ') !!}
          {!! Form::text('path', Input::old('path'), ['class' => 'form-control', 'placeholder' => 'Path']) !!}
        </div>

        <h2>Images</h2>

        @if (count( $item->images) > 0 )
          <table class="table no-margin">
          <thead><tr><th>Image</th><th>Action</th></tr></thead>
          <tbody>
            @foreach( $item->images as $image )
            <?php $image->toArray(); ?>
              <tr><td>
              <a href="{{ config('filesystems.disks.rackspace.public_url') }}{{ $image['url'] }}" target="_blank"><img src="{{ config('filesystems.disks.rackspace.public_url') }}{{ $image['url'] }}" style="max-width: 500px; height: auto;" /> </a>
              </td><td><a href="{{ URL::to('/image/remove/'.$image['id']) }}" onclick="return confirm('Are you sure? This will remove the image from our system.');">Remove</a>
              </tr>
            @endforeach
          </tbody>
          </table>
          <hr />
        @endif

        <div class="form-group">
          {!! Form::label('image', 'Image: ') !!}
          {!! Form::file('image') !!}
        </div>

        {!! Form::submit('Save Changes', ['class' => 'btn btn-primary']) !!} 
        or <a href="{{ URL::to('/mealtypes/') }}">Cancel</a>

      {!! Form::close() !!}

    </section><!-- /.content -->
  </div><!-- /.content-wrapper -->

@stop


