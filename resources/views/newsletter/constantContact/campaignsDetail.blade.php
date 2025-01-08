@extends("app")
@section("content")

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1 class="pull-left">Constant Contact: Email Campaigns</h1>
            <div class="pull-right">
                <div class="btn-group">
                    <a role="button" href="https://ui.constantcontact.com/rnavmap/emcf/email/create" target="_blank"
                       class="btn btn-primary"><i class="fa fa-plus"></i> Create New Campaign</a> <a role="button"
                                                                                                     href="{{URL::to('newsletter/campaigns')}}"
                                                                                                     class="btn btn-default"><i
                                class="fa fa-list"></i> All Campaigns</a>
                </div>
            </div>
        </section>

        <hr class="clearfix"/>

        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="box box-primary pad">
                        <div class="box-header with-border">
                            <h3 class="box-title"></h3>
                            <div class="box-tools pull-right">

                            </div>
                        </div>

                        <div class="box-body">
                            <form action="" method="POST" role="form">
                                <legend>{{$campaign->name}}</legend>
                                <p>Campaign ID: {{$campaign->id}}</p>
                                <a class="btn btn-default btn-lg" href="{{$campaign->permalink_url}}" target="_blank">
                                    <i
                                            class="fa fa-eye"></i> Preview Campaign</a> <br>
                                <div class="form-group">
                                    <label for="name">Name</label> <input type="text"
                                                                          class="form-control"
                                                                          name="name"
                                                                          id="name"
                                                                          placeholder="{{$campaign->name}}">
                                </div>
                                <div class="form-group">
                                    <label for="name">Subject</label> <input type="text"
                                                                             class="form-control"
                                                                             name="subject"
                                                                             id="subject"
                                                                             placeholder="{{$campaign->subject}}">
                                </div>

                                <div class="form-group col-md-6">
                                    <label for="from_name">From Name:</label> <input type="text"
                                                                                     class="form-control"
                                                                                     name="from_name"
                                                                                     id="from_name"
                                                                                     placeholder="{{$campaign->from_name}}">

                                    <label for="from_email">From Email:</label> <input type="text"
                                                                                       class="form-control"
                                                                                       name="from_email"
                                                                                       id="from_email"
                                                                                       placeholder="{{$campaign->from_email}}">

                                    <label for="from_email">Created Date:</label> <input type="text"
                                                                                         class="form-control datepicker"
                                                                                         name="created_date"
                                                                                         id="created_date"
                                                                                         placeholder="{{ $campaign->created_date ? Carbon::parse($campaign->created_date)->format('m/d/Y') : "" }}">

                                    <label for="modified_date">Modified Date:</label> <input type="text"
                                                                                             class="form-control datepicker"
                                                                                             name="modified_date"
                                                                                             id="modified_date"
                                                                                             placeholder="{{ $campaign->modified_date ? Carbon::parse($campaign->modified_date)->format('m/d/Y') : "" }}">

                                    <label for="last_run_date">Last Run Date:</label> <input type="text"
                                                                                             class="form-control datepicker"
                                                                                             name="last_run_date"
                                                                                             id="last_run_date"
                                                                                             placeholder="{{ $campaign->last_run_date ? Carbon::parse($campaign->last_run_date)->format('m/d/Y') : "" }}">

                                    <label for="next_run_date">Next Run Date:</label> <input type="text"
                                                                                             class="form-control datepicker"
                                                                                             name="next_run_date"
                                                                                             id="next_run_date"
                                                                                             placeholder="{{ $campaign->next_run_date ? Carbon::parse($campaign->next_run_date)->format('m/d/Y') : ""}}">

                                    <h3>Message Foooter:</h3>
                                    @if($campaign->message_footer  )
                                        <label for="city">City:</label>
                                        {!! Form::text("message_footer.city", Input::old("message_footer.city"),['class' => 'form-control', 'placeholder' => $campaign->message_footer->city ]) !!}

                                        <label for="state">State:</label>
                                        {!! Form::text("message_footer.state", Input::old("message_footer.state"),['class' => 'form-control', 'placeholder' => $campaign->message_footer->state ]) !!}

                                        <label for="state">Country:</label>
                                        {!! Form::text("message_footer.country", Input::old("message_footer.country"),['class' => 'form-control', 'placeholder' => $campaign->message_footer->country ]) !!}

                                        <label for="state">Organization:</label>
                                        {!! Form::text("message_footer.organization_name", Input::old("message_footer.organization_name"),['class' => 'form-control', 'placeholder' => $campaign->message_footer->organization_name ]) !!}

                                        <label for="state">Address:</label>
                                        {!! Form::text("message_footer.address_line_1", Input::old("message_footer.address_line_1"),['class' => 'form-control', 'placeholder' => $campaign->message_footer->address_line_1 ]) !!}

                                        <label for="state">Postal Code:</label>
                                        {!! Form::text("message_footer.postal_code", Input::old("message_footer.postal_code"),['class' => 'form-control', 'placeholder' => $campaign->message_footer->postal_code ]) !!}
                                    @endif
                                </div>
                                <div class="form-group col-md-6">
                                    <div class="well">
                                        <h3>Tracking Summary</h3>
                                        <table class="table table-hover table-striped table-bordered table-hover table-condensed">
                                            <thead>
                                            <tr>
                                                <th>Sends</th>
                                                <th>Opens</th>
                                                <th>Clicks</th>
                                                <th>Forwards</th>
                                                <th>Unsubscribes</th>
                                                <th>Bounces</th>
                                                <th>Spam Count</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <tr>
                                                <td>{{$campaign->tracking_summary->sends}}</td>
                                                <td>{{$campaign->tracking_summary->opens}}</td>
                                                <td>{{$campaign->tracking_summary->clicks}}</td>
                                                <td>{{$campaign->tracking_summary->forwards}}</td>
                                                <td>{{$campaign->tracking_summary->unsubscribes}}</td>
                                                <td>{{$campaign->tracking_summary->bounces}}</td>
                                                <td>{{$campaign->tracking_summary->spam_count}}</td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="well">
                                        <h3>Sent to Lists</h3>
                                        <table class="table table-hover table-striped table-bordered table-hover table-condensed">
                                            <thead>
                                            <tr>
                                                <th>Id</th>
                                                <th>Name</th>
                                                <th>Status</th>
                                                <th>Contacts</th>
                                                <th>Created</th>
                                                <th>Modified</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @foreach($lists_info as $list)
                                                <tr>
                                                    <td><a class='btn btn-default btn-xs'
                                                           href="{{ URL::to('newsletter/list/view/'.$list->id) }}"
                                                           title="{{ $list->id }}"><i class="fa fa-eye"></i></a></td>
                                                    <td>{{$list->name}}</td>
                                                    <td {!!  ($list->status == 'ACTIVE')? 'class="success"' : '' !!}>{{$list->status}}</td>
                                                    <td>{{$list->contact_count}}</td>
                                                    <td>{{Carbon::parse($list->created_date)->format('m/d/Y')}}</td>
                                                    <td>{{Carbon::parse($list->modified_date)->format('m/d/Y')}}</td>
                                                </tr>
                                            @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="form-group col-md-6">
                                    <div class="well">
                                        <h3>Click Through Details</h3>
                                        <table class="table table-hover table-striped table-bordered table-hover table-condensed">
                                            <thead>
                                            <tr>
                                                <th>URL</th>
                                                <th>URL_ID</th>
                                                <th>Click Count</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @foreach($campaign->click_through_details as $details)
                                                <tr>
                                                    <td><a href="{{$details->url}}"
                                                           target="_blank"
                                                           class="btn btn-default"><i class="fa fa-eye"></i></a></td>
                                                    <td>{{$details->url_uid}}</td>
                                                    <td><span class="badge">{{$details->click_count}}</span></td>
                                                </tr>
                                            @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section><!-- /.content -->
    </div><!-- /.content-wrapper -->
@stop