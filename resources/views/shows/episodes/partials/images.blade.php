<div class="box pad" id="episode_images">
    <div class="box-header">
        <h3 class="box-title">Episode Images:</h3>
        <div class="box-tools pull-right">
            <a title="Add Episode Images"
               data-toggle="tooltip" class="btn btn-box-tool add_episode_photo"> <i
                        class="fa fa-plus-circle fa-2x text-success"></i></a>
        </div>
    </div>
    <div class="box-body clearfix">
        <div class="row">
            @if($episode->images)

                @foreach($episode->images as $image)
                    <div class="col-md-3 col-xs-12">
                        <img class="img-thumbnail" src="{{ image($image->url) }}"/> <a class="btn btn-danger remove-btn"
                                                                                       href="{{ URL::to("shows/photos/delete/".$image->id) }}"
                                                                                       data-imageid="{{$image->id}}"> <i
                                    class="fa fa-trash "></i></a>
                    </div>

                @endforeach
            @endif
        </div>
    </div>
</div>