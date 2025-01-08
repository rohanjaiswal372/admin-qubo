@extends("app")
@section("content")


  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1 class="pull-left"><i class="fa fa-file-o"></i> Posts</h1>
      <div class="pull-right">
          <a href="{{ URL::previous() }}" class="btn btn-primary"><i class="fa fa-undo"></i> Back</a>
        <a href="{{ route('posts.create') }}" class="btn btn-info"><i class="fa fa-pencil-square"></i> Create New</a>
      </div>
    </section>

    <hr class="clearfix" />
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary pad">
                    <div class="box-header with-border">
                        <div class="box-tools pull-right">

                        </div>
                    </div>

                    <div class="box-body">
   
                      <table class="tablesorter table no-margin table-striped table-condensed">
                    <thead>
                        <tr>
                            <th>Active</th>
                            <th>Title</th>
                            <th>Path</th>
                            <th>Summary</th>
                            <th>Posted On</th>
                            <th>Author</th>
                            <!--<th>Sponsor</th>-->
                            <th>Options</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($items as $item)
                        <tr class="clickable-row @if(!$item->active) text-muted @endif" data-href="{{ route('posts.edit', $item->id) }}" title="Click to Edit: {{$item->title}}" data-toggle="tooltip">
                            <td class="text-center">@if($item->active) <i class="fa fa-circle text-success"></i> @else <i class="fa fa-ban text-danger"></i> @endif</td>
                            <td>{{ $item->title }}</td>
                            <td>{{ $item->path }}</td>
                            <td><span data-toggle="tooltip" title="{{strip_tags($item->summary)}}">{{ str_limit(strip_tags($item->summary),20) }}</span></td>
                            <td>{{ $item->created_at->format('M d Y') }}</td>
                            <td>{{ $item->author }}</td>
                            <?php /* ?><td> @if($item->ads) <a href="{{route('advertisements.edit', $item->ads->first()['id'])}}">{{$item->ads->first()['sponsor']['name']}}</a> @endif</td><?php */ ?>
                            <td>
                              <a href="{{ route('posts.edit', $item->id) }}" title="Edit" data-toggle="tooltip" ><i class="fa fa-pencil-square fa-2x"></i></a>
                                @if($item->video)
                                    <a href="javascript:void(0);" title="Preview" data-toggle="tooltip"
                                       data-videoid="{{ $item->video->brightcove_id }}" class="video_preview"><i class="fa fa-play-circle
                				fa-2x text-info"></i></a>
                                @endif
                              <a href="{{ URL::to('/posts/remove/'.$item->id) }}" title="Delete" data-toggle="tooltip" onclick="return confirm('Are you sure you want to remove this?');"><i class="fa fa-times fa-2x text-danger"></i></a>
                            </td>
                        </tr>
                      @endforeach
                    </tbody>
                </table>
   </div></div></div></div>
    </section><!-- /.content -->
  </div><!-- /.content-wrapper -->
@include('shows.partials.videomodal')
@stop
@section('footer_js')
    <script src="//sadmin.brightcove.com/js/BrightcoveExperiences.js"></script>
    <script src="{{ asset('js/brightcove/brightcove-player.js') }}"></script>
@stop

