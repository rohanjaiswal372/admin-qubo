@extends("app")
@section("content")

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1 class="pull-left"><i class="fa fa-th-large"></i> Grids</h1>
            <div class="pull-right">
                <a href="{{ route('grids.create') }}" class="btn btn-primary"><i class="fa fa-pencil-square"></i> Create
                    New</a>
            </div>
        </section>

        <hr class="clearfix"/>
        <section class="content">
            <div class="box box-primary pad">
                <div class="box-body">
                    <table class="tablesorter table no-margin table-striped table-condensed">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>Active</th>
                            <th>Title</th>
                            <th>Layout</th>
                            <td>Created At</td>
                            <td>Updated At</td>
                            <th>Options</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($items as $item)
                            <tr>
                                <td>{{$item->id}}</td>
                                <td>
                                    @if ( $item->active == 1 )
                                        <i class="text-success fa fa-circle"></i>
                                    @else
                                        <i class="text-danger fa fa-circle"></i>
                                    @endif
                                </td>
                                <td>{{ $item->title }}</td>
                                <td>{{ $item->layout->title }}<br/>
                                    <img src="{{ asset("images/grid-layouts/".$item->layout->path.".jpg") }}"/></label>
                                </td>
                                <td>{{Carbon::parse($item->created_at)->toFormattedDateString()}}</td>
                                <td>{{Carbon::parse($item->updated_at)->toDayDateTimeString()}}</td>
                                <td>
                                    <a href="{{ route('grids.edit', $item->id) }}"><i class="fa fa-pencil-square fa-2x"></i></a>
                                    <a href="{{ route('pods.show', $item->id) }}"
                                       title="Edit Pods"><i class="fa fa-th fa-2x"></i></a>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </section><!-- /.content -->
    </div><!-- /.content-wrapper -->

@stop


