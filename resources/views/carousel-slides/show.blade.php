@extends("app")
@section("content")

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1 class="pull-left">Carousels</h1>
      <div class="pull-right">
        <a href="{{ URL::to('carousel-slides/create/'.$carousel->id) }}" class="btn btn-primary"><i class="fa fa-pencil-square"></i> Create New</a>
      </div>
    </section>
    
    <hr class="clearfix" />
    <section class="content">
      <table class="tablesorter table no-margin">
    <thead>
        <tr>
            <th>Sort</th>
            <th>Title</th>
            <th>URL</th>
            <th>Show</th>
            <th>Scheduling</th>
            <th>Options</th>
        </tr>
    </thead>
    <tbody>
      @foreach($slides as $slide)
        <tr>
            <td>{{ $slide->sort_order }}</td>
            <td>{{ $slide->title }}</td>
            <td>{{ $slide->url }}</td>
            <td>
              @if (isset($slide->show->name) )
                {{ $slide->show->name }}
              @endif
            </td>
            <td>
              @if( is_null($slide->starts_at) )
                N/A
              @else
                {{ date('m/d/Y g:i A', strtotime($slide->starts_at)) }} - {{ date('m/d/Y g:i A', strtotime($slide->ends_at)) }}
              @endif
            </td>
            <td>
              <a href="{{ route('carousel-slides.edit', $slide->id) }}"><i class="fa fa-pencil-square fa-2x"></i></a>
              <a href="{{ URL::to('/carousel-slides/remove/'.$slide->id) }}" onClick="return confirm('Are you sure you want to remove this slide?')"><i class="fa fa-times fa-2x"></i></a>
            </td>
        </tr>
      @endforeach
    </tbody>
</table>
    </section><!-- /.content -->
  </div><!-- /.content-wrapper -->

@stop


