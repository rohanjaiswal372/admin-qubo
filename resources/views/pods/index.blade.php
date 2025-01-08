@extends("app")
@section("content")
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1 class="pull-left">Pods</h1>
            <div class="pull-right">
                <a href="{{ route('pods.create') }}" class="btn btn-primary"><i class="fa fa-pencil-square"></i> Create
                    New</a>
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
                            <table class="tablesorter table no-margin table-striped table-condensed table-hover">
                                <thead>
                                <tr>
                                    <th>Name (Internal)</th>
                                    <th>Title</th>
                                    <th>Image</th>
                                    <th>Options</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($items as $item)
                                    <tr>
                                        <td>{{ $item->name }}</td>
                                        <td>{{ $item->title }}</td>
                                        <td>
                                            @if(isset($item->image))
                                                <img class="img-thumbnail" src="{{  image($item->image->thumbnail(150,150,"C")->url) }}"/>
                                            @endif
                                        </td>
                                        <td><a href="{{ route('pods.edit', $item->id) }}"><i
                                                        class="fa fa-pencil-square fa-2x"></i></a>
                                            <a href="{{ URL::to('/pods/delete/'.$item->id) }}" title="Delete" data-toggle="tooltip" onclick="return confirm('Are you sure you want to remove this?');"><i class="fa fa-trash-o fa-2x text-danger"></i></a>
                                        </td>
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


