@extends("app")
@section("content")

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>Feedbacks</h1>
        </section>
        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <div class="box box-default">
                    <div class="box-header">
                        <h3 class="box-title">Create New Audience Relations: Feedback</h3>
                    </div>
                    <div class="box-body">
                        <div class="col-md-9 col-xs-12">
                            {{ HTML::ul($errors->all()) }}
                            {!! Form::open(array( 'url' => 'audience-relations/feedbacks/create','method' => 'POST','class' => 'form-horizontal')) !!}
                            <div class="form-group-sm">
                                <div class="col-md-3">
                                    {!! Form::label('subject_id', 'Subject: ', ['class' => 'control-label ']) !!}
                                    {!! Form::select('subject_id', $subjects, Input::old('subject_id'),  ['class' => 'form-control select2']) !!}
                                </div>
                                <div class="col-md-6">
                                    {!! Form::label('firstname', 'First Name: ', ['class' => 'control-label ']) !!}
                                    {!! Form::text('firstname', Input::old('firstname'), ['class' => 'form-control ', 'placeholder' => 'First Name']) !!}
                                </div>
                                <div class="col-md-6">
                                    {!! Form::label('lastname', 'Last Name: ', ['class' => 'control-label ']) !!}
                                    {!! Form::text('lastname', Input::old('lastname'), ['class' => 'form-control ', 'placeholder' => 'Last Name']) !!}
                                </div>
                                <div class="col-md-12">
                                    <hr class="clearfix">
                                </div>
                                <div class="col-md-5">
                                    {!! Form::label('email', 'Email: ', ['class' => 'control-label ']) !!}
                                    {!! Form::email('email', Input::old('email'), ['class' => 'form-control ', 'placeholder' => 'Email']) !!}
                                </div>
                                <div class="col-md-3">
                                    {!! Form::label('phone', 'Phone: ', ['class' => 'control-label ']) !!}
                                    {!! Form::text('phone', Input::old('phone'), ['class' => 'form-control ', 'placeholder' => 'Phone']) !!}
                                </div>
                                <div class="col-md-2">
                                    {!! Form::label('birthyear', 'Birth Year: ', ['class' => 'control-label ']) !!}
                                    {!! Form::text('birthyear', Input::old('birthyear'), ['class' => 'form-control ', 'placeholder' => 'Birthyear']) !!}
                                </div>
                                <div class="col-md-12">
                                    <hr class="clearfix">
                                </div>
                                <div class="col-md-4">
                                    {!! Form::label('city', 'City: ', ['class' => 'control-label ']) !!}
                                    {!! Form::text('city', Input::old('city'), ['class' => 'form-control ', 'placeholder' => 'City']) !!}
                                </div>
                                <div class="col-md-4">
                                    {!! Form::label('state', 'State: ', ['class' => 'control-label ']) !!}
                                    {!! Form::text('state', Input::old('state'), ['class' => 'form-control ', 'placeholder' => 'State']) !!}
                                </div>
                                <div class="col-md-4">
                                    {!! Form::label('zipcode', 'Zipcode: ', ['class' => 'control-label ']) !!}
                                    {!! Form::text('zipcode', Input::old('zipcode'), ['class' => 'form-control ', 'placeholder' => 'Zipcode']) !!}
                                </div>
                                <div class="col-md-12">
                                    <hr class="clearfix">
                                </div>
                                <div class="col-md-4">
                                    {!! Form::label('market', 'Market: ', ['class' => 'control-label ']) !!}
                                    {!! Form::select('market',$markets, Input::old('market'), ['class' => 'form-control select2']) !!}
                                </div>
                                <div class="col-md-4">
                                    {!! Form::label('channel_number', 'Channel Number: ', ['class' => 'control-label ']) !!}
                                    {!! Form::text('channel_number', Input::old('channel_number'), ['class' => 'form-control ', 'placeholder' => 'Channel Number']) !!}
                                </div>
                                <div class="col-md-4">
                                    {!! Form::label('provider', 'Provider: ', ['class' => 'control-label ']) !!}
                                    {!! Form::text('provider', Input::old('provider'), ['class' => 'form-control ', 'placeholder' => 'Provider']) !!}
                                </div>
                                <div class="col-md-4">
                                    {!! Form::label('format', 'Format: ', ['class' => 'control-label ']) !!}
                                    {!! Form::text('format', Input::old('channel_number'), ['class' => 'form-control ', 'placeholder' => 'Format']) !!}
                                </div>
                                <div class="col-md-4">
                                    {!! Form::label('newsletter', 'Newsletter: ', ['class' => 'control-label ']) !!}
                                    {!! Form::select('newsletter', ['1'=> 'yes' , '0' => 'No'], ['class' => 'form-control select2 ']) !!}
                                </div>
                                <div class="col-md-12">
                                    {!! Form::label('message', 'User Message: ', ['class' => 'control-label ']) !!}
                                    {!! Form::textarea('message', Input::old('message'), ['class' => 'form-control ', 'placeholder' => 'User Message']) !!}
                                </div>
                            </div>
                            @include('templates.partials.savebar')
                        </div>
                    </div>
                </div>
        </section><!-- /.content -->
    </div><!-- /.content-wrapper -->
@stop





