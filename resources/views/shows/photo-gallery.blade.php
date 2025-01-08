@extends("app")
@section("content")
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="pull-left">
                <h3><strong>Edit Show Images:</strong> {{$show->name}} @if(!$show->active):
                    <span class="text-danger"> Inactive <i class="fa fa-ban"></i></span>
                    @endif
                    @if($show->logo)
                        @if(!strstr($show->logo->url, ":"))
                            <img class="img img-thumbnail" style="background-color:{{$show->color}}; max-width:200px"
                                 src="{{image($show->logo->url) }}"/>
                        @else
                            <img class="img img-thumbnail" style="background-color:{{$show->color}}; max-width:200px"
                                 src="{{URL::to('proxy.php?type=image&url='.$show->logo->url) }}"/>
                        @endif
                    @endif
                </h3>
            </div>
            <div class="pull-right">
                <div class="btn-group">
                    <a href="{{ URL::previous() }}" class="btn btn-primary"><i class="fa fa-undo"></i> Back</a>
                    <a href="{{ route('shows.edit', $show->id) }}" class="btn btn-info"><i class="fa fa-pencil-square-o"></i>Edit Show</a>
                    <a href="{{ URL::to("shows/media/create/".$show->id) }}" class="btn btn-success"><i
                                class="fa fa-picture-o"></i> Add Image</a>
                    <a href="{{ URL::to("shows/casts/create/".$show->id) }}"
                       class="btn btn-success"><i class="fa fa-user"></i> Add Cast</a>
                    <a href="{{ URL::to('/shows/remove/'.$show->id) }}"
                       title="Delete"
                       data-toggle="tooltip"
                       class="btn btn-danger"
                       onclick="return confirm('Are you sure you want to remove this show?');"
                    ><i class="fa fa-times"></i> Delete Show</a>
                </div>
            </div>
            <div class="clearfix"></div>
        </section>
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary pad">
                    <div class="box-header with-border">
                        <h3 class="box-title">Show Info:</h3>
                        <div class="box-tools pull-right">
                            Show: {!! Form::select('show_id', ["Select One"] + $show_id_selector , $show->id , ['class' => 'form-control select2',
																												 'autocomplete'=>'off',
																												 'onchange'=>"window.location='".URL::to("shows/photos/")."/'+this.value;"
																]) !!}
                        </div>
                    </div>

                    <div class="box-body">
                        <div class="row pad">
                            <div class="col-md-12">
                                @foreach($image_types as $type)
                                    <h3><span class="label label-default">{{ $type->name }}</span></h3>
                                    <table class="table no-margin table-striped table-hover table-bordered">
                                        <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Image</th>
                                            <th>Info</th>
                                            <th>Options</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($show->images->sortByDesc("id") as $image)
                                            @if($image->type_id == $type->type_id)
                                                <tr>
                                                    <td class="col-md-1">
                                                        {{ $image->id}}
                                                    </td>
                                                    <td class="col-md-7">
                                                        <a href="{{ image($image->url) }}" class="colorbox" rel="images" title="{{$image->type_id}}">
                                                            <img src="{{ image($image->url) }}"
                                                                 class="img-responsive"/> </a>
                                                    </td>
                                                    <td class="col-md-3">
                                                        <table>
                                                            <tr>
                                                                <td>TYPE:</td>
                                                                <td><span class="label label-default">{{$image->type_id}}</span></td>
                                                            </tr>
                                                            <tr>
                                                                <td>CREATED:</td>
                                                                <td><span class="label label-default"><i class="fa fa-calendar"></i> {{Carbon::parse($image->created_at)->format('m.d g:ia')}}</span></td>
                                                            </tr>
                                                            <tr>
                                                                <td>UPDATED:</td>
                                                                <td><span class="label label-default"><i class="fa fa-calendar"></i> {{Carbon::parse($image->updated_at)->format('m.d g:ia')}}</span></td>
                                                            </tr>
                                                            <tr>
                                                                <td>WIDTH:</td>
                                                                <td><span class="label label-default"><i class="fa fa-arrows-h"></i> {{image_size($image->url)['width']}}
                                                                        px</span></td>
                                                            </tr>
                                                            <tr>
                                                                <td>HEIGHT:</td>
                                                                <td><span class="label label-default"><i class="fa fa-arrows-v"></i> {{image_size($image->url)['height']}}
                                                                        px</span></td>
                                                            </tr>
                                                        </table>
                                                    </td>
                                                    <td class="col-md-1">
                                                        <a
                                                                href="{{ URL::to("shows/photos/delete/".$image->id) }}">
                                                            <li class="fa fa-trash fa-2x text-danger"></li>
                                                        </a>
                                                    </td>
                                                </tr>
                                            @endif
                                        @endforeach
                                        </tbody>
                                    </table>
                                @endforeach
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>

        </section><!-- /.content -->
    </div><!-- /.content-wrapper -->

@stop
