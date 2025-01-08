@extends("app")
@section("content")
    <style>
        .featuredMarker {
            display: block;
        }

        .green, .green:visited, .green:link, .green:hover, .green:active {
            background-color: transparent;
            color: green;
            text-shadow: none;
        }

        .red, .red:visited, .red:link, .red:hover, .red:active {
            background-color: transparent;
            color: red;
            text-shadow: none;
        }

    </style>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                Shows
                <small>Still work in progress, dont break me ;)</small>
            </h1>

            <ol class="breadcrumb">
                <li>
                    <a href="#"><i class="fa fa-dashboard"></i> Level</a>
                </li>
                <li class="active">Shows</li>
            </ol>
        </section>

        <!-- Main content -->
        <section class="content">
            <h3>Active</h3>
            <a href="shows/create" class="pull-right btn btn-primary"><i class="fa fa-pencil-square"></i> Create New</a>
            <br clear="all"/> <br clear="all"/>

            <table class="tablesorter table no-margin table-striped table-hover table-bordered">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Options</th>
                </tr>
                </thead>
                <tbody>
                @foreach($ActiveShows as $show)
                    <tr>
                        <td>{{ $show->id }}</td>
                        <td>{{ $show->name }}</td>
                        <td>
                            <a href="{{ route('shows.edit', $show->id) }}" title="Edit" data-toggle="tooltip"><i class="fa
            fa-pencil-square fa-2x"></i></a> <a href="{{ URL::to('shows/media/create/'.$show->id) }}"
                                                title="Photo Upload"
                                                data-toggle="tooltip"><i class="fa
			fa-cloud-upload fa-2x"></i></a>

                            <a href="{{ URL::to('shows/photos/'.$show->id) }}" title="Photos" data-toggle="tooltip"><i
                                        class="fa fa-image-o fa-2x"></i></a>

                            <a href="{{ URL::to('shows/episodic-photos/'.$show->id) }}"
                               title="Episodic Photo Gallery"
                               data-toggle="tooltip"><i class="fa fa-picture-o fa-2x"></i></a>

                            <a href="{{ URL::to('shows/casts/'.$show->id) }}" title="Casts" data-toggle="tooltip"><i
                                        class="fa fa-users
			fa-2x"></i></a> <a href="{{ URL::to('shows/videos/'.$show->id) }}" title="Videos" data-toggle="tooltip"><i
                                        class="fa
			fa-video-camera fa-2x"></i></a> <a href="{{ URL::to('shows/episodes/'.$show->id) }}"
                                               title="Episodes"
                                               data-toggle="tooltip"><i class="fa
			fa-film fa-2x"></i></a>
                    </tr>
                @endforeach
                </tbody>
            </table>

            <h3>Inactive</h3>
            <table id="myTable" class="tablesorter table no-margin table-striped table-hover table-bordered">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Options</th>
                </tr>
                </thead>
                <tbody>
                @foreach($InActiveShows as $show)
                    <tr>
                        <td>{{ $show->id }}</td>
                        <td>{{ $show->name }}</td>
                        <td>
                            <a href="{{ route('shows.edit', $show->id) }}"
                               title="Edit: {{ $show->name }}"
                               data-toggle="tooltip"><i class="fa
				fa-pencil-square fa-2x"></i></a> <a href="{{ URL::to('shows/media/create/'.$show->id) }}"
                                                    title="Upload Photo"
                                                    data-toggle="tooltip"><i class="fa fa-cloud-upload fa-2x"></i></a>

                            <a href="{{ URL::to('shows/photos/'.$show->id) }}" title="Photos" data-toggle="tooltip"><i
                                        class="fa fa-image-o fa-2x"></i></a>

                            <a href="{{ URL::to('shows/episodic-photos/'.$show->id) }}"
                               title="Episodic Photo Gallery"
                               data-toggle="tooltip"><i
                                        class="fa fa-picture-o fa-2x"></i></a>

                            <a href="{{ URL::to('shows/casts/'.$show->id) }}" title="Casts" data-toggle="tooltip"><i
                                        class="fa
				fa-users fa-2x"></i></a> <a href="{{ URL::to('shows/videos/'.$show->id) }}"
                                            title="Videos"
                                            data-toggle="tooltip"><i class="fa
				fa-video-camera fa-2x"></i></a> <a href="{{ URL::to('shows/episodes/'.$show->id) }}"
                                                   title="Episodes"
                                                   data-toggle="tooltip"><i
                                        class="fa fa-film fa-2x"></i></a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>

        </section><!-- /.content -->
    </div><!-- /.content-wrapper -->
    @include('shows.partials.videomodal')
@stop
@section('footer_js')
    <script src="{{ asset('js/brightcove/videojs-player.js') }}"></script>
@stop
