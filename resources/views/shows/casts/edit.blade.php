@extends("app")
@section("content")
        <!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="pull-left">
            <h3>Editing {{$cast->name}}</h3>
        </div>
        <div class="pull-right">
            <div class="btn-group">
                <a href="{{ URL::to("shows/casts/".$cast->show->id) }}" class="btn btn-info"><i class="fa fa-users"></i> All Cast</a>
                <a href="{{ URL::previous() }}" class="btn btn-warning"><i class="fa fa-undo"></i> Back</a>
            </div>
        </div>
        <div class="clearfix"></div>
    </section>

    <!-- Main content -->
    <section class="content">
        {{ HTML::ul($errors->all()) }}

                <!-- Main content -->
        <section class="content">

            {!! Form::model($cast, array('route' => array('casts.update', $cast->id), 'method' => 'PUT')) !!}
            <div class="row">
                <div class="col-md-7">
                    <div class="box box-primary pad">
                        <div class="box-body">

                        {!! Form::hidden('show_id', $cast->show->id) !!}

                            <div class="form-group">
                                {!! Form::label('active', 'Active: ') !!}
                                {!! Form::checkbox('active', Input::old('active'), ['class' => 'form-control']) !!}
                            </div>
                            <div class="form-group">
                                {!! Form::label('name', 'Name: ') !!}
                                {!! Form::text('name', Input::old('name'), ['class' => 'form-control', 'placeholder' => 'Cast Name']) !!}
                            </div>

                            <div class="form-group">
                                {!! Form::label('real_name', 'Real Name: ') !!}
                                {!! Form::text('real_name', Input::old('real_name'), ['class' => 'form-control', 'placeholder' => 'Real Name']) !!}
                            </div>
                            <div class="form-group">
                                {!! Form::label('age', 'Age: ') !!}
                                {!! Form::text('age', Input::old('age'), ['class' => 'form-control', 'placeholder' => 'Age']) !!}
                            </div>
                            <div class="form-group">
                                {!! Form::label('title', 'Title: ') !!}
                                {!! Form::text('title', Input::old('title'), ['class' => 'form-control', 'placeholder' => 'Real Name']) !!}
                            </div>

                            <div class="form-group">
                                {!! Form::label('description', 'Bio: ') !!}
                                {!! Form::textarea('description', Input::old('description'), ['class' => 'form-control', 'placeholder' => 'Bio']) !!}
                            </div>

                            <div class="form-group">
                                {!! Form::label('description', 'Sort Order: ') !!}
                                {!! Form::select('sort_order',range(0,20),Input::old('sort_order'), ['class' => 'form-control col-md-2', 'placeholder' => 'Sort Order']) !!}
                            </div>

                        </div>
                    </div>
                </div>
                @include('templates.partials.savebar')
                {!! Form::open(array("role"=>"form","files"=>TRUE,"id"=>"upload-form")) !!}
                <div class="col-md-5">
                    <div class="box box-primary pad image-box">
                        <div class="box-header">
                            <h3 class="box-title"><i class="fa fa-file-image-o"></i> {{$cast->name}}'s Cast Images:</h3>
                        </div>
                        <div class="box-body" id="image-box">
                            <div class="form-group">
                                @if(!$cast->images->isEmpty())
                                    @include('shows.partials.images',['item' => $cast])
                                @endif
                            </div>
                            </div>
                        </div>
                    <div class="box box-primary pad image-box" id="upload-box">
                        <div class="box-header"></div>
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

                            </div>
                        </div>
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
                getImages();
            });
            $('.remove').on('click',function(){
                getRemoveImages($(this));
            });
        });

        function upload_complete(){
            getImages();
        }

        function getRemoveImages(item){
            var imgid = $(item).data('imgid');
            var itemid = $(item).data('itemid');
            $.get("/shows/casts/remove-images/"+imgid+"/"+itemid, function(response){
                $('#image-box').html(response);
                $('.colorbox a').colorbox();
            });

        }

        function getImages(){
            var castid = $("#upload_object_id option:selected").val();
            $.get("/shows/casts/cast-images/"+castid, function(response){
                $('#image-box').html(response);
                $('.colorbox a').colorbox();
            });
        }
    </script>
@endsection