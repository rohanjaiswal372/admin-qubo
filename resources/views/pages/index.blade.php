@extends("app")
@section("content")

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1 class="pull-left"><i class="fa fa-file-o"></i> Pages</h1>
      <div class="pull-right">
          <a href="{{ URL::previous() }}" class="btn btn-primary"><i class="fa fa-undo"></i> Back</a>
        <a href="{{ route('pages.create') }}" class="btn btn-info"><i class="fa fa-pencil-square"></i> Create New</a>
      </div>
    </section>
    
    <hr class="clearfix" />
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary pad">
                    <div class="box-header with-border">
                        <div class="box-tools pull-right">

                        </div>
                    </div>

                    <div class="box-body">
      <table class="tablesorter table no-margin table-striped table-bordered table-hover table-condensed">
    <thead>
        <tr>
            <th>Active</th>
            <th>ID</th>
            <th>Name</th>
            <th>Colors</th>
            <th>Searchable</th>
            <th>Slug</th>
            <th>Options</th>
        </tr>
    </thead>
    <tbody>
    @foreach($items as $item)
        <tr class="clickable-row @if(!$item->active) text-muted @endif" data-href="{{ route('pages.edit', $item->id) }}" title="Edit Page: {{$item->title}}" data-toggle="tooltip" >
            <td class="text-center">@if($item->active) <i class="fa fa-circle text-success"></i> @else <i class="fa fa-ban text-danger"></i> @endif</td>
            <td>{{ $item->id }}</td>
            <td>{{ $item->title }}</td>
            <td>@if($item->colors)
                    @foreach($item->colors as $color)
                            <div class="pad" style="background-color:{{$color->code}}">
                                <small>{{$color->type->description}}</small>
                            </div>
                    @endforeach
                    @endif
            </td>
            <td>@if($item->searchable)<i class="fa fa-search"></i> @endif</td>

            <td>{{ $item->path }}</td>
            <td>
                <a href="{{ route('pages.edit', $item->id) }}" title="Edit: {{ $item->title }}" data-toggle="tooltip"><i class="fa fa-pencil-square fa-2x"></i></a>
                <a href="{{ URL::to('/pages/remove/'.$item->id) }}" onClick="return confirm('Are you sure you want to remove this Page?');"><i class="fa fa-trash-o text-danger fa-2x"></i></a>
            </td>
        </tr>
      @endforeach
    </tbody>
</table>
                        </div></div></div></div>
    </section><!-- /.content -->
  </div><!-- /.content-wrapper -->

@stop


