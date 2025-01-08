@extends("app")
@section("content")
        <!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="pull-left"><h3>New Sponsor</h3>
            <div id="debug"></div>
        </div>
        <div class="pull-right">
            <div class="button-group">
                <a href="{{ URL::to('sponsors') }}" class="btn btn-primary"><i class="fa fa-undo"></i> All Sponsors</a>
            </div>
        </div>
        <div class="clearfix"></div>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-7 col-xs-12">
                <div class="box box-primary pad">

                    {{ HTML::ul($errors->all()) }}

                    {!! Form::open(array('route' => array('sponsors.store'),'method' => 'POST', 'files' => TRUE)) !!}
                    <div class="form-group">
                        {!! Form::label('name', 'Name: ') !!}
                        {!! Form::text('name', Input::old('name'), ['class' => 'form-control', 'placeholder' => 'Name']) !!}
                    </div>

                    <div class="form-group">
                        {!! Form::label('url', 'URL: ') !!}
                        {!! Form::text('url', Input::old('url'), ['class' => 'form-control', 'placeholder' => 'URL']) !!}
                    </div>

                    <div class="input-group">
                        {!! Form::label('color', 'Color: ex(#efefef) ') !!}
                        {!! Form::text('color', Input::old('color'), ['class' => 'form-control colorpicker', 'placeholder' => 'color']) !!}

                    </div>

                </div>
            </div>
            <div class="col-md-5">
                <div class="box box-primary pad image-box" id="image-box">
                    <div class="box-header">
                        <h3 class="box-title"><i class="fa fa-file-image-o"></i> Image/Logo:</h3>
                    </div>
                    <div class="box-body">
                        <div class="form-group">
                            {!! Form::label('image', 'Image or SWF: ') !!}
                            {!! Form::file('image') !!}
                        </div>
                    </div>
                </div>
            </div>
            @include('templates.partials.savebar')
        </div>
    </section><!-- /.content -->
</div><!-- /.content-wrapper -->

@stop
