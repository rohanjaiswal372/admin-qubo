@extends("app")
@section("content")

        <!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="pull-left">
            <h3 class="box-title">Posts : <small>{{Request::path()}}</small></h3>
        </div>
        <div class="pull-right ">
            <a href="{{ route('posts.create') }}" class="btn btn-success"><i class="fa fa-pencil-square"></i> Create New</a>
        </div>
        <br class="clearfix"/>
        <hr>
    </section>

    <section class="content pad">
        <table class="tablesorter table no-margin table-stripped table-condensed">
            <thead>
            <tr>
                <th>ID</th>
                <th>BrightcoveID</th>
                <th>VideoableID</th>
                <th>VideoableType</th>
                <th>Created At</th>
                <th>Updated At</th>
                <th>Options</th>
            </tr>
            </thead>
            <tbody>
            @foreach($videos as $video)
                <tr>

                    <td>{{ $video->id }}</td>
                    <td>{{ $video->brightcove_id }}</td>
                  <td>{{$video->videoable_id}}</td>
                    <td>{{$video->videoable_type}}</td>
                    <td>{{Carbon::parse($video->created_at)->toFormattedDateString()}}</td>
                    <td>{{Carbon::parse($video->updated_at)->toDayDateTimeString()}}</td>
                    <td><a href="{{ route('videos.edit', $video->id) }}" title="Edit" data-toggle="tooltip" ><i class="fa fa-pencil-square-o fa-2x"></i></a></td>
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

