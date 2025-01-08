@extends("app")

@section("content")
        <!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Upload Media
            <small>Optional description</small>


        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Level</a></li>
            <li class="active">Here</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="pull-right">
            <a href="{{ URL::to("shows/episodic-photos/{$show_id}#preview-{$object_id}") }}" class="btn btn-primary"><i class="fa fa-picture-o"></i> View Images</a>
        </div>

        <!-- form start -->
        {!! Form::open(array("role"=>"form","files"=>true,"id"=>"upload-form")) !!}

        <div class="box-body">

            <div class="form-group">
                <div class="row">
                    <div class="form-group col-lg-3">
                        {!! Form::label('show_id', 'Show: ') !!}
                        {!! Form::select('show_id', ["Select One"] + $show_id_selector , $show_id , ['class' => 'form-control','onChange'=>";window.location='".URL::to("/shows/episodes/media/create/?show_id=")."'+$('#show_id').val()",'autocomplete'=>"off"] ) !!}
                    </div>
                </div>
            </div>


            <div class="form-group">
                <div class="row">
                    <div class="form-group col-lg-3">
                        {!! Form::label('upload_object_id', 'Episode: ') !!}
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
                <div class="row">
                    <div class="form-group col-lg-3">
                        <input id="upload_create_thumbnail" type="checkbox" value=true class=" upload-param" checked /> &nbsp;  Create a Thumbnail for this image
                    </div>
                </div>
            </div>


            <div class="form-group thumbnail-crop-options">
                <div class="row">
                    <div class="form-group col-lg-3">
                        {!! Form::label('upload_thumbnail_crop_options', 'Thumbnail Crop Region:') !!}
                        {!! Form::select('upload_thumbnail_crop_options', ["Select One"] + $crop_options_selector , "" , ['class' => 'form-control upload-param', 'autocomplete'=>"off"] ) !!}
                    </div>
                </div>
                The System will create a thumbnail based on the selected crop region.
            </div>

            <br/>

            <div class="form-group">

                @include('templates.uploader')

                <div id="fine-uploader-manual-trigger"></div>

                <br clear="all" />

            </div>


        </div><!-- /.box-body -->



        {!! Form::close() !!}




    </section><!-- /.content -->
</div><!-- /.content-wrapper -->

@endsection


@section("footer_js")
    <script src="{{ asset("/js/fineuploader.js") }}" type="text/javascript"></script>
@endsection

