@extends("app")
@section("content")

<style>
.featuredMarker {display:block;}

.green, .green:visited,.green:link, .green:hover, .green:active {
    background-color: transparent;
    color:green;
    text-shadow: none;
}

.red, .red:visited,.red:link, .red:hover, .red:active {
    background-color: transparent;
    color:red;
    text-shadow: none;
}

label {
   clear:both;
   display:block;
}

</style>


  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        casts
        <small>Still work in progress, dont break me ;)</small>
      </h1>
      <ol class="breadcrumb">
        <li>
          <a href="#"><i class="fa fa-dashboard"></i> Level</a>
        </li>
        <li class="active">casts</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <h3>Editing {{$cast->name}}</h3>

		{!! Form::model($cast, array('route' => array('shows.casts.update', $cast->id), 'method' => 'PUT')) !!}

		

			<div class="form-group">
				  <div class="row">
				   <div class="form-group col-lg-3">
					  {!! Form::label('show_id', 'Show: ') !!}
					  {!! Form::select('show_id', ["Select One"] + $show_id_selector , $cast->show->id , ['class' => 'form-control','autocomplete'=>"off"] ) !!}
					</div>	   
				  </div>
			</div>	
		
		
			<div class="form-group">
			{!! Form::label('name', 'Name: ') !!}
            {!! Form::text('name', Input::old('name'), ['class' => 'form-control', 'placeholder' => 'Cast Name']) !!}			
			</div>
			

			<div class="form-group">
			{!! Form::label('real_name', 'Real Name: ') !!}
            {!! Form::text('real_name', Input::old('real_name'), ['class' => 'form-control', 'placeholder' => 'Real Name']) !!}		
			</div>


			<div class="form-group">
			{!! Form::label('description', 'Bio: ') !!}
            {!! Form::textarea('description', Input::old('description'), ['class' => 'form-control', 'placeholder' => 'Bio']) !!}		
			</div>
			
			<div class="form-group">
			{!! Form::label('description', 'Sort Order: ') !!}
            {!! Form::select('sort_order',range(0,10),Input::old('sort_order'), ['class' => 'form-control col-md-2', 'placeholder' => 'Sort Order']) !!}		
			</div>
			

			<br clear="all" />
			<br clear="all" />

			<div class="form-group">
			{!! Form::label('facebeook_handle', 'Facebook URL: ') !!}
            {!! Form::text('facebook_handle', Input::old('facebook_handle'), ['class' => 'form-control', 'placeholder' => 'http://']) !!}		
			</div>

		     <div class="form-group">
			{!! Form::label('twitter_handle', 'Twitter URL: ') !!}
            {!! Form::text('twitter_handle', Input::old('twitter_handle'), ['class' => 'form-control', 'placeholder' => 'http://']) !!}		
			</div>

			<div class="form-group">
			{!! Form::label('instagram_handle', 'Instagram URL: ') !!}
            {!! Form::text('instagram_handle', Input::old('instagram_handle'), ['class' => 'form-control', 'placeholder' => 'http://']) !!}		
			</div>

			<br clear="all" />
			<br clear="all" />


			<input type="submit" value="Save Changes" class="btn btn-primary">

      {!! Form::close() !!}



    </section><!-- /.content -->
  </div><!-- /.content-wrapper -->

@stop


