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
        Cast Members
        <small>{{ $show->name }}</small>
      </h1>


      <ol class="breadcrumb">
        <li>
          <a href="#"><i class="fa fa-dashboard"></i> Level</a>
        </li>
        <li class="active">Cast Memebrs</li>
      </ol>
    </section>
	

    <!-- Main content -->
    <section class="content">
      <a href="{{ URL::to("shows/casts/create/".$show->id) }}" class="pull-right btn btn-primary"><i class="fa fa-pencil-square"></i> Create New</a>
	  <br clear="all" />
	  <br clear="all" />

      <table class="tablesorter table no-margin">
    <thead>
        <tr>
            <th>ID</th>
            <th>Sort Order</th>
            <th>Picture</th>
            <th>Name</th>
            <th>Real Name</th>
            <th>Options</th>
        </tr>
    </thead>
    <tbody>
    @foreach($show->casts as $cast)
        <tr>
            <td>{{ $cast->id }}</td>
            <td>{{ $cast->sort_order }}</td>
            <td><img src="{{ $cast->pod_image ? image($cast->pod_image->url) : "https://placehold.it/320x240" }}" style="width:80px;"/></td>
            <td>{{ $cast->name }}</td>
            <td>{{ $cast->real_name }}</td>
            <td>
            <a href="{{ route('shows.casts.edit', $cast->id) }}"><i class="fa fa-pencil-square fa-2x"></i></a>
			<a href="{{ URL::to('shows/casts/media/create/',$cast->id) }}"><i class="fa fa-picture-o fa-2x"></i></a>
			</td>
        </tr>
      @endforeach
    </tbody>
</table>


    </section><!-- /.content -->
  </div><!-- /.content-wrapper -->

@stop


