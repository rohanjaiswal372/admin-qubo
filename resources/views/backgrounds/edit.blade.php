@extends("app")
@section("content")

        <!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1 class="pull-left">Backgrounds</h1>
        <a class="btn btn-danger pull-right" href="{{URL::previous()}}"> <i class="fa fa-undo"></i> Back</a>

    </section>
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary pad">
                    <div class="box-header with-border">
                        <h3 class="box-title">Editing {{$item->title}}</h3>
                        <div class="box-tools pull-right">
                            <a href="{{ URL::to('/backgrounds/remove/'.$item->id) }}"
                               title="Remove: {{ $item->title }}"
                               data-toggle="tooltip"
                               onClick="return confirm('Are you sure you want to remove this background?');"><i
                                        class="fa fa-trash-o text-danger fa-2x"></i></a>
                        </div>
                    </div>

                    <div class="box-body">
                        {{ HTML::ul($errors->all()) }}

                        {!! Form::model($item, array('route' => array('backgrounds.update', $item->id), 'method' => 'PUT', 'files' => TRUE)) !!}
                        <div class="form-group">
                            {!! Form::label('title', 'Title: ') !!}
                            {!! Form::text('title', Input::old('title'), ['class' => 'form-control', 'placeholder' => 'Page Title']) !!}
                        </div>

                        <div class="form-group">
                            {!! Form::label('path', 'Path: ') !!}
                            {!! Form::text('path', Input::old('path'), ['class' => 'form-control', 'placeholder' => 'Page Path']) !!}
                        </div>

                        <div class="form-group">
                            {!! Form::label('active', 'Active: ') !!}
                            {!! Form::checkbox('active', Input::old('active'), ['class' => 'form-control']) !!}
                            <p class="help-hint">Check to make active.</p>
                        </div>

                        <h2>Images</h2>

                        @if (count( $item->images) > 0 )
                            <table class="table no-margin">
                                <thead>
                                <tr>
                                    <th>Image</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach( $item->images as $image )
                                    <?php $image->toArray(); ?>
                                    <tr>
                                        <td>
                                            <img src="{{ config('filesystems.disks.rackspace.public_url') }}{{ $image['url'] }}"
                                                 style="max-width: 500px; height: auto;"/>
                                        </td>
                                        <td><a href="{{ URL::to('/image/remove/'.$image['id']) }}"
                                               onclick="return confirm('Are you sure? This will remove the image from our system.');">Remove</a>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                            <hr/>
                        @endif

                        <div class="form-group">
                            {!! Form::label('image', 'New Image: ') !!}
                            {!! Form::file('image') !!}
                        </div>

                        @include('templates.partials.savebar')
                    </div>
                </div>
            </div>
        </div>
    </section><!-- /.content -->
</div><!-- /.content-wrapper -->

@stop


