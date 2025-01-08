<div class="btn-group pull-right clearfix">
    <a class="btn btn-primary btn-xs" href="{{ route('shows.edit', $show->id) }}" title="Edit" data-toggle="tooltip"><i class="fa fa-pencil-square"></i></a>
    <a class="btn btn-primary btn-xs" href="{{ URL::to('shows/media/create/'.$show->id) }}" title="Photo Upload" data-toggle="tooltip"><i class="fa fa-cloud-upload"></i></a>
    <a class="btn btn-primary btn-xs" href="{{ URL::to('shows/photos/'.$show->id) }}" title="Photos" data-toggle="tooltip"><i class="fa fa-file-image-o"></i></a>
    <a class="btn btn-primary btn-xs" href="{{ URL::to('shows/episodic-photos/'.$show->id) }}" title="Episodic Photo Gallery" data-toggle="tooltip"><i class="fa fa-picture-o"></i></a>
    <a class="btn btn-primary btn-xs" href="{{ URL::to('shows/casts/'.$show->id) }}" title="Casts" data-toggle="tooltip"><i class="fa fa-users"></i></a>
    <a class="btn btn-primary btn-xs" href="{{ URL::to('shows/videos/'.$show->id) }}" title="Videos" data-toggle="tooltip"><i class="fa fa-video-camera"></i></a>
    <a class="btn btn-primary btn-xs" href="{{ URL::to('shows/episodes/'.$show->id) }}" title="Episodes" data-toggle="tooltip"><i class="fa fa-film"></i></a>
</div>
