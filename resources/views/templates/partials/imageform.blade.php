<div class="box box-primary image-box pad" id="image-box">
    <div class="box-header">
        <h3 class="box-title"><i class="fa fa-file-image-o"></i> Image:</h3>
    </div>
    <div class="box-body">
        @if(isset($item))
            @if($item->image && $item->image->url)
                @php $image_size['width'] = image_size($item->image->url)['width'];
                $image_size['height'] = image_size($item->image->url)['height'];
                @endphp
            @endif
            <table class="table no-margin">
                <thead>
                <tr>
                    <th>Image</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody>
                @if($item->image)
                    <tr>
                        <td>
                            <a href="{{ image($item->image->url) }}"
                               target="_blank" class="colorbox"> <img class='img img-thumbnail'
                                                                      src="{{ $item->image->url }}"
                                                                      data-adid="{{$item->id}}"
                                                                      @if($sponsor)data-imgid="{{$sponsor->id}}
                                                                              data-type=" Sponsor"
                                @endif
                                /> </a>
                        </td>
                        <td>
                            <a href="{{ url('/image/remove/'.$item->image->id)}}"
                               data-toggle="tooltip"
                               title="Remove"
                               onclick="return confirm('Are you sure? This will remove the image from our system.');"><i
                                        class="fa fa-trash fa-2x text-danger"></i></a>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <small><strong>WIDTH:</strong><span> {{(isset($image_size['width']))? $image_size['width'] : "N/A"}}
                                    px</span></small> &nbsp; | &nbsp;
                            <small><strong>HEIGHT:</strong><span> {{(isset($image_size['height']))? $image_size['height'] : "N/A"}}
                                    px</span></small>
                        </td>
                    </tr>
                @endif
                </tbody>
            </table>
            <input type="hidden" name="ad[id]" data-adID="{{$item->id}}" value="{{$item->id}}"/>
        @endif
    </div>
    <div class="box-footer">
        <div class="form-group-sm">
            <label class="btn btn-primary btn-sm btn-block btn-file tippy"
                   title="Upload new image"
            ><span>
                                   <i class="fa fa-upload"></i> Upload Image</span><input type="file"
                                                                                          id="image"
                                                                                          name="image" class="hide">
            </label>
        </div>
        @if($item->image)
            <div class="form-group-sm tippy"
                 title="This is the maximum width the logo will be on the page.  The absolute maximum is 250px.">
                <label class="text-center"> Max Width:
                    <span id="widthValue">{{((int)$image_size['width'] < (int)$item->width)? $image_size['width'] : $item->width }}</span>px
                </label>
                <input id="widthSlider"
                       type="range"
                       min="50"
                       max="{{((int)$image_size['width'] < 250)? $image_size['width'] : 250}}"
                       step="1"
                       value="{{((int)$image_size['width'] < (int)$item->width)? $image_size['width'] : $item->width }}"
                       name="ad[width]"/>
            </div>
        @endif
    </div>
</div>