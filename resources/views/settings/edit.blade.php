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
                            <h3 class="box-title">Editing {{$item->setting}}</h3>
                            <div class="box-tools pull-right">
                            </div>
                        </div>
                        <div class="box-body">


                            {{ HTML::ul($errors->all()) }}

                            {!! Form::model($item, array('route' => array('settings.update', $item->id), 'method' => 'PUT')) !!}
                            <div class="form-group">
                                {!! Form::label('setting', 'Setting: ') !!}
                                {!! Form::text('setting', Input::old('setting'), ['class' => 'form-control', 'placeholder' => 'Setting Title']) !!}
                            </div>

                            @if ( $item->type == 'switch' )
                                <div class="form-group">
                                    {!! Form::label('on_off', 'Turn On: ') !!}
                                    {!! Form::checkbox('on_off', Input::old('on_off'), ['class' => 'form-control']) !!}
                                    <p class="help-hint">Check to turn the switch on. Uncheck to turn it off.</p>
                                </div>
                            @else
                                <div class="form-group">
                                    {!! Form::label('value', 'Setting Value: ') !!}
                                    {!! Form::text('value', Input::old('value'), ['class' => 'form-control', 'placeholder' => 'Setting Value']) !!}
                                </div>
                            @endif

                        </div>
                    </div>
                </div>
            </div>
            @include('templates.partials.savebar')

        </section><!-- /.content -->
    </div><!-- /.content-wrapper -->

@stop


