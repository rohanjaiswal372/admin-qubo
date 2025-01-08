<!-- Post Stats -->
<div class="box box-success">
    <div class="box-header with-border">
        <h3 class="box-title">Ads Coming up</h3>

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
            <table class="table no-margin table-striped table-condensed table-hover">
                <thead>
                <tr>
                    <th>Date</th>
                    <th>Sponsor</th>
                    <th>Type</th>
                    <th>Status</th>
                </tr>
                </thead>
                <tbody>
                @foreach($stats->adsrecent as $ad)
                    <tr class="small clickable-row" data-href="{{ route('advertisements.edit', $ad['id']) }}">
                        <td>
                            <a href="{{ URL::to('advertisements#calendar-tab') }}"><span class="badge"><i class="fa fa-calendar-o"></i> {{Carbon::parse($ad['starts_at'])->format('m-d')}}</span></a>
                        </td>
                        <td><a href="{{ route('sponsors.edit', $ad['sponsor_id']) }}" title="{{$ad->sponsor->url}}" data-toggle="tooltip">{{$ad->sponsor->name}}</a></td>
                        <td>{{ mb_substr($ad['morphable_type'], 4 ,strlen($ad['morphable_type'])) }}</td>

                        <td>
                            <label {!! ($ad['active'])? "class='label label-success'>Live</label>" : "class='label label-danger'>Off</label>" !!}
                        </td>
                    </tr>
                @endforeach

                </tbody>
            </table>
        </div>
    </div>
    <div class="box-footer clearfix">
        <a href="/advertisements/create" class="btn btn-sm btn-info btn-flat pull-left"><i class="fa fa-plus"></i> Ad</a>
        <a href="/advertisements" class="btn btn-sm btn-default btn-flat pull-right"><i class="fa fa-eye"></i> View All
            ads</a>
    </div>
</div>
<div class="box box-danger">
    <div class="box-header with-border">
        <h3 class="box-title">Ads Recentley Expired</h3>

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
        <div class="table-responsive ">
            <table class="table no-margin table-striped table-condensed table-hover">
                <thead>
                <tr>
                    <th>Date</th>
                    <th>Sponsor</th>
                    <th>Type</th>
                    <th>Status</th>
                </tr>
                </thead>
                <tbody>
                @foreach($stats->adsrecentexpired as $ad)
                    <tr class="small clickable-row" data-href="{{ route('advertisements.edit', $ad['id']) }}">
                        <td>
                            <a href="{{ URL::to('advertisements#calendar-tab') }}"><span class="badge"><i class="fa fa-calendar-o"></i> {{Carbon::parse($ad['starts_at'])->format('m-d')}}</span></a>
                        </td>
                        <td><a href="{{ route('sponsors.edit', $ad['sponsor_id']) }}" title="{{$ad->sponsor->url}}" data-toggle="tooltip">{{$ad->sponsor->name}}</a></td>
                        <td>{{ mb_substr($ad['morphable_type'], 4 ,strlen($ad['morphable_type'])) }}</td>

                        <td>
                            <label {!! ($ad['active'])? "class='label label-warning'>Expired</label>" : "class='label label-danger'>Off</label>" !!}
                        </td>
                    </tr>
                @endforeach

                </tbody>
            </table>
        </div>
    </div>
    <div class="box-footer clearfix">
        <a href="/advertisements/create" class="btn btn-sm btn-info btn-flat pull-left"><i class="fa fa-plus"></i> Ad</a>
        <a href="/advertisements/expired/list" class="btn btn-sm btn-default btn-flat pull-right"><i class="fa fa-eye"></i> View All
            ads</a>
    </div>
</div>