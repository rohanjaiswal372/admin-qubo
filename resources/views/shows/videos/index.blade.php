@extends("app")
@section('header')

@stop
@section("content")
        <!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Videos : {{  $show->name }}
        </h1>

        <ol class="breadcrumb">
            <li>
                <a href="#"><i class="fa fa-dashboard"></i> Shows</a>
            </li>
            <li class="active">Videos</li>
        </ol>
    </section>

    <section class="content box-body">
        <div class="box pad box-primary">
            <div class="box-header">
            </div>
            <div clas="box-body">
                <h3>Generic</h3>
                <div class="btn-group pull-right" role="group">

                    <div class="btn-group" role="group">
                        <button type="button"
                                class="btn btn-default dropdown-toggle"
                                data-toggle="dropdown"
                                aria-haspopup="true"
                                aria-expanded="false">
                            Shows <span class="caret"></span>
                        </button>
                        <ul class="dropdown-menu">
                            @foreach($Shows as $Show)
                                <li><a href="{{ URL::to('shows/videos/'.$Show->id) }}"
                                       title="{{$Show['name']}}">{{$Show['name']}}</a></li>
                            @endforeach
                        </ul>
                    </div>
                    <a href="{{ URL::to("shows/videos/media/create/?show_id=".$show->id) }}" class="btn btn-success"><i
                                class="fa fa-pencil-square"></i> Create New</a>
                    <a href="{{ URL::to("shows/videos/media/create-bulk/".$show->id) }}" class="btn btn-success"><i
                                class="fa fa-upload"></i> Create Bulk</a>
                </div>
                <br/>

                <br/>
                <table class="tablesorter table no-margin table-striped table-hover table-bordered">
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>Title</th>
                        <th>Brightcove ID</th>
                        <th>Options</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($show->videos as $video)
                        <tr>
                            <td>{{ $video->id }}</td>
                            <td>{{ ($video->title) ? $video->title : "None" }}</td>
                            <td>{{ $video->brightcove_id }}</td>
                            <td>
                                <a href="javascript:void(0);" title="Preview" data-toggle="tooltip"
                                   data-videoid="{{ $video->brightcove_id }}" class="video_preview"><i class="fa fa-play-circle
				fa-2x"></i></a> <a href="{{ route('videos.edit', $video->id) }}" title="Edit" data-toggle="tooltip"><i
                                            class="fa fa-pencil-square fa-2x text-info"></i></a>
                                <a href="{{ URL::to('shows/videos/delete/'.$video->id) }}" title="Remove"
                                   data-toggle="tooltip"><i
                                            class="fa fa-trash-o fa-2x text-danger"></i></a>
                        </tr>
                    @endforeach
                    </tbody>
                </table>

                <h3>Episodes</h3>
                <br/>
                <table id="" class="tablesorter table no-margin table-striped table-hover table-bordered">
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>Title</th>
                        <th>Brightcove ID</th>
                        <th>Options</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($show->episodes as $episode)
                        @if($episode->preview)
                            <tr>
                                <td>EP {{ $episode->episode_number }}</td>
                                <td> {{ $episode->name }}</td>
                                <td>{{ $episode->preview->brightcove_id }}</td>
                                <td>
                                    <a href="javascript:void(0);" title="Preview"= data-toggle="tooltip" data-videoid=
                                    "{{ $episode->preview->brightcove_id }}" class="video_preview"><i
                                            class="fa fa-play-circle fa-2x"></i></a>
                                    <a href="{{ route('videos.edit', $episode->preview->id) }}"
                                       title="Edit"
                                       data-toggle="tooltip"><i class="fa fa-pencil-square fa-2x text-info"></i></a> <a
                                            href="{{ URL::to('shows/videos/delete/'.$episode->preview->id) }}"
                                            title="Remove"
                                            data-toggle="tooltip"><i
                                                class="fa fa-trash-o fa-2x text-danger"></i></a>
                            </tr>
                        @endif
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </section><!-- /.content -->
</div><!-- /.content-wrapper -->
@include('shows.partials.videomodal')
@stop
@section('footer_js')
    <script src="{{ asset('js/brightcove/videojs-player.js') }}"></script>
@stop

