@extends("app")
@section("content")

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1 class="pull-left">Contests</h1>
        <div class="pull-right">
        </div>
    </section>
    
    <hr class="clearfix" />
    
    <section class="content">
    <h3>All Contests</h3>

    <table class="table tablesorter no-margin table-striped table-bordered table-hover table-condensed">
      <thead>
        <tr>
            <th><i class="fa fa-eye"></i></th>
          <th>Name</th>
          <th>Description</th>
            <th>Date Start</th>
            <th>Date End</th>
            <th>Scope</th>
            <th>Options</th>
        </tr>
      </thead>
		@foreach( $data as $key => $item )
		<tr @if($item->scope == "iontv")  class="info"  @endif>
            <td>
                <a class="btn btn-default" href="{{ route('contests.show', $item->slug) }}" title="View {{ $item->name }} Results"  data-toggle="tooltip"><i class="fa fa-eye"></i></a>
                <a class="btn btn-default" href="{{ URL::to('contests/export/'.$item->slug) }}" title="Exports {{ $item->name }} Results"  data-toggle="tooltip"><i class="fa fa-table"></i></a>
            </td>
	 		<td>{{ $item->name }}</td>
	 		<td>{{ $item->description }}</td>
            <td>{{date('m-d-Y',strtotime($item->start_date))}}</td>
            <td>{{date('m-d-Y',strtotime($item->end_date))}}</td>
            <td>{{ $item->scope }}</td>
            <td>
                <a href="{{ URL::to('/contests/edit', $item->id) }}" title="Edit: {{ $item->name }}" data-toggle="tooltip"><i class="fa fa-pencil-square fa-2x"></i></a>
            </td>
		 </tr>
		@endforeach
      </tbody>
    </table>

    </section><!-- /.content -->
  </div><!-- /.content-wrapper -->

@stop


