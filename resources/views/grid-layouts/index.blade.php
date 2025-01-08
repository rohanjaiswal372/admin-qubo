@extends("app")
@section("content")

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1 class="pull-left"><i class="fa fa-th-large"></i> Grid Layouts</h1>
            <div class="pull-right">
                <a href="{{ route('grid-layouts.create') }}" class="btn btn-primary"><i class="fa fa-pencil-square"></i>
                    Create New</a>
            </div>
        </section>

        <hr class="clearfix"/>
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="box box-primary pad">
                        <div class="box-header with-border">
                            <div class="box-tools pull-right">
                            </div>
                        </div>
                        <div class="box-body">
                            <table class="tablesorter table no-margin">
                                <thead>
                                <tr>
                                    <th>Layout Example</th>
                                    <th>Title</th>
                                    <th>Path</th>
                                    <th>Options</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($items as $item)
                                    <tr>
                                        <td><img src="images/grid-layouts/{{ $item->path }}.jpg"/></td>
                                        <td>{{ $item->title }}</td>
                                        <td>{{ $item->path }}</td>
                                        <td><a href="{{ route('grid-layouts.edit', $item->id) }}"><i
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


