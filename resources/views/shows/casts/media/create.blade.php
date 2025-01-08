@extends("app")

@section("content")
        <!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>Upload Media </h1>
    </section>

    <!-- Main content -->
    <section class="content">

        <!-- form start -->
        {!! Form::open(array("role"=>"form","files"=>TRUE,"id"=>"upload-form")) !!}

        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary pad">
                    <div class="box-header with-border">
                        <h3 class="box-title">Cast Member images</h3>
                        <div class="box-tools pull-right">
                        </div>
                    </div>

                    <div class="box-body">

                        <div class="form-group">
                            <div class="row">
                                <div class="form-group col-lg-3">
                                    {!! Form::label('upload_object_id', 'Cast: ') !!}
                                    {!! Form::select('upload_object_id', ["Select One"] + $object_id_selector , $object_id , ['class' => 'form-control upload-param', 'autocomplete'=>"off"] ) !!}
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="row">
                                <div class="form-group col-lg-3">
                                    {!! Form::label('upload_media_type', 'Type: ') !!}
                                    {!! Form::select('upload_media_type', ["Select One"] + $media_type_selector , "" , ['class' => 'form-control upload-param', 'autocomplete'=>"off"] ) !!}
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            @include('templates.uploader')
                        </div>
                        <div id="cast_images">
                            @if($cast->images)
                                @include('shows.partials.images', ['item' => $cast])
                            @endif
                        </div>
                    </div><!-- /.box-body -->
                </div>
            </div>
        </div>
    </section><!-- /.content -->
</div><!-- /.content-wrapper -->
@stop
@section("footer_js")
    <script src="{{ asset("/js/fineuploader.js") }}" type="text/javascript"></script>

    <script>
        $(document).ready(function () {

            $('#upload_object_id').on('change', function () {
                getCastImages();
            });
            $('.remove').on('click',function(){
               getRemoveImages($(this));
            });
        });

        function upload_complete(){
            getCastImages();
        }

        function getRemoveImages($item){
            var imgid = $($item).data('imgid');
            var itemid = $($item).data('itemid');
            $.get("/shows/casts/remove-images/"+imgid+"/"+itemid, function(response){
                $('#cast_images').html(response);
                $('.colorbox a').colorbox();
            });

        }

        function getCastImages(){
            var castname = $("#upload_object_id option:selected").text();
            var castid = $("#upload_object_id option:selected").val();
            $("#cast_name").text(castname);
            $.get("/shows/casts/cast-images/"+castid, function(response){
                $('#cast_images').html(response);
                $('.colorbox a').colorbox();
            });
        }
    </script>
@endsection

