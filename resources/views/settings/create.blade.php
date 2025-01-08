@extends("app")
@section("content")

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>Settings</h1>
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="box box-primary pad">
                        <div class="box-header with-border">
                            <h3 class="box-title">Create A New Setting</h3>
                            <div class="box-tools pull-right">
                            </div>
                        </div>
                        <div class="box-body">


                            {{ HTML::ul($errors->all()) }}

                            {!! Form::open(array('route' => array('settings.store'), 'method' => 'POST')) !!}
                            <div class="form-group">
                                {!! Form::label('setting', 'Setting: ') !!}
                                {!! Form::text('setting', Input::old('setting'), ['class' => 'form-control', 'placeholder' => 'Setting Title']) !!}
                            </div>

                            <div class="form-group">
                                {!! Form::label('type', 'Setting Type: ') !!}
                                {!! Form::select('type', ['switch' => 'On / Off Switch', 'type-in' => 'Fill In'], Input::old('type')) !!}
                            </div>

                            <p>Once you select a type and save the setting, you will be able to enter it's defautl
                                value.</p>

                            <div></div>
                        </div>
                    </div>
            @include('templates.partials.savebar')
        </section><!-- /.content -->
    </div><!-- /.content-wrapper -->

@stop


