@extends("app")
@section("content")

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1 class="pull-left">Constant Contact: Email Campaigns</h1>
            <div class="pull-right">
                <div class="btn-group">
                    <a role="button"
                       href="https://ui.constantcontact.com/rnavmap/emcf/email/create"
                       target="_blank"
                       class="btn btn-primary"><i
                                class="fa fa-plus"></i> Create New Campaign</a>
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
                            <table class="table tablesorter no-margin table-striped table-bordered table-hover table-condensed">
                                <thead>
                                <tr>
                                    <th>id</th>
                                    <th>Name</th>
                                    <th>Status</th>
                                    <th>Modified Date</th>
                                    <th>Options</th>
                                </tr>
                                </thead>
                                <tbody>
                                {{--var_dump($campaigns->results)--}}
                                @foreach($campaigns->results as $campaign)
                                    <tr>
                                        <td>{{$campaign->id}}</td>
                                        <td>{{$campaign->name}}</td>
                                        <td {!!  ($campaign->status == 'SENT')? 'class="success"' : (($campaign->status = 'DRAFT' ) ? 'class="warning"' :'')  !!}>{{$campaign->status}}</td>
                                        <td>{{ Carbon::parse($campaign->modified_date)->format('m/d/Y') }}</td>
                                        <td><a href="{{ URL::to('newsletter/campaigns/view/'.$campaign->id) }}"><i
                                                        class="fa fa-eye fa-2x"></i></a></td>
                                    </tr>
                                @endforeach

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </section><!-- /.content -->
    </div><!-- /.content-wrapper -->
@stop