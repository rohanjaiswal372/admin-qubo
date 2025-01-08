@extends("app")
@section("content")
        <!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="pull-left">
            <h3>{{ $show->name}} Episodes</h3>
            @if($show->logo)
                @if(!strstr($show->logo->url, ":"))
                    <img class="img img-thumbnail" style="background-color:{{$show->color}}; max-width:150px;"
                         src="{{image($show->logo->url) }}"/>
                @else
                    <img class="img img-thumbnail" style="background-color:{{$show->color}}"
                         src="{{URL::to('proxy.php?type=image&url='.$show->logo->url) }}"/>
                @endif
            @endif
        </div>
        <div class="pull-right">
            <div class="btn-group">

                <a href="{{ URL::to('shows') }}" class="btn btn-primary"><i
                            class="fa fa-undo"></i> Back</a> <a href="{{ URL::to('shows/episodes/new/'.$show->id) }}"
                                                                class="btn btn-success"
                                                                title="Create new {{$show->name}}&rsquo;s episode"
                                                                data-toggle="tooltip"
                ><i class="fa fa-pencil-square"></i></a> <a href="{{ URL::to('shows/episodes/export/xls/'.$show->id) }}"
                                                            class="btn btn-default"
                                                            title="Export {{$show->name}}'s episode list to XLS"
                                                            data-toggle="tooltip"><i
                            class="fa fa-file-excel-o"></i></a>
                <a href="{{ URL::to('shows/episodes/export/csv/'.$show->id) }}"
                   class="btn btn-default"
                   title="Export {{$show->name}}&rsquo;s episode list to CSV"
                   data-toggle="tooltip"><i
                            class="fa fa-file-text-o"></i></a>
                @if(Auth::user()->hasPermission("brightcove"))
                    <a href="{{ URL::to('/shows/videos/process/2/'.$show->id) }}"
                       title="Update {{$show->name}}'s episode BrightCove custom fields " class="btn btn-danger"
                       data-toggle="tooltip"><i class="fa
			fa-cloud-upload"></i></a>@endif
                {{-- <button class="btn btn-danger delete-selected"><i class="fa fa-times"></i> Delete Multiple</button> --}}
            </div>
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
                        <table class="tablesorter table no-margin no-margin table-striped table-hover table-condensed table-bordered">
                            <thead>
                            <tr style="background-color:{{$show->color}}">
                                <th>ID</th>
                                <th>Season</th>
                                <th>Episode Number</th>
                                <th>Name</th>
                                <th>Rating</th>
                                <th>Next Air Date</th>
                                <th>Publication Date</th>
                                <th>Options</th>
                            </tr>
                            </thead>
                            <tbody>

                            @foreach($show->episodes as $episode)
                                <tr>
                                    <td>{{ $episode->id }}</td>
                                    <td>{{$episode->season}}</td>
                                    <td>EP {{ $episode->episode_number }}</td>
                                    <td>{{ $episode->name }}</td>
                                    <td>{{$episode->rating}}</td>
                                    <td>
                                        @if($episode->published_at)
                                            <span class="@if(Carbon::now()->gte(Carbon::parse($episode->published_at))) text-muted @else badge @endif" >
                                            {{  Carbon::parse($episode->published_at)->format('m/d/Y') }}
                                           </span>
                                        @endif
                                        @if($episode->new)
                                            <span class="label label-danger pull-right">NEW EPISODE</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group">
                                            <a class='btn'
                                               href="{{ route('shows.episodes.edit', $episode->id) }}"
                                               title="Edit"
                                               data-toggle="tooltip"><i
                                                        class="fa fa-pencil-square fa-2x"></i></a>
                                            <a href="{{ URL::to('shows/episodes/media/create/'.$episode->id) }}"
                                               title="Create Photo"
                                               class="btn"
                                               data-toggle="tooltip"><i class="fa fa-picture-o
			fa-2x text-success"></i></a>
                                            @if($episode->videos && $episode->videos->brightcove_id)
                                                <a href="javascript:void(0);"
                                                   title="Preview"
                                                   data-toggle="tooltip"
                                                   data-videoid="{{ $episode->videos->brightcove_id }}"
                                                   class="video_preview btn"><i
                                                            class="fa fa-play-circle
				fa-2x"></i></a>

                                            @else
                                                <a href="{{ route('shows.episodes.edit', $episode->id) }}"
                                                   title="Add Video Preview"
                                                   data-toggle="tooltip"
                                                   class="btn"> <i
                                                            class="fa fa-plus-circle fa-2x text-success"></i></a>
                                            @endif
                                            <a href="{{ URL::to('shows/episodes/'.$episode->id.'/delete') }}"
                                               onclick="return confirm('Are you Sure you want to delete this episode')"
                                               title="Remove Episode"
                                               data-toggle="tooltip"
                                               class="btn"><i
                                                        class="fa fa-trash fa-2x text-danger"></i></a>
                                        </div>
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
@include('shows.partials.videomodal')
@stop
@section('footer_js')
    <script src="{{ asset('js/brightcove/videojs-player.js') }}"></script>
@stop
