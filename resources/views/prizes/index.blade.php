@extends("app")
@section("content")

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1 class="pull-left">Prizes</h1>
      <div class="pull-right">
        <a href="{{ route('prizes.create') }}" class="btn btn-primary"><i class="fa fa-pencil-square"></i> Create New</a>
      </div>
    </section>
    
    <hr class="clearfix" />
    <section class="content">
      <table class="tablesorter table no-margin">
    <thead>
        <tr>
            <th>Title</th>
            <th>Stock</th>
            <th>Points</th>
            <th>Active</th>
            <th>Options</th>
        </tr>
    </thead>
    <tbody>
    @foreach($items as $item)
        <tr>
            <td>{{ $item->title }}</td>
            <td>{{ $item->stock }}</td>
            <td>{{ $item->points }}</td>
            <td>
              @if( $item->active )
                Yes
              @else
                No
              @endif
            </td>
            <td><a href="{{ route('prizes.edit', $item->id) }}"><i class="fa fa-pencil-square fa-2x"></i></a></td>
        </tr>
      @endforeach
    </tbody>
</table>
    </section><!-- /.content -->
  </div><!-- /.content-wrapper -->

@stop


