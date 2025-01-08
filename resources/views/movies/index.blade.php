@extends("app")
@section("content")
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="pull-left">
                <h3>Movies</h3>
            </div>
            <div class="pull-right">
                <a href="movies/create" class="pull-right btn btn-primary"><i
                            class="fa fa-pencil-square"></i> Create New</a>
                <a href="{{ URL::previous() }}" class="btn btn-warning"><i class="fa fa-undo"></i> Back</a>
            </div>
        </section>

        <!-- Main content -->
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
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Options</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($movies as $movie)
                                    <tr>
                                        <td>{{ $movie->id }}</td>
                                        <td>{{ $movie->name }}</td>
                                        <td>
                                            <a href="{{ route('movies.edit', $movie->id) }}" title="Edit"
                                               data-toggle="tooltip"><i class="fa fa-pencil-square fa-2x"></i></a>
                                            <a href="{{ URL::to('movies/media/create/'.$movie->id) }}" title="Create"
                                               data-toggle="tooltip"><i
                                                        class="fa fa-picture-o fa-2x"></i></a>
                                            <a href="{{ URL::to('movies/casts/'.$movie->id) }}" title="Casts"
                                               data-toggle="tooltip"><i class="fa
			fa-users fa-2x"></i></a>
                                            <a href="{{ URL::to('movies/videos/'.$movie->id) }}" title="Videos"
                                               data-toggle="tooltip"><i class="fa
			fa-video-camera fa-2x"></i></a>
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


