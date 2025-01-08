@extends("app")
@section("content")

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="pull-left"><h1><i class="fa fa-th-large"></i> Grids</h1></div>
            <div class="pull-right">
                <a href="{{ route('grids.create') }}" class="btn btn-primary"><i class="fa fa-pencil-square"></i> Create
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
                            <table class="tablesorter table no-margin">
                                <thead>
                                <tr>
                                    <th>Title</th>
                                    <th>Layout</th>
                                    <th>Active</th>
                                    <th>Options</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($items as $item)
                                    <tr>
                                        <td>{{ $item->title }}</td>
                                        <td>{{ $item->layout->title }}<br/>
                                            <img src="{{ asset("images/grid-layouts/".$item->layout->path.".jpg") }}"/></label>
                                        </td>
                                        <td>
                                            @if ( $item->active )
                                                Active
                                            @else
                                                Not-Active
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('grids.edit', $item->id) }}"><i
                                                        class="fa fa-pencil-square fa-2x"></i></a>
                                            <a href="{{ route('pods.show', $item->id) }}" title="Edit Pods"><i
                                                        class="fa fa-th fa-2x"></i></a>
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


