@if($stats->user_campaigns)
    {{-- Campaign Stats --}}
    <div class="box box-success">
        <div class="box-header with-border">
            <h3 class="box-title">Campaigns Owned</h3>

            <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse">
                    <i class="fa fa-minus"></i>
                </button>
                <button type="button" class="btn btn-box-tool" data-widget="remove">
                    <i class="fa fa-times"></i>
                </button>
            </div>
        </div>
        <!-- /.box-header -->
        <div class="box-body">
            <div class="table-responsive">
                <table class="table no-margin table-striped table-hover table-condensed">
                    <thead>
                    <tr>
                        <th>Dates</th>
                        <th>Program</th>
                        <th>Client</th>
                        <th>#ofAds</th>
                        <th>Status</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($stats->user_campaigns as $campaign)
                        <tr class="small clickable-row" data-href="{{ route('campaigns.edit', $campaign->id) }}">
                            <td>
                                <span class="badge"><i class="fa fa-calendar"></i> {{ Carbon::parse(date('m/d/Y g:i A', strtotime($campaign->starts_at)))->format('m/d') }}
                                    - {{ Carbon::parse(date('m/d/Y g:i A', strtotime($campaign->ends_at)))->format('m/d') }}</span>
                            </td>
                            <td>{{$campaign->name}}</td>
                            <td>
                                <div style="font-size:0.9em; margin: 0 10px">
                                    <a href="{{ route('sponsors.edit', $campaign->sponsor->id) }}">
                                        <h5 class="no-margin">{{$campaign->sponsor->name}}</h5>
                                    </a>
                                </div>
                            </td>
                            <td>{{$campaign->advertisements->count()}}</td>
                            <td>@if($campaign->status->status_id =='approved')
                                    <span class="label label-success"><i class="fa fa-check"></i>
                                        @elseif($campaign->status->status_id =='canceled')
                                            <span class="label label-danger"><i class="fa fa-ban"></i>
                                                @else
                                                    <span class="label label-warning"><i class="fa fa-clock-o"></i>
                                                        @endif{{$campaign->status->statusType->name}}</span>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div class="box-footer clearfix">
            <a href="/campaigns/create" class="btn btn-sm btn-info btn-flat pull-left"><i class="fa fa-plus"></i>
                Campaign</a> <a href="/campaigns/{{Auth::user()->username}}"
                                class="btn btn-sm btn-default btn-flat pull-right"><i class="fa fa-eye"></i> View My
                Campaigns</a>
        </div>
    </div>
@endif
@if(!$stats->campaigns_following->isEmpty())
    <div class="box box-danger">
        <div class="box-header with-border">
            <h3 class="box-title">Campaigns Following</h3>

            <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse">
                    <i class="fa fa-minus"></i>
                </button>
                <button type="button" class="btn btn-box-tool" data-widget="remove">
                    <i class="fa fa-times"></i>
                </button>
            </div>
        </div>
        <!-- /.box-header -->
        <div class="box-body no-padding">
            <div class="table-responsive">
                <table class="table no-margin table-striped table-hover table-condensed">
                    <thead>
                    <tr>
                        <th>Dates</th>
                        <th>Owner/Creator</th>
                        <th>Program</th>
                        <th>Client</th>
                        <th>#ofAds</th>
                        <th>Status</th>
                    </tr>
                    </thead>
                    <tbody>

                    @foreach($stats->campaigns_following as $campaign)
                        @if($campaign->campaigns)
                            <tr class="small clickable-row"
                                data-href="{{ route('campaigns.edit', $campaign->campaigns->first()->id) }}">
                                <td>
                                <span class="badge"><i class="fa fa-calendar"></i> {{ Carbon::parse(date('m/d/Y g:i A', strtotime($campaign->campaigns->first()->starts_at)))->format('m/d') }}
                                    - {{ Carbon::parse(date('m/d/Y g:i A', strtotime($campaign->campaigns->first()->ends_at)))->format('m/d') }}</span>
                                </td>
                                <td>{{$campaign->campaigns->first()->owner->fullname}}</td>
                                <td>{{$campaign->campaigns->first()->name}}</td>
                                <td>
                                    <div style="font-size:0.9em; margin: 0 10px">
                                        <a href="{{ route('sponsors.edit', $campaign->campaigns->first()->sponsor->id) }}">
                                            <h5 class="no-margin">{{$campaign->campaigns->first()->sponsor->name}}</h5>
                                        </a>
                                    </div>
                                </td>
                                <td>{{$campaign->campaigns->first()->advertisements->count()}}</td>
                                <td>@if($campaign->campaigns->first()->status->status_id =='approved')
                                        <span class="label label-success"><i class="fa fa-check"></i>
                                            @elseif($campaign->campaigns->first()->status->status_id =='canceled')
                                                <span class="label label-danger"><i class="fa fa-ban"></i>
                                                    @else
                                                        <span class="label label-warning"><i class="fa fa-clock-o"></i>
                                                            @endif
                                                            {{$campaign->campaigns->first()->status->statusType->name}}
                                            </span>

                            </tr>
                        @endif
                    @endforeach

                    </tbody>
                </table>
            </div>
            <div class="box-footer clearfix">
                <a href="/campaigns/create" class="btn btn-sm btn-info btn-flat pull-left"><i class="fa fa-plus"></i>
                    Campaign</a> <a
                        href="/campaigns/"
                        class="btn btn-sm btn-default btn-flat pull-right"><i class="fa fa-eye"></i> View All Campaigns</a>
            </div>
        </div>
@endif