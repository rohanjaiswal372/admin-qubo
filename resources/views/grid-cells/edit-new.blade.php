@extends("app")
@section("content")

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>POD Editing</h1>
    </section>

    <!-- Main content -->
    <section class="content">
      <h3>Editing {{ $pod_number }}</h3>

      {{ HTML::ul($errors->all()) }}
      
      @if( is_null($item) )
        {!! Form::open(array('route' => array('grid-cells.store'), 'method' => 'POST', 'files' => true)) !!}
      @else
        {!! Form::model($item, array('route' => array('grid-cells.update', $item->id), 'method' => 'PUT', 'files' => true)) !!}
      @endif
        {!! Form::hidden('grid_id', $grid->id) !!}
        {!! Form::hidden('location', $pod_number) !!}

        <div class="form-group">
          {!! Form::label('title', 'Box Title: ') !!}
          {!! Form::text('title', Input::old('title'), ['class' => 'form-control', 'placeholder' => 'All New Shows']) !!}
          <p class="help-block">This will dipslay within the gray bar below the graphic.</p>
        </div>

        <div class="form-group">
          {!! Form::label('hyperlink', 'Box Link: ') !!}
          {!! Form::text('hyperlink', Input::old('hyperlink'), ['class' => 'form-control', 'placeholder' => 'http://www.iontelevision.com']) !!}
          <p class="help-block">Where should the box take the user when clicked?</p>
        </div>
        
        <div class="form-group">
          {!! Form::label('hyperlink_target', 'Open link in a new window:') !!}
          {!! Form::checkbox('hyperlink_target', Input::old('hyperlink_target')) !!}
          <p class="help-block">Check to open a new window when the box is clicked.</p>
        </div>

        <hr />
        <h3>Hover Effect</h3>
        <p>If you would like this pod to have a hover effect please fill in at least the headline.</p>
        <div class="form-group">
          {!! Form::label('headline', 'Hover Headline: ') !!}
          {!! Form::text('headline', Input::old('headline'), ['class' => 'form-control', 'placeholder' => 'All New Shows']) !!}
          <p class="help-block">This will be the primary headline when the user hovers over the box.</p>
        </div>

        <div class="form-group">
          {!! Form::label('tagline', 'Hover Tagline: ') !!}
          {!! Form::text('tagline', Input::old('tagline'), ['class' => 'form-control', 'placeholder' => 'follow this amazing story']) !!}
          <p class="help-block">This is the secondary tagline below the primary headline.</p>
        </div>

        <h3>Show</h3>
        <p>If this pod needs to be associated with a show, please select the show here.</p>

        <div class="form-group">
          {!! Form::label('show_id', 'Show: ') !!}
          {!! Form::select('show_id', ["Select One"] + $shows, Input::old('show_id')) !!}
          <p class="help-block">If this slide is to showcase a show, please select it here.</p>
        </div>

        <div class="form-group">
            {!! Form::label('pull_next_air', 'Pull Next Air Date: ') !!}
            {!! Form::checkbox('pull_next_air', Input::old('pull_next_air')) !!}
            <p class="help-hint">Check if you want to ignore the tagline and display the next air date.</p>
        </div>

        <h3>Image</h3>
        @if( !is_null($item) )
          @if (count( $item->images) > 0 )
            <table class="table no-margin">
            <thead><tr><th>Image</th><th>Action</th></tr></thead>
            <tbody>
              @foreach( $item->images as $image )
              <?php $image->toArray(); ?>
                <tr><td>
                <img src="{{ config('filesystems.disks.rackspace.public_url') }}{{ $image['url'] }}" style="max-width: 500px; height: auto;" /> 
                </td><td><a href="{{ URL::to('/image/remove/'.$image['id']) }}" onclick="return confirm('Are you sure? This will remove the image from our system.');">Remove</a>
                </tr>
              @endforeach
            </tbody>
            </table>
            <hr />
          @endif
        @endif

        <div class="form-group">
          {!! Form::label('image', 'Image: ') !!}
          {!! Form::file('image') !!}
        </div>

        {!! Form::submit('Save Changes', ['class' => 'btn btn-primary']) !!} 
        or <a href="{{ URL::to('/grid-cells/'.$grid->id) }}">Cancel</a>

      {!! Form::close() !!}

    </section><!-- /.content -->
  </div><!-- /.content-wrapper -->

@stop


