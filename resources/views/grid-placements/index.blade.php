@extends("app")
@section("content")

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1 class="pull-left"><i class="fa fa-th-large"></i> Grid Placements</h1>

            <div class="pull-right">
                <a href="{{ route('grid-placements.create') }}" class="btn btn-primary"><i
                            class="fa fa-pencil-square"></i> Create New</a>
            </div>
        </section>

        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="box box-primary pad">
                        <div class="box-header with-border">
                            <div class="box-tools pull-right">
                            </div>
                        </div>
                        <div class="box-body">
                            <table class="table tablesorter no-margin table-striped table-bordered table-hover table-condensed">
                                <thead>
                                <tr>
                                    <th>Type</th>
                                    <th>Name</th>
                                    <th>URL</th>
                                    <th>Grids</th>
                                    <th>Options</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($items as $item)
                                    <tr>
                                        <td>{{ (get_class($item) == "App\Show") ?  ucwords($item->type_id)  : str_replace("App\\","",get_class($item)) }}</td>
                                        <td>{{ ($item->title) ?  $item->title : $item->name }}</td>
                                        <td><a href="{{  $item->url }}" target="_blank">{{  $item->url }}</a></td>
                                        <td>
                                            <ul>
                                                @foreach($item->grids() as $grid)
                                                    <li> {{ $grid->title }}</li>
                                                @endforeach
                                            </ul>
                                        </td>
                                        <td>
                                            <a href="{{ route('grid-placements.show', [ "type"=>str_replace("app\\","", strtolower(get_class($item))) , "id"=>$item->id]) }}"><i
                                                        class="fa fa-pencil-square fa-2x"></i></a></td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </section><!-- /.content -->
    </div><!-- /.content-wrapper -->

@stop


