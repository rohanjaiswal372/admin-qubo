@extends("app")
@section("content")

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="box">
            <section class="content-header box-header with-border">
                <h3 class="box-title">Shows</h3>
                <div class="box-tools pull-right">
                    <div class="btn-group">
                        <a href="{{URL::to('shows/create')}}"
                           class="btn btn-success"
                           title="Create new show"
                           data-toggle="tooltip"><i
                                    class="fa fa-pencil-square"></i> New</a>
                        @if(Auth::user()->hasPermission("brightcove"))
                            <a href="{{ URL::to('/shows/videos/process/1/') }}"
                               title="Update Show Promos BrightCove custom fields "
                               class="btn btn-danger"
                               class="btn btn-danger"
                               data-toggle="tooltip"><i class="fa
			fa-cloud-upload"></i></a>@endif
                    </div>
                </div>
            </section>
            <!-- Main content -->
            <section class="content box-body">
                <div class="box pad box-primary">
                    <div class="box-header">
                        <h3 class="box-title">Active</h3>
                    </div>
                    <div class="box-body">
                        <table class="tablesorter table no-margin table-striped table-hover table-bordered table-responsive">
                            <thead>
                            <tr>
                                <th>ID</th>
                                <th>Status</th>
                                <th>Name</th>
                                <th>Broadview Handle</th>
                                <th>Next Air Date</th>
                                <th>Show Options</th>
                                <th>Episodic Options</th>
                                <th>Other Options</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($shows as $show)
                                <tr>
                                    <td>{{ $show->id }}</td>
                                    <td>@if($show->active) <span class="label label-success">Active</span>@else<span
                                                class="label label-danger">In Active</span>@endif</td>
                                    <td>@if($show->logo)<img src="{{image($show->logo->url)}}"
                                                             class="img img-responsive col-md-1 pull-left"/>@endif<span
                                                class="box-title pull-left">{{ $show->name }}</span>
                                        <a target="_blank"
                                           class="btn btn-xs btn-default pull-right"
                                           href="{{ $show->url }}"
                                           title="{{ $show->url }}"><i
                                                    class="fa fa-eye"></i> Show Page</a></td>
                                    <td>@if($show->broadview_handle){{$show->broadview_handle}}@endif</td>
                                    <td>
                                        @if($show->upcoming_program)
                                            <label class="label label-default">{!! $show->upcoming_program->date_and_time()!!}</label>
                                        @endif
                                    </td>
                                    <td class="col-md-1">
                                        <a href="{{ route('shows.edit', $show->id) }}"
                                           title="Edit"
                                           data-toggle="tooltip"><i
                                                    class="fa
            fa-pencil-square fa-2x"></i></a> <a href="{{ URL::to('shows/media/create/'.$show->id) }}"
                                                title="Photo Upload"
                                                data-toggle="tooltip"><i class="fa
			fa-cloud-upload fa-2x text-success"></i></a> <a href="{{ URL::to('shows/photos/'.$show->id) }}"
                                                            title="Photos"
                                                            data-toggle="tooltip"><i
                                                    class="fa fa-file-image-o fa-2x"></i></a>
                                        @if($show->preview)<a href="javascript:void(0);"
                                                              title="Preview"
                                                              data-toggle="tooltip"
                                                              data-videoid="{{ $show->preview->brightcove_id }}"
                                                              class="video_preview"><i
                                                    class="fa fa-play-circle
				fa-2x"></i></a>@endif
                                    </td>
                                    <td class="col-md-2">
                                        <a href="{{ URL::to('shows/episodic-photos/'.$show->id) }}"
                                           title="Episodic Photo Gallery"
                                           data-toggle="tooltip"> <i class="fa fa-camera fa-2x teal"></i> </a>
                                        <a href="{{ URL::to('shows/episodes/'.$show->id) }}"
                                           title="Episodes"
                                           data-toggle="tooltip"><i
                                                    class="fa fa-film fa-2x"></i> </a>
                                    </td>
                                    <td>
                                        <a href="{{ URL::to('shows/casts/'.$show->id) }}"
                                           title="Casts"
                                           data-toggle="tooltip"><i
                                                    class="fa fa-users
			fa-2x"></i></a> <a href="{{ URL::to('shows/videos/'.$show->id) }}" title="Videos" data-toggle="tooltip"><i
                                                    class="fa
			fa-video-camera fa-2x"></i></a>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </section><!-- /.content -->
        </div>
    </div><!-- /.content-wrapper -->
    @include('shows.partials.videomodal')
@stop
@section('footer_js')
    <script src="{{ asset('js/brightcove/videojs-player.js') }}"></script>
@stop


