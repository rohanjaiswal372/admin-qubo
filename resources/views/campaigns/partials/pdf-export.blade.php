{{ HTML::style(elixir('css/admin.css')) }}
<!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="pull-left"><h3>Campaign Report: <strong>{{$campaign->name}}</strong></h3></div>
            <div class="pull-right">
                <div class="btn-group">
                    <a href="{{ url('campaigns')}}" class="btn btn-primary"><i class="fa fa-undo"></i> Back</a>
                    <button class="btn btn-default print">Print <i class="fa fa-print"></i></button>
                    <a href="{{url('campaigns/export/'.$campaign->id.'/xls')}}" class="btn btn-default"><i
                                class="fa fa-file-excel-o"
                                title="Export Campaign Report to XLS"
                                data-toggle="tooltip"></i> Export Report</a>
                    <a href="{{url('campaigns/export/'.$campaign->id.'/pdf')}}" class="btn btn-default"><i
                                class="fa fa-file-excel-o"
                                title="Export Campaign Report to XLS"
                                data-toggle="tooltip"></i> PDF Report</a>
                    <a href="{{route('campaigns.edit',$campaign->id)}}" class="btn btn-default"><i
                                class="fa fa-pencil"
                                title="Edit Campaign"
                                data-toggle="tooltip"></i> Edit Campaign</a>
                    <a href="{{ url('campaigns/remove/'.$campaign->id) }}"
                       title="Remove: {{ $campaign->name }}"
                       data-toggle="tooltip"
                       class="btn btn-danger"
                       onClick="return confirm('Are you sure you want to remove this campaign?');"><i
                                class="fa fa-trash-o"></i></a>

                </div>
            </div>
            <div class="clearfix"></div>
        </section>
        {{--<!-- Main content -->--}}
        <section class="content">
            <div class="row">
                {{-- Report window --}}
                <div class="col-md-12">
                    <div class="box box-widget widget-user">
                        <!-- Add the bg color to the header using any of the bg-* classes -->
                        <div class="widget-user-header bg-aqua-active">
                            <div class="pull-right text-right">
                                <b>ION</b> Television<br>
                                <small>ION Media Networks &copy; {{date('Y')}}</small>
                                <br>
                                <div class="row visible-print-block">
                                    <div class="col-md-6 pull-left text-center">
                                        <h3><i class="fa fa-hand-pointer-o text-muted"></i></h3>
                                        <h5 class="description-header">{{$total_clicks}}</h5>
                                        <span class="description-text">TOTAL CLICKS</span>
                                        <!-- /.description-block -->
                                    </div>
                                    <!-- /.col -->
                                    <div class="col-md-6 pull-left text-center">
                                        <h3><i class="fa fa-binoculars text-muted"></i></h3>
                                        <h5 class="description-header">{{$total_impressions}}</h5>
                                        <span class="description-text">TOTAL IMPRESSIONS</span>
                                        <!-- /.description-block -->
                                    </div>
                                    <!-- /.col -->
                                </div>
                            </div>
                            <h4 class="widget-user-username"><strong>Campaign Report</strong> {{$campaign->name}}</h4>
                            <h5 class="widget-user-desc">{{$campaign->sponsor->url}}</h5>
                            <h4><i class="fa fa-calendar"></i> {{Carbon::parse($campaign->starts_at)->format('m-d-y')}}

                                &nbsp; &nbsp; &nbsp;
                                &nbsp;<i class="fa fa-calendar"></i> {{Carbon::parse($campaign->ends_at)->format('m-d-y')}}
                            </h4>

                        </div>
                        <div class="widget-user-image">
                            @if($campaign->sponsor->logo)
                                <img class="img-responsive"
                                     src="{{ image($campaign->sponsor->logo->url) }}"
                                     alt="Sponsor Logo">
                            @else
                                <h3>{{$campaign->sponsor->name}}</h3>
                            @endif
                        </div>
                        <div class="box-body pad" style="padding:100px 5px 0px;">

                            <div class="row pad with-border">
                                <div class="col-md-12">
                                    <div class="table-responsive">
                                        <table class="table no-margin table-striped table-condensed">
                                            <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>Start</th>
                                                <th>End</th>
                                                <th>Client</th>
                                                <th>Click-Through URL</th>
                                                <th>Platform</th>
                                                <th>Type
                                                <th>Entity</th>
                                                <th>Clicks</th>
                                                <th>Impressions</th>
                                                {{--<th>CPC</th>--}}

                                            </tr>
                                            </thead>
                                            <tbody>
                                            @foreach($ads as $ad)
                                                @if($ad->ad)
                                                    <tr class="small">
                                                        <td>{{$ad->ad->id}}</td>
                                                        <td>
                                                            <span class="badge"><i class="fa fa-calendar-o"></i> {{Carbon::parse($ad->ad['starts_at'])->format('m-d')}}</span>
                                                        </td>
                                                        <td>
                                                            <span class="badge"><i class="fa fa-calendar-o"></i> {{Carbon::parse($ad->ad['ends_at'])->format('m-d')}}</span>
                                                        </td>
                                                        <td>{{$ad->ad->sponsor->name}}</td>
                                                        <td>{{$ad->ad->sponsor->url}}</td>
                                                        <td>{{$ad->ad->platform->name}}</td>
                                                        <td>{{ mb_substr($ad->ad['morphable_type'], 4 ,strlen($ad->ad['morphable_type'])) }}</td>
                                                        <td>@if($ad->ad->morphable_type =="App\Post"){{ucfirst($ad->ad->advertisedItem->type_id)}}
                                                            | {{$ad->ad->advertisedItem->title}}@else{{str_limit($ad->ad->advertisedItem->name,50)}}@endif</td>
                                                        @if($ad->ad->category->platform->slug == "website")
                                                            <td>{{$ad->ad->click}}</td>
                                                            <td>{{$ad->ad->impression}}</td>
                                                            {{--<td>$</td>--}}
                                                        @else
                                                            <td>N/A</td>
                                                            <td>N/A</td>
                                                            {{--<td>N/A</td>--}}
                                                        @endif

                                                    </tr>
                                                @endif
                                            @endforeach

                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="row pad with-border" style="page-break-before: always">
                                <div class="container">
                                    <hr>
                                    <br>
                                    @foreach($ads as $ad)
                                        @if($ad->ad)
                                            {{--@if(!in_array($ad->ad->platform->slug,['mobile','ipad']) && file_exists(url("uploads/ion-television-ad-preview-".$ad->ad->id.".png")))--}}
                                            <div class="col-md-4" style="page-break: avoid">
                                                <div class="panel">
                                                    <div class="panel-heading">
                                                        <div class="text-center">
                                                            <h5>@if($ad->ad->morphable_type =="App\Post"){{ucfirst($ad->ad->advertisedItem->type_id)}}
                                                                | {{$ad->ad->advertisedItem->title}}@else{{str_limit($ad->ad->advertisedItem->name,50)}}@endif</h5>
                                                        </div>
                                                    </div>
                                                    <div class="panel-body">
                                                        @if(file_exists(public_path("uploads/ion-television-ad-preview-".$ad->ad->id.".png")))
                                                            <a href="{{ url("uploads/ion-television-ad-preview-".$ad->ad->id.".png") }}"
                                                               rel="adpreviews"
                                                               class="colorbox text-muted small"
                                                               title="Click to view Full Sized"
                                                               data-toggle="tooltip"> <img class="img-responsive"
                                                                                           src="{{ url("uploads/ion-television-ad-preview-".$ad->ad->id.".png") }}"/>
                                                            </a>
                                                        @else
                                                            <div class="pad text-center">
                                                                <h4 class="text-muted">Preview Not Available</h4>
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>

                                            </div>
                                            {{--@endif--}}
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                            <br><br>
                        </div>
                        <div class="box-footer">
                            <div class="row hidden-print">
                                <div class="col-sm-6 border-right">
                                    <div class="description-block">
                                        <h3><i class="fa fa-hand-pointer-o text-muted"></i></h3>
                                        <h5 class="description-header">{{$total_clicks}}</h5>
                                        <span class="description-text">TOTAL CLICKS</span>
                                    </div>
                                    <!-- /.description-block -->
                                </div>
                                <!-- /.col -->
                                <div class="col-sm-6 border-right">
                                    <div class="description-block">
                                        <h3><i class="fa fa-binoculars text-muted"></i></h3>
                                        <h5 class="description-header">{{$total_impressions}}</h5>
                                        <span class="description-text">TOTAL IMPRESSIONS</span>
                                    </div>
                                    <!-- /.description-block -->
                                </div>
                                <!-- /.col -->
                            </div>
                            <!-- /.row -->
                            <div class="row text-center visible-print">
                                <hr>
                                <small>Copyright &copy; {{date('Y')}} ION Media Networks. All Rights Reserved.</small>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </section>
    </div>

