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
        Videos
        <small>: {{  $show->name }}</small>
      </h1>


      <ol class="breadcrumb">
        <li>
          <a href="#"><i class="fa fa-dashboard"></i> Level</a>
        </li>
        <li class="active">Videos</li>
      </ol>
    </section>
	

    <!-- Main content -->
    <section class="content">
      <h3>Generic</h3>
      <a href="{{ URL::to("shows/videos/media/create/?show_id=".$show->id) }}" class="pull-right btn btn-primary"><i class="fa fa-pencil-square"></i> Create New</a>
	  <br/>
	  <br/>
      <table class="tablesorter table no-margin">
    <thead>
        <tr>
            <th>ID</th>
            <th>Title</th>
            <th>Brightcove ID</th>
            <th>Options</th>
        </tr>
    </thead>
    <tbody>
    @foreach($show->videos as $video)
        <tr>
            <td>{{ $video->id }}</td>
            <td>{{ ($video->title) ? $video->title : "None" }}</td>
            <td>{{ $video->brightcove_id }}</td>
            <td>
				<a href="javascript:void(0);"><i class="fa fa-play-circle fa-2x"></i></a>
				<a href="{{ URL::to('shows/videos/delete/'.$video->id) }}"><i class="fa fa-trash-o fa-2x"></i></a>
			</tr>
      @endforeach
    </tbody>
</table>

<h3>Episodes</h3>
<br/>
<table id="" class="tablesorter table no-margin">
    <thead>
        <tr>
            <th>ID</th>
            <th>Title</th>
            <th>Brightcove ID</th>
            <th>Options</th>
        </tr>
    </thead>
    <tbody>
     @foreach($show->episodes as $episode)
         @if($episode->preview)
			<tr>
				<td>EP {{ $episode->episode_number }}</td>
				<td> {{ $episode->name }}</td>
				<td>{{ $episode->preview->brightcove_id }}</td>
				<td>
                    <a href="javascript:void(0);" title="Preview"= data-toggle="tooltip" data-videoid=
                    "{{ $episode->preview->brightcove_id }}" class="video_preview"><i
                            class="fa fa-play-circle fa-2x"></i></a>
				<a href="{{ URL::to('shows/casts/'.$episode->preview->id) }}"><i class="fa fa-trash-o fa-2x"></i></a>
			</tr>
		 @endif
      @endforeach
    </tbody>
</table>

    </section><!-- /.content -->
  </div><!-- /.content-wrapper -->


@include('shows.partials.videomodal')
@stop
@section('footer_js')
    <script src="//sadmin.brightcove.com/js/BrightcoveExperiences.js"></script>
    <script src="{{ asset('js/brightcove/brightcove-player.js') }}"></script>
@stop


