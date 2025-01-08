@extends("app")
@section("content")
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="pull-left"><h3><i class="fa fa-plus text-success"></i> Create Campaign</h3>
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
            {{ HTML::ul($errors->all()) }}

            {!! Form::open(array('route' => array('campaigns.store'), 'method' => 'POST', 'files' => TRUE)) !!}
            <div class="row">
                <div class="col-md-8 col-xs-12">
                    <div class="box box-primary pad">
                        <div class="box-header with-border">
                            <h3 class="box-title pull-left"><i class="fa fa-info-circle text-info"></i> Info:</h3>
                            <div class="box-tools pull-right">
                                <button type="button" class="btn btn-box-tool" data-widget="collapse">
                                    <i class="fa fa-minus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="box-body">
                            <div class="form-group-sm col-md-6 tippy" title="Owner of the campaign"
                            <strong>{!! Form::label('campaign[user_id]', 'Assigned To: ') !!}</strong>
                            <select name="campaign[user_id]" class="form-control select2" required>
                                <option value="{{Auth::user()->id}}" selected>{{Auth::user()->fullname}}</option>
                                @foreach($users as $user)
                                    <option value="{{$user->id}}">{{$user->fullname}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group-sm col-md-6 tippy"
                             title="Status of the campaign, this controls if the advertisements under this campaign are visible on the live website.">
                            {!! Form::label('status_id', 'Status: ') !!}
                            {!! Form::select('status_id', ['pending-creatives' => "Pending Creatives"] + $statuses->pluck("name","id")->toArray(), Input::old('status'), ['class' => 'form-control select2' ,'required' => 'required' ]) !!}
                        </div>
                    </div>
                    <div class="box-body">
                        <div class="form-group-sm col-md-6 tippy"
                             title="Name of the campaign."
                        >
                            {!! Form::label('campaign[name]', 'Name: ') !!}
                            {!! Form::text('campaign[name]', Input::old('campaign[name]'), ['class' => 'form-control' ,'placeholder' => 'Campaign Name', 'required' => 'required']) !!}
                        </div>
                        <div class="form-group-sm col-md-3">
                            {!! Form::label('campaign[starts_at]', 'Start Date: ') !!}
                            <div class="input-group form-group">
                                <label class="input-group-btn" for="starts_at">
                                    <span class="btn btn-default btn-sm"><i class="fa fa-calendar"></i></span>
                                </label>
                                {!! Form::text('campaign[starts_at]',NULL, ['class' => 'form-control datepicker', 'id' => 'starts_at', 'placeholder' => 'Start Date', 'required' => 'required']) !!}
                            </div>
                        </div>
                        <div class="form-group-sm col-md-3">
                            {!! Form::label('campaign[ends_at]', 'Start Date: ') !!}
                            <div class="input-group form-group">
                                <label class="input-group-btn" for="ends_at">
                                    <span class="btn btn-default btn-sm"><i class="fa fa-calendar"></i></span>
                                </label>
                                {!! Form::text('campaign[ends_at]',NULL, ['class' => 'form-control datepicker', 'id' => 'ends_at', 'placeholder' => 'End Date', 'required' => 'required']) !!}
                            </div>
                        </div>
                        <div class="form-group-sm col-md-12 tippy"
                             title="People associated with aspects of the campaign, including designers, developers, sales and anyone needing to be updated on the statuses."
                        <strong>
                            <i class="fa fa-user text-info"></i> {!! Form::label('campaign_followers[]', 'Followers: ') !!}
                        </strong>
                        {!! Form::select('campaign_followers[]', $users->pluck("fullname","id")->toArray(), Input::old('campaign_followers[]'), ['class' => 'form-control select2' , 'multiple' => 'multiple' ]) !!}
                        <hr>
                    </div>
                    <div class="form-group-sm col-md-12">
                        {!! Form::label('campaign[description]', 'Description / Notes: ') !!}
                        {!! Form::textarea('campaign[description]',NULL, ['class' => 'form-control ckeditor', 'placeholder' => 'Notes', "rows" => '5']) !!}
                    </div>
                </div>
            </div>
    </div>
    <div class="col-lg-4 col-sm-6 col-xs-12">
        <div class="box box-primary pad">
            <div class="box-header with-border tippy"
                 title="To create a new sponsor enter the name in the input field and hit enter.">
                <h3 class="box-title"><i class="fa fa-users text-info"></i> Sponsor:</h3>
                <div class="box-tools pull-right">
                    <a href="{{ route('sponsors.index') }}"
                       title="View All sponsors"
                       class="tippy"><i class="fa fa-list"></i></a>
                    <a class="btn btn-box-tool tippy"
                       href="{{ route('sponsors.create') }}"
                       title="Create New Sponsor"><i class="fa text-success fa-plus-square fa-2x"></i></a>
                </div>
            </div>
            <div class="box-body">
                <div class="form-group-sm">
                    <div class="col-md-6">
                        {!! Form::label('sponsor_id', 'Sponsor: ') !!}
                        <select name="sponsor_id"
                                class="form-control sponsor_selector"
                                data-live-search='true' required>
                            <option value="" url="">Select One</option>
                            @foreach($sponsors as $sponsor)
                                <option value="{{$sponsor->id}}"
                                        url="{{$sponsor->url}}">{{$sponsor->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group-sm col-md-6">
                        {!! Form::label('logo', 'Logo: ') !!}
                        <img id="sponsor-logo" class='img img-thumbnail' src=""/>
                        <div class="form-group-sm" id="logo-upload">
                            <label class="btn btn-primary btn-block btn-file tippy"
                                   title="Upload new image"
                            ><span>
                                   <i class="fa fa-upload"></i> Upload Image</span><input type="file"
                                                                                          id="sponsor-logo-upload"
                                                                                          name="sponsor-logo"
                                                                                          class="hide">
                            </label>
                        </div>
                    </div>
                    <div class="form-group-sm col-md-6 fade hidden tippy"
                         title="Please include a full url - http://www.domain.com."
                    >
                        {!! Form::label('sponsor[url]', 'URL: ') !!}
                        {!! Form::url('sponsor[url]', Input::old('sponsor[url]'), ['class' => 'form-control sponsor_url', 'id'=>'sponsor_url' , 'placeholder' => 'http://www.domain.com']) !!}
                    </div>
                    <div class="form-group-sm col-md-6 hidden tippy" title="Use _blank to link to an outside website and leave it blank to stay
                                within the ION website.">
                        {!! Form::label('sponsor[link_target]', 'Link Target: ') !!}
                        {!! Form::select('sponsor[link_target]',
                        [
                        "_blank" => "_blank" ,
                        '_parent'=>'_parent',
                        '_self'=>'_self',
                        '_top'=>'_top'
                        ] ,Input::old('sponsor[link_target]'), ['class' => 'form-control', 'placeholder' => '_blank']) !!}
                    </div>
                </div>
            </div>
        </div>
        @include('templates.partials.savebar')
    </div>

    </section><!-- /.content -->
    </div><!-- /.content-wrapper -->
@stop

@section('footer_js')
    <script src="{{ asset("/js/campaigns.js") }}" type="text/javascript"></script>

@stop