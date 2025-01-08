    <div class="col-md-2 text-center" style="min-height:150px; background-color:{{$color->color}}">
            <select name="show_colors[{{$color->id}}][type]" class="select2">
                @foreach($show->color_types as $type)
                    <option  value="{{$type->id}}" @if($color->type->id == $type->id) {{ "selected" }} @endif>{{$type->description}}</option>
                @endforeach
            </select>
            <input type="color" name="show_colors[{{$color->id}}][color]" class="show_colors form-control colorpicker" value="{{$color->color}}"/>
        </div>
