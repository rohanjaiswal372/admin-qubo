@extends("app")
@section("content")

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>Users</h1>
        </section>
        <!-- Main content -->
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="box box-primary pad">
                        <div class="box-header with-border">
                            <h3 class="box-title">New User</h3>
                            <div class="box-tools pull-right">
                            </div>
                        </div>
                        <div class="box-body">
                            @if($errors)<span class="help-block">{{ HTML::ul($errors->all()) }}</span>@endif

                            {!! Form::open(array('route' => array('users.store'), 'method' => 'POST')) !!}

                            <div class="row">
                                <div class="form-group col-lg-3">
                                    {!! Form::label('type_id', 'User Type: ') !!}
                                    {!! Form::select('type_id', array('ion' => 'ION User', 'local' => 'Local User'), null , ['class' => 'form-control user-type-selectbox'] ) !!}
                                </div>
                            </div>
                            <div class="row ion-user-container">
                                <div class="form-group col-lg-3">
                                    {!! Form::label('ion_username', 'ION User: ') !!}
                                    {!! Form::select('ion_username', $ion_users ,Input::old('ion_username'), ['class' => 'form-control select2','data-live-search'=>'true'] ) !!}
                                </div>
                            </div>
                            <div class="local-user-container" style="display:none">
                                <div class="form-group">
                                    {!! Form::label('username', 'Username: ') !!}
                                    {!! Form::text('username', Input::old('username'), ['class' => 'form-control', 'placeholder' => 'Username']) !!}
                                </div>

                                <div class="form-group">
                                    {!! Form::label('firstname', 'First Name: ') !!}
                                    {!! Form::text('firstname', Input::old('firstname'), ['class' => 'form-control', 'placeholder' => 'First Name']) !!}
                                </div>

                                <div class="form-group">
                                    {!! Form::label('lastname', 'Last Name: ') !!}
                                    {!! Form::text('lastname', Input::old('lastname'), ['class' => 'form-control', 'placeholder' => 'Last Name']) !!}
                                </div>

                                <div class="form-group">
                                    {!! Form::label('email', 'Email Address: ') !!}
                                    {!! Form::text('email', Input::old('email'), ['class' => 'form-control', 'placeholder' => 'Email Address']) !!}
                                </div>
                                <div class="form-group">
                                    {!! Form::label('password', 'Password: ') !!}
                                    {!! Form::password('password', Input::old('password'), ['class' => 'form-control', 'placeholder' => 'Password']) !!}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @include('templates.partials.savebar')
        </section><!-- /.content -->
    </div><!-- /.content-wrapper -->
@endsection
@section("footer_js")
    <script src="{{ asset("/js/users.js") }}" type="text/javascript"></script>
@endsection

