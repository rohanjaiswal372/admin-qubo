<div class="box box-primary pad">
    <div class="box-header">
        <h3 class="box-title"><i class="fa fa-video-camera"></i> Video:</h3>
        <div class="box-tools pull-right">
            @if(!is_null($item->video))
                <a href="{{ url('videos/edit/'.$item->video->id) }}"
                   title="Edit Video details"
                   data-toggle="tooltip"
                   class="btn btn-box-tool"
                ><i class="fa fa-pencil-square fa-2x text-info"></i></a>
                <a href="{{ url('videos/delete/'.$item->video->id) }}"
                   title="Remove"
                   data-toggle="tooltip"
                   class="btn btn-box-tool"
                   onclick="return confirm('Are you Sure you want to delete this video - This will remove it from  Brightcove and all other locations')"
                ><i class="fa fa-trash fa-2x text-danger"></i></a>
            @endif
        </div>
    </div>
    <div class="box-body text-center">
        @if($item->video && $item->video->brightcove_id)
            <a href="#"
               data-videoid="{{ $item->video->brightcove_id }}"
               class="video_preview"> <img
                        class="img-responsive"
                        src="{{ ($item->video->still())? $item->video->still() : placeholder(480 ,360)  }}"/>
                <em class="fa fa-play-circle-o play-btn fa-4x"></em></a>
            <div class="form-group text-left">
                {!! Form::label('brightcove_id', 'Brightcove Video ID: ') !!}
                {!! Form::text('brightcove_id', (!is_null($item->video->brightcove_id))? $item->video->brightcove_id : "", ['class' => 'form-control', 'placeholder' => '44122312342321']) !!}
            </div>
        @else
            <a class="video_preview_add"
               title="Enter a Brightcove Video ID below and save to add a video  to this post"
               data-toggle="tooltip"> <em class="fa fa-plus-circle text-success fa-4x"></em> </a>
            <a href="#" title="Upload new Video"
               data-toggle="tooltip" id="upload_new_video"> <em class="fa fa-cloud-upload fa-4x"></em></a>
            <div class="form-group text-left">
                {!! Form::label('brightcove_id', 'Brightcove Video ID: ') !!}
                {!! Form::text('brightcove_id', NULL, ['class' => 'form-control', 'placeholder' => '44122312342321']) !!}
            </div>
        @endif
    </div>
</div>
