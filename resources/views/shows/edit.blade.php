@extends("app")
@section('header')
    <style>
        .colorpicker {
            background: rgba(255, 255, 255, 0.70);
        }
    </style>
@stop
@section("content")
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="pull-left">
                <h3><strong>Edit Show:</strong> {{$show->name}} @if(!$show->active):
                    <span class="text-danger"> Inactive <i class="fa fa-ban"></i></span>
                    @endif
                    @if($show->logo)
                        @if(!strstr($show->logo->url, ":"))
                            <img class="img img-thumbnail show-logo-sm"
                                 @if($show->color)style="background-color:{{$show->color}}@endif"
                                 src="{{image($show->logo->url) }}"/>
                        @else
                            <img class="img img-thumbnail show-logo-sm" style="background-color:{{$show->color}}"
                                 src="{{URL::to('proxy.php?type=image&url='.$show->logo->url) }}"/>
                        @endif
                    @endif
                </h3>
            </div>
            <div class="pull-right">
                {!! Form::select('objects', $shows->pluck('name','id'), $show->id, ['class' => 'select2 shows_selector form-control']) !!}
                <div class="btn-group">
                    <a href="{{ route('shows.index') }}" class="btn btn-info"><i class="fa fa-undo"></i> Back</a>
                    <a href="{{ route('shows.create') }}" class="btn btn-success"><i class="fa fa-plus"></i>&nbsp;New
                        Show</a> <a href="{{ URL::to("shows/media/create/".$show->id) }}" class="btn btn-success"><i
                                class="fa fa-picture-o"></i> Add Image</a>
                    <a href="{{ URL::to("shows/casts/create/".$show->id) }}"
                       class="btn btn-success"><i class="fa fa-user"></i> Add Cast</a>
                    <a href="{{  URL::to("shows/delete/".$show->id)}}"
                       title="Delete"
                       data-toggle="tooltip"
                       class="btn btn-danger"
                       onclick="return confirm('Are you sure you want to remove this show?');"
                    ><i class="fa fa-times"></i> Delete Show</a>
                </div>
            </div>
            <div class="clearfix"></div>
        </section>
        <!-- Main content -->
        <section class="content">
            {!! Form::model($show, array('route' => array('shows.update', $show->id), 'method' => 'PUT')) !!}
            <div class="row-fluid">
                <div class="col-md-6">
                    <div class="box box-primary pad">
                        <div class="box-header with-border">
                            <h3 class="box-title">Show Info:</h3>
                            <div class="box-tools pull-right">
                                <button type="button" class="btn btn-box-tool" data-widget="collapse">
                                    <i class="fa fa-minus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="box-body">
                            <div class="form-group col-md-6">
                                {!! Form::label('name', 'Name: ') !!}
                                {!! Form::text('name', Input::old('name'), ['class' => 'form-control', 'placeholder' => '']) !!}
                            </div>
                            <div class="form-group col-md-6">
                                {!! Form::label('short_name', 'Short Name: ') !!}
                                {!! Form::text('short_name', Input::old('short_name'), ['class' => 'form-control', 'placeholder' => '']) !!}
                            </div>
                            <div class="form-group col-md-3">
                                {!! Form::label('slug', 'Path: ') !!}
                                {!! Form::text('slug', Input::old('slug'), ['class' => 'form-control', 'placeholder' => '']) !!}
                            </div>
                            <div class="form-group col-md-3">
                                {!! Form::label('code', 'Code (3-Letter): ') !!}
                                {!! Form::text('code', Input::old('code'), ['class' => 'form-control', 'maxlength' => '4']) !!}
                            </div>
                            <div class="form-group col-md-3">
                                {!! Form::label('active', 'Active: ') !!}
                                {!! Form::checkbox('active', Input::old('active'), (int)$show->active) !!}
                            </div>
                            <div class="form-group col-md-12">
                                {!! Form::label('broadview_handle', 'Broadview Handle: ') !!}
                                {!! Form::select('broadview_handle', [""=>"Select One"] + \PromoTool::getBroadviewHandles() , Input::old('broadview_handle'), ['class' => 'form-control', 'placeholder' => '']) !!}
                            </div>
                            <div class="form-group col-md-3">
                                {!! Form::label('new', 'New: ') !!}
                                {!! Form::checkbox('new', Input::old('new'), (int)$show->holiday) !!}
                            </div>
                            <div class="form-group col-md-3">
                                {!! Form::label('holiday', 'Active Holiday: ') !!}
                                {!! Form::checkbox('holiday', Input::old('holiday'), (int)$show->holiday) !!}
                            </div>
                            <div class="form-group col-md-12">
                                {!! Form::label('description', 'Description: ') !!}
                                {!! Form::textarea('description', Input::old('description'), ['class' => 'form-control ckeditor', 'placeholder' => '']) !!}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="box box-primary pad">
                        <div class="box-header with-border">
                            <h3 class="box-title">Show Colors:</h3>
                            <div class="box-tools pull-right">
                                <button type="button" class="btn btn-box-tool" data-widget="collapse">
                                    <i class="fa fa-minus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="box-body">
                            <div class="form-group col-md-12">
                                @if($show->colors)
                                    @foreach($show->colors as $color)
                                        <div class="col-md-3 text-center"
                                             style="min-height:100px; background-color:{{$color->code}}">
                                            <div class="box box-default">
                                                <div class="box-body">
                                                    <h5>{{$color->type->description}}</h5>
                                                    <input type="hidden"
                                                           name="colors[{{$color->type->id}}][id]"
                                                           value="{{$color->id}}"/> <input type="hidden"
                                                                                           name="colors[{{$color->type->id}}][type_id]"
                                                                                           value="{{$color->type_id}}"/>
                                                    <input type="text"
                                                           name="colors[{{$color->type->id}}][code]"
                                                           class="colors form-control colorpicker"
                                                           value="{{$color->code}}"/>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="box box-primary pad">
                        <div class="box-header with-border">
                            <h3 class="box-title">Show Images:</h3>
                            <div class="box-tools pull-right">
                                <button type="button" class="btn btn-box-tool" data-widget="collapse">
                                    <i class="fa fa-minus"></i>
                                </button>
                                <a href="{{ URL::to('shows/photos/',$show->id) }}"
                                   title="View all Images"
                                   data-toggle="tooltip"><i class="fa fa-list"></i></a>
                                <a class="btn btn-box-tool"
                                   href="{{ URL::to('shows/media/create', $show->id) }}"
                                   title="Create New"
                                   data-toggle="tooltip"><i class="fa text-success fa-plus-square fa-2x"></i></a>
                            </div>
                        </div>
                        <div class="box-body" id="images-box">
                            @if($show->images)
                                @include('shows.partials.images', ['item' => $show])
                            @endif
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="box box-primary pad">
                        <div class="box-header with-border">
                            <h3 class="box-title">Show Episodes:</h3>
                            <div class="box-tools pull-right">
                                <button type="button" class="btn btn-box-tool" data-widget="collapse">
                                    <i class="fa fa-minus"></i>
                                </button>
                                <a href="{{ URL::to('/shows/episodes',$show->id) }}"
                                   title="View all Episodes"
                                   data-toggle="tooltip"><i class="fa fa-list"></i></a>
                                <a class="btn btn-box-tool" href="{{ URL::to('/shows/episodes/new/'.$show->id) }}"><i
                                            class="fa text-success fa-plus-square fa-2x"></i></a>
                            </div>
                        </div>
                        <div class="box-body">
                            @if($show->episodes)
                                @include('shows.partials.showepisodes')
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="box box-primary pad">
                    <div class="box-header with-border">
                        <h3 class="box-title">Show Cast:</h3>
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse">
                                <i class="fa fa-minus"></i>
                            </button>
                            <a href="{{ URL::to('shows/casts/',$show->id) }}"
                               title="View all Cast"
                               data-toggle="tooltip"><i class="fa fa-list"></i></a>
                            <a class="btn btn-box-tool"
                               href="{{ URL::to('shows/casts/create', $show->id) }}"
                               title="Create New"
                               data-toggle="tooltip"><i class="fa text-success fa-plus-square fa-2x"></i></a>
                        </div>
                    </div>
                    <div class="box-body" id="show-cast">
                        @if($show->images)
                            @include('shows.partials.showcast')
                        @endif
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="box box-primary">
                    <div class="box-header">
                        <h3 class="box-title">Show Preview:</h3>
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse">
                                <i class="fa fa-minus"></i>
                            </button>
                            @if($show->preview)
                                <a href="#" title="Preview" data-toggle="tooltip"
                                   data-videoid="{{ $show->preview->brightcove_id }}"
                                   class="video_preview btn btn-box-tool"><i
                                            class="fa fa-play-circle fa-2x"></i></a>
                                <a class="btn btn-box-tool"
                                   href="{{ route('videos.edit', $show->preview->id) }}"
                                   title="Edit"
                                   data-toggle="tooltip"><i class="fa fa-pencil-square fa-2x"></i></a>
                                <a href="{{ URL::to('shows/videos/delete/'.$show->preview->id) }}"
                                   title="Remove"
                                   data-toggle="tooltip"
                                   class="btn btn-box-tool"
                                   onclick="return confirm('Are you Sure you want to delete this episode video preview - This will remove it from the Brightcove site and all other locations')"
                                ><i class="fa fa-trash fa-2x text-danger"></i></a>
                            @endif
                        </div>
                    </div>
                    <div class="box-body">
                        @include('shows.partials.showvideo', array('show' => $show))
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="box box-primary pad">
                    <div class="box-header with-border">
                        <h3 class="box-title">Carousel Info:</h3>
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse">
                                <i class="fa fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="box-body">
                        <div class="form-group col-md-6">
                            {!! Form::label('headline', 'Carousel headline: ') !!}
                            {!! Form::text('headline', Input::old('headline'), ['class' => 'form-control', 'placeholder' => '']) !!}
                        </div>
                        <div class="form-group col-md-6">
                            {!! Form::label('subtitle', 'Subtitle: ') !!}
                            {!! Form::text('subtitle', Input::old('subtitle'), ['class' => 'form-control', 'placeholder' => '']) !!}
                        </div>
                        <div class="form-group col-md-3">
                            {!! Form::label('scope', 'Scope: ') !!}
                            {!! Form::text('scope', Input::old('scope'), ['class' => 'form-control', 'placeholder' => '']) !!}
                        </div>
                        <div class="form-group col-md-3">
                            {!! Form::label('sort_order', 'Sort Order: ') !!}
                            {!! Form::text('sort_order', Input::old('sort_order'), ['class' => 'form-control', 'placeholder' => '']) !!}
                        </div>
                        <div class="form-group col-md-3">
                            {!! Form::label('position', 'Logo Position: ') !!}
                            {!! Form::select('position',  [ $show->position => $show->position,'left'=> 'left', 'left-tall'=>'left-tall','right' => 'right','right-tall'=>'right-tall'], ['class' => 'form-control select2']) !!}
                        </div>
                        <div class="form-group col-md-3">
                            {!! Form::label('color', 'Menu color ') !!}
                            {!! Form::text('color', Input::old('color'), ['class' => 'form-control colorpicker', 'placeholder' => '#ffffff']) !!}
                        </div>
                    </div>
                </div>
            </div>
            @include('templates.partials.savebar')
        </section><!-- /.content -->
    </div><!-- /.content-wrapper -->
    @include('shows.partials.videomodal')
@stop
@section('footer_js')
    <script src="{{ asset('js/brightcove/videojs-player.js') }}"></script>
    <script>
        $(document).ready(function () {
            load_actions();

        });

        function load_actions() {
            $('.shows_selector').change(function (e) {
                var showid = $(this).val();
                window.location.href = "/shows/" + showid + "/edit";
            });
            $('.colors').on('changeColor', function (e) {
                $(this).parent().css("backgroundColor", e.color.toHex());
            });
            $('.colors').keyup(function (e) {
                var txt = $(this).val();
                if (!/#/.test(txt)) {
                    $(this).val('#' + txt);
                }
            });
            $('#upload_object_id').on('change', function (e) {
                getImages();
            });
            $('.remove').on('click', function (e) {
                e.preventDefault();
                getRemoveImages($(this));
            });
            $('.colorbox a').colorbox();

        }

        function upload_complete() {
            getImages();
        }

        function getRemoveImages(item) {
            var imgid = $(item).data('imgid');
            var itemid = $(item).data('itemid');
            $.get("/shows/remove-images/" + imgid + "/" + itemid, function (response) {
                $('#images-box').html(response);
                load_actions();
            });

        }

        function getImages() {
            var name = $("#upload_object_id option:selected").text();
            var id = $("#upload_object_id option:selected").val();
            $("#show_name").text(name);
            $.get("/shows/show-images/" + id, function (response) {
                $('#images-box').html(response);
                load_actions();
            });
        }
    </script>
@stop