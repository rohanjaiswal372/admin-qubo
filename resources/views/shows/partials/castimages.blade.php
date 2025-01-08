<div class="box-body pad">
    <div class="row">
        @foreach($item->images as $image)
            <div class="col-md-3">
                <h3>{{ucfirst($image->type_id)}}<a title="Delete" data-imgid="{{$image->id}}"
                                                        data-toggle="tooltip"
                                                        class="btn btn-danger pull-right remove"
                                                        onclick="return confirm('Are you sure you want to remove this?');"><i class="fa fa-trash-o"></i></a></h3>
                <a href="{{image($image->url)}}" class="colorbox"><img src="{{image($image->url)}}"
                                                                       class="img img-responsive"/></a>
            </div>
        @endforeach
    </div>
</div>