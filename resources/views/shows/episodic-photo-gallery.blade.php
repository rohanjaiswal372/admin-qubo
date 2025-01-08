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

</style>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Shows
        <small>Still work in progress, dont break me ;)</small>
      </h1>


      <ol class="breadcrumb">
        <li>
          <a href="#"><i class="fa fa-dashboard"></i> Level</a>
        </li>
        <li class="active">Shows</li>
      </ol>
    </section>
	
	<div style="padding:20px;margin-top:5px;">

			<h3>{{ $show->name}}: Episodic Images</h3>
	
	
		   <div class="row">
						<div class="pull-right">
							 Show: {!! Form::select('show_id', ["Select One"] + $show_id_selector , $show->id , ['class' => 'form-control',
																												 'autocomplete'=>'off',
																												 'onchange'=>"window.location='".URL::to("shows/episodic-photos/")."/'+this.value;"
																									]) !!} 
																									
						</div>	
		  </div>
	
	
	@if($show->episodes)
     @foreach($show->episodes->sortBy('episode_number') as $episode)
	 <div class="bg-gray" style="width:100%;padding:5px;margin-top:5px;">
       <div id="preview-{{$episode->id}}"></div>
	   <h4>EP {{ $episode->episode_number }}: {{ $episode->name}}
	    <span class="pull-right">
		<a href="{{ URL::to("shows/episodes/media/create/".$episode->id) }}"><i class="fa fa-cloud-upload"></i></a>
		</span>
	   </h4>

	 </div>  
		@foreach($episode->images as $image)
			<table class="table no-margin table-striped table-hover table-bordered">
				<thead>
					<tr>
						<th style="width:500px;">Photo</th>
						<th>Thumbnail</th>
						<th style="width:100px;">Options</th>
					</tr>
				</thead>
				<tbody>
				<tr>
					<td>
						<img src="{{ image($image->url) }}" style="max-width:250px;max-height:250px;" />
					</td>
					<td>
						<img src="{{ image($image->thumbnail(480,270)->url) }}"  style="max-width:150px;max-height:150px;"/>
					</td>
					<td>
						&nbsp;
						<a  class="" target="_blank" href="{{ URL::to("shows/photos/delete/".$image->id) }}"><li class="fa fa-trash fa-2x"></li></a>
					</td>
				</tr>
				</tbody>
			</table>
		@endforeach
	  
      @endforeach
	@endif
	
	
	</div>
	
    </section><!-- /.content -->
  </div><!-- /.content-wrapper -->

@stop
