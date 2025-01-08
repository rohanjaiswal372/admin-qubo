@extends("app")
@section("content")

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1 class="pull-left">Tips</h1>
      <div class="pull-right">
        <a href="{{ route('tips.create') }}" class="btn btn-primary"><i class="fa fa-pencil-square"></i> Create New</a>
      </div>
    </section>
    
    <hr class="clearfix" />
    <section class="content">
      <table class="tablesorter table no-margin">
    <thead>
        <tr>
            <th>Title</th>
            <th>Summary</th>
            <th>Video</th>
            <th>Posted On</th>
            <th>Options</th>
        </tr>
    </thead>
    <tbody>
    @foreach($items as $item)
        <tr>
            <td>{{ $item->title }}</td>
            <td>{{ strip_tags($item->summary) }}</td>
            <td>{{ $item->video->brightcove_id }}</td>
            <td>{{ $item->created_at->format('M d Y') }}</td>
            <td>
              <a href="{{ route('tips.edit', $item->id) }}"><i class="fa fa-pencil-square fa-2x"></i></a>
              <a href="{{ URL::to('/tips/remove/'.$item->id) }}" onclick="return confirm('Are you sure you want to remove this?');"><i class="fa fa-times fa-2x"></i></a>
            </td>
        </tr>
      @endforeach
    </tbody>
</table>
    </section><!-- /.content -->
  </div><!-- /.content-wrapper -->

@stop


