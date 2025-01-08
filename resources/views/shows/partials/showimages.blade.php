<div class="box-body pad">
    <div class="row">
        @foreach($show->images as $image)
            <div class="col-lg-3">
                <h3>{{ucfirst($image->type_id)}}</h3>
                <a href="{{image($image->url)}}" class="colorbox"><img src="{{image($image->url)}}"
                                                                       class="img img-responsive"/></a>
            </div>
        @endforeach
    </div>
</div>