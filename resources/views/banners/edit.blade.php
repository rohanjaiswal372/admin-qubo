@extends("app")
@section("content")

        <!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>Banners</h1>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary pad">
                    <div class="box-header with-border">
                        <h3 class="box-title">Editing {{$item->title}}</h3>
                        <div class="box-tools pull-right">

                        </div>
                    </div>

                    <div class="box-body">

                        {{ HTML::ul($errors->all()) }}

                        {!! Form::model($item, array('route' => array('banners.update', $item->id), 'method' => 'PUT', 'files' => TRUE)) !!}
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

                        <div class="form-group">
                            {!! Form::label('url', 'URL: ') !!}
                            {!! Form::text('url', Input::old('url'), ['class' => 'form-control', 'placeholder' => 'http://www.iontelevision.com/shows']) !!}
                            <p class="help-block">Please input the location this banner should send the user.</p>
                        </div>

                        <div class="form-group">
                            {!! Form::label('headline', 'Headline: ') !!}
                            {!! Form::text('headline', Input::old('headline'), ['class' => 'form-control', 'placeholder' => 'Bond Marathon']) !!}
                        </div>

                        <div class="form-group">
                            {!! Form::label('tagline', 'Tagline: ') !!}
                            {!! Form::text('tagline', Input::old('tagline'), ['class' => 'form-control', 'placeholder' => 'Start watching this weekend']) !!}
                        </div>

                        <div class="form-group">
                            {!! Form::label('description', 'Description: ') !!}
                            <textarea name="description" class="form-control">{!! $item->description !!}</textarea>
                        </div>

                        <div class="form-group">
                            {!! Form::label('headline_align', 'Headline Alignment: ') !!}
                            {!! Form::select('headline_align', ['left' => 'Align Left', 'right' => 'Align Right'], Input::old('headline_align')) !!}
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

    </section><!-- /.content -->
</div><!-- /.content-wrapper -->

@stop


