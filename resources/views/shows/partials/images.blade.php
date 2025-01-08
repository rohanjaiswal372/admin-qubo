<div class="box-body pad" id="image-box">
    <div class="row">
        @foreach($item->images->where('type_id','!=', 'gallery') as $image)
                <div class="col-md-3">
                    <h4 class="box-title">{{ucfirst($image->type_id)}}<a title="Delete" data-itemid="{{$item->id}}" data-imgid="{{$image->id}}"
                                                                         data-toggle="tooltip"
                                                                         class="pull-right remove"
                                                                         onclick="return confirm('Are you sure you want to remove this?');"><i class="fa text-danger fa-trash-o"></i></a></h4>
                    <a href="{{image($image->url)}}" class="colorbox" rel="images"><img src="{{image($image->url)}}"
                                                                                        class="img img-responsive"/></a>
                </div>

        @endforeach
            @foreach($item->images->where('type_id','=', 'gallery')->take(50) as $image)
                <div class="col-md-2">
                    <h4 class="box-title">{{ucfirst($image->type_id)}}<a title="Delete" data-itemid="{{$item->id}}" data-imgid="{{$image->id}}"
                                                                         data-toggle="tooltip"
                                                                         class="pull-right remove"
                                                                         onclick="return confirm('Are you sure you want to remove this?');"><i class="fa text-danger fa-trash-o"></i></a></h4>
                    <a href="{{image($image->url)}}" class="colorbox" rel="images"><img src="{{image($image->url)}}"
                                                                                        class="img img-responsive"/></a>
                </div>

            @endforeach
    </div>
</div>