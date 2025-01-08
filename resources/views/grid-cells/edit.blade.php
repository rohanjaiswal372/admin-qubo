@extends("app")
@section("content")

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1><i class="fa fa-th-large"></i> Grid Edit</h1>
    </section>

    <!-- Main content -->
    <section class="content">
      <h3>Editing {{$item->title}}</h3>

      {{ HTML::ul($errors->all()) }}

      {!! Form::model($item, array('route' => array('grids.update', $item->id), 'method' => 'PUT')) !!}
        <div class="form-group">
          {!! Form::label('title', 'Title: ') !!}
          {!! Form::text('title', Input::old('title'), ['class' => 'form-control', 'placeholder' => 'Title']) !!}
        </div>	
		
        <div class="form-group">
          {!! Form::label('display_title', 'Display Title: ') !!}
          {!! Form::text('display_title', Input::old('title'), ['class' => 'form-control', 'placeholder' => 'Latest Shows']) !!}
        </div>

        <div class="form-group">
            {!! Form::label('active', 'Active: ') !!}
            {!! Form::checkbox('active', 1 , Input::old('active')) !!}
        </div>        

        <hr />
        <h2>Grid Layout</h2>
		<div class="form-group">

        @foreach ( $grid_layouts as $grid_layout )
			<div class="col-lg-3">
				  <br/>
				  <div>
				  <input type="radio" name="layout_id" id="layout_{{ $grid_layout->id }}" value="{{ $grid_layout->id }}" <?php if( $item->layout_id == $grid_layout->id ){ echo 'checked="checked"'; } ?>/>
				  <label for="layout_{{ $grid_layout['id'] }}"> {{ $grid_layout['title'] }}
				  </div>
				  <img src="{{ asset("images/grid-layouts/".$grid_layout->path.".jpg") }}" /></label>
				  <br/>
			</div>
        @endforeach
		
		</div>
		<br clear="all"/>
        <hr />

        {!! Form::submit('Save Changes', ['class' => 'btn btn-primary']) !!} 
        or <a href="{{ url('/grids/') }}">Cancel</a>

      {!! Form::close() !!}

    </section><!-- /.content -->
  </div><!-- /.content-wrapper -->

@stop


