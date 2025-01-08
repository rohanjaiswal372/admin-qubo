@extends("app")
@section("content")
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="pull-left">
                <h3><strong>Upload Media - Show:</strong>
                    <span id="show_name">{{$show->name}}</span> @if(!$show->active):
                    <span class="text-danger"> Inactive <i class="fa fa-ban"></i></span>
                    @endif
                    @if($show->logo)
                            <img class="img img-thumbnail show-logo-sm" @if($show->color)style="background-color:{{$show->color}}@endif"
                                 src="{{image($show->logo->url) }}"/>
                    @endif
                </h3>
            </div>
            <div class="pull-right">
                <div class="btn-group">
                    <a href="{{ URL::previous() }}" class="btn btn-primary"><i class="fa fa-undo"></i> Back</a>
                    <a href="{{ route('shows.create') }}" class="btn btn-success"><i class="fa fa-plus"></i>&nbsp;New
                        Show</a> <a href="{{ URL::to("shows/casts/create/".$show->id) }}"
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
        <!-- Main content -->
        <section class="content">
            <!-- form start -->
            {!! Form::open(array("role"=>"form","files"=>TRUE,"id"=>"upload-form")) !!}
            <div class="row">
                <div class="col-md-12">
                    <div class="box box-primary pad">
                        <div class="box-header with-border">
                            <h3 class="box-title">Show images</h3>
                            <div class="box-tools pull-right">
                            </div>
                        </div>

                        <div class="box-body">
                            <div class="form-group">
                                <div class="row">
                                    <div class="form-group col-lg-3">
                                        {!! Form::label('upload_object_id', 'Show: ') !!}
                                        {!! Form::select('upload_object_id', ["Select One"] + $object_id_selector , $show->id , ['class' => 'form-control upload-param select2', 'autocomplete'=>"off"] ) !!}
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class="form-group col-lg-3">
                                        {!! Form::label('upload_media_type', 'Type: ') !!}
                                        {!! Form::select('upload_media_type', ["Select One"] + $media_type_selector , "" , ['class' => 'form-control upload-param select2', 'autocomplete'=>"off"] ) !!}
                                    </div>
                                </div>
                            </div>
                            @include('templates.uploader')
                        </div>
                        <div id="show_images">
                            @if($show->images)
                                @include('shows.partials.images',['item' => $show])
                            @endif
                        </div>
                    </div>
                </div>
            </div>

        </section><!-- /.content -->
    </div><!-- /.content-wrapper -->
@endsection
@section("footer_js")
    <script src="{{ asset("/js/fineuploader.js") }}" type="text/javascript"></script>

    <script>
        $(document).ready(function () {
            loadActions();
        });

        function loadActions(){
            $('#upload_object_id').on('change', function () {
                getShowImages();
            });
            $('.remove').on('click',function(){
                getRemoveImages($(this));
            });
            $('.colorbox a').colorbox();
        }
        function upload_complete() {
            getShowImages();
            loadActions();
        }
        function getRemoveImages(item){
            var imgid = $(item).data('imgid');
            var itemid = $(item).data('itemid');
            $.get("/shows/remove-images/"+imgid+"/"+itemid, function(response){
                $('#show_images').html(response);
                loadActions();
            });

        }
        function getShowImages() {
            var showname = $("#upload_object_id option:selected").text();
            var showid = $("#upload_object_id option:selected").val();
            $("#show_name").text(showname);
            $.get("/shows/show-images/" + showid, function (response) {
                $('#show_images').html(response);
                loadActions();
            });
        }
    </script>
@endsection