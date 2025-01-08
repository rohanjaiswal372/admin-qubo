@extends("app")
@section("content")

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1 class="pull-left">Nielsen Sources</h1>
      <div class="pull-right">
        <a href="{{ route('nielsen.create') }}" class="btn btn-primary"><i class="fa fa-pencil-square"></i> Create New</a>
      </div>
    </section>
    
    <hr class="clearfix" />
    <section class="content">
      <table class="tablesorter table no-margin table-striped table-bordered table-hover table-condensed">
    <thead>
        <tr>
            <th>Source ID</th>
            <th>Description</th>
            <th>Options</th>
            
        </tr>
    </thead>
    <tbody>
    @foreach($items as $item)
        <tr>
            <td>
				<a href={{ $item->url() }} target="_blank">{{ $item->id }}</a>
			</td>
            <td>{{{ strip_tags($item->description) }}}</td>
            <td>
              <a href="{{ route('nielsen.edit', $item->id) }}" title="Edit: {{ $item->id }}" data-toggle="tooltip"><i class="fa
              fa-pencil-square fa-2x"></i></a>
              <a href="{{ URL::to('/nielsen/remove/'.$item->id) }}" title="Remove: {{ $item->id }}"
                 data-toggle="tooltip" onClick="return confirm('Are you sure you want to remove this Source?');"><i class="fa fa-times fa-2x"></i></a>

            </td>
        </tr>
           @endforeach
    </tbody>
</table>
    </section><!-- /.content -->
  </div><!-- /.content-wrapper -->

@stop


