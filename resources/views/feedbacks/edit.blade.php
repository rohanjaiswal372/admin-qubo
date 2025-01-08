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
                        <h3 class="box-title">Edit Audience Relations: Feedback </h3>
                    </div>
                    <div class="box-body">
                        <div class="col-md-9 col-xs-12">
                            {{ HTML::ul($errors->all()) }}
                            {!! Form::model($item, ['route' => array('feedbacks.update', $item->id), 'method' => 'PATCH']) !!}
                            <div class="form-group-sm">
                                <div class="col-md-3">
                                    {!! Form::label('subject_id', 'Subject: ', ['class' => 'control-label']) !!}
                                    {!! Form::select('subject_id', $subjects,$item->subject_id , ['class' => 'form-control select2']) !!}
                                </div>
                                <div class="col-md-6">
                                    {!! Form::label('firstname', 'First Name: ', ['class' => 'control-label']) !!}
                                    {!! Form::text('firstname', $item->firstname, ['class' => 'form-control ']) !!}
                                </div>
                                <div class="col-md-6">
                                    {!! Form::label('lastname', 'Last Name: ', ['class' => 'control-label']) !!}
                                    {!! Form::text('lastname', $item->lastname, ['class' => 'form-control ']) !!}
                                </div>
                                <div class="col-md-12">
                                    <hr class="clearfix">
                                </div>
                                <div class="col-md-5">
                                    {!! Form::label('email', 'Email: ', ['class' => 'control-label']) !!}
                                    {!! Form::email('email', $item->email, ['class' => 'form-control ']) !!}
                                </div>
                                <div class="col-md-3">
                                    {!! Form::label('phone', 'Phone: ', ['class' => 'control-label']) !!}
                                    {!! Form::text('phone', $item->phone, ['class' => 'form-control ']) !!}
                                </div>
                                <div class="col-md-2">
                                    {!! Form::label('birthyear', 'Birth Year: ', ['class' => 'control-label']) !!}
                                    {!! Form::text('birthyear', $item->birthyear, ['class' => 'form-control ']) !!}
                                </div>
                                <div class="col-md-12">
                                <hr class="clearfix">
                                </div>
                                <div class="col-md-4">
                                    {!! Form::label('city', 'City: ', ['class' => 'control-label']) !!}
                                    {!! Form::text('city', $item->city, ['class' => 'form-control ']) !!}
                                </div>
                                <div class="col-md-4">
                                    {!! Form::label('state', 'State: ', ['class' => 'control-label']) !!}
                                    {!! Form::text('state', $item->state, ['class' => 'form-control ']) !!}
                                </div>
                                <div class="col-md-4">
                                    {!! Form::label('zipcode', 'Zipcode: ', ['class' => 'control-label']) !!}
                                    {!! Form::text('zipcode', $item->zipcode, ['class' => 'form-control ']) !!}
                                </div>
                                <div class="col-md-12">
                                    <hr class="clearfix">
                                </div>
                                <div class="col-md-4">
                                    {!! Form::label('market', 'Market: ', ['class' => 'control-label']) !!}
                                    {!! Form::select('market', $markets, $item->market, ['class' => 'form-control select2']) !!}
                                </div>
                                <div class="col-md-4">
                                    {!! Form::label('channel_number', 'Channel Number: ', ['class' => 'control-label']) !!}
                                    {!! Form::text('channel_number', $item->channel_number, ['class' => 'form-control ']) !!}
                                </div>
                                <div class="col-md-4">
                                    {!! Form::label('provider', 'Provider: ', ['class' => 'control-label']) !!}
                                    {!! Form::text('provider', $item->provider, ['class' => 'form-control ']) !!}
                                </div>
                                <div class="col-md-4">
                                    {!! Form::label('format', 'Format: ', ['class' => 'control-label']) !!}
                                    {!! Form::text('format', $item->format, ['class' => 'form-control ']) !!}
                                </div>
                                <div class="col-md-4">
                                    {!! Form::label('newsletter', 'Newsletter: ', ['class' => 'control-label']) !!}
                                    {!! Form::select('newsletter', ['1'=> 'yes' , '0' => 'No'],$item->newsletter,  ['class' => 'form-control select2' ]) !!}
                                </div>
                                <div class="col-md-12">
                                    {!! Form::label('message', 'User Message: ', ['class' => 'control-label']) !!}
                                    {!! Form::textarea('message', $item->message, ['class' => 'form-control ']) !!}
                                </div>
                            </div>
                            @include('templates.partials.savebar')
                        </div>
                    </div>
                </div>
        </section><!-- /.content -->
    </div><!-- /.content-wrapper -->
@stop


