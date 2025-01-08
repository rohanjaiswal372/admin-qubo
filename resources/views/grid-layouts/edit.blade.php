@extends("app")
@section("content")

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1><i class="fa fa-th-large"></i> Grid Layouts Edit</h1>
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

                            {!! Form::model($item, array('route' => array('grid-layouts.update', $item->id), 'method' => 'PUT')) !!}
                            <div class="form-group">
                                {!! Form::label('title', 'Title: ') !!}
                                {!! Form::text('title', Input::old('title'), ['class' => 'form-control', 'placeholder' => 'Page Title']) !!}
                            </div>

                            <div class="form-group">
                                {!! Form::label('path', 'Path: ') !!}
                                {!! Form::text('path', Input::old('path'), ['class' => 'form-control', 'placeholder' => 'Page Path']) !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @include('templates.partials.savebar')
        </section><!-- /.content -->
    </div><!-- /.content-wrapper -->

@stop


