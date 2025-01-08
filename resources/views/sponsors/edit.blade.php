@extends("app")
@section("content")
        <!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="pull-left"><h3><strong>Edit Sponsor:</strong> {{$sponsor->name}}</h3>
            <div id="debug"></div>
        </div>
        <div class="pull-right">
            <div class="button-group">
                <a href="{{ URL::previous() }}" class="btn btn-primary"><i class="fa fa-undo"></i> Back</a>
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

                    {!! Form::model($sponsor, array('route' => array('sponsors.update', $sponsor->id), 'method' => 'PATCH','files' => TRUE)) !!}
                    <div class="form-group">
                        {!! Form::label('name', 'Name: ') !!}
                        {!! Form::text('name', Input::old('name'), ['class' => 'form-control', 'placeholder' => 'Source ID']) !!}
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
                        @if($sponsor->logo)
                            <table class="table no-margin">
                                <thead>
                                <tr>
                                    <th>Image</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td>
                                        <a class="colorbox" href="{{ config('filesystems.disks.rackspace.public_url') }}{{ $sponsor->logo->url }}" >
                                            <img class='img img-thumbnail' src="{{ config('filesystems.disks.rackspace.public_url') }}{{ $sponsor->logo->url }}"
                                            /> </a>
                                    </td>
                                    <td>
                                        <a href="{{ URL::to('/image/remove/'.$sponsor->logo->id) }}"
                                           data-toggle="tooltip"
                                           title="Remove"
                                           onclick="return confirm('Are you sure? This will remove the image from our system.');"><i
                                                    class="fa fa-trash fa-2x text-danger"></i></a>
                                </tr>
                                </tbody>
                            </table>
                            <hr/>
                        @endif
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
