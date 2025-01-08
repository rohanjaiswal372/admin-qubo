<table class="table table-responsive table-striped table-condensed">
@if(count($item->images))
    <th>Image</th>
    <th>Info</th>
    @foreach( $item->images as $image )
        <tr>
            <td><span  @if($item->color) style="background-color:{{$item->color}}" @endif ><a href="{{image($image->url)}}" class="tippy" title="Left click to download image" download><img src="{{image($image->url) }}" class="img-responsive post-image {{$image->type_id}}" @if($item->color) style="background-color:{{$item->color}}; padding:10px;" @endif data-clipboard-action="copy" data-clipboard-text="{{image($image->url)}}"/></a></span>
                @if($image->type_id == 'general')
                <input class="form-control" type="text" value="{{image($image->url)}}"/>
                    @endif
                </td>
            <td>
                <table class="table-responsive table-condensed table-striped">
                    <tr>
                        <td>TYPE:</td>
                        <td><span class="label label-success">{{strtoupper($image->type_id)}}
                                @if(get_class($item) == 'App\Post')
                                @if($image->type_id == "default") (VIDEO STILL)
                                @elseif($image->type_id == "thumbnail") (POD IMAGE) @endif @endif</span></td>
                    </tr>
                    <tr>
                        <td>EXT:</td>
                        <td><span class="label label-warning">{{pathinfo($image->url, PATHINFO_EXTENSION)}}</span></td>
                    </tr>
                        <td>CREATED:</td>
                        <td><span class="label label-default"><i class="fa fa-calendar"></i> {{Carbon::parse($image->created_at)->format('m.d g:ia')}}</span></td>
                    </tr>
                    <tr>
                        <td>UPDATED:</td>
                        <td><span class="label label-default"><i class="fa fa-calendar"></i> {{Carbon::parse($image->updated_at)->format('m.d g:ia')}}</span></td>
                    </tr>
                    <tr>
                        <td>WIDTH:</td>
                        <td><span class="label label-info"><i class="fa fa-arrows-h"></i> {{image_size($image->url)['width']}}
                                px</span></td>
                    </tr>
                    <tr>
                        <td>HEIGHT:</td>
                        <td><span class="label label-info"><i class="fa fa-arrows-v"></i> {{image_size($image->url)['height']}}
                                px</span></td>
                    </tr>
                    <tr>
                        <td></td>
                        <td><a href="{{ url('/image/remove/'.$image->id) }}"
                               onclick="return confirm('Are you sure? This will remove the image from our system.');"><i
                                        class="fa fa-trash text-danger fa-2x"></i></a></td>
                    </tr>

                </table>

        </tr>
    @endforeach
@endif
</table>