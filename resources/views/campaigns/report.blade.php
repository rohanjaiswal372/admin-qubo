@extends("app")
@section('header')
@stop
@section("content")

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="pull-left"><h3>Campaign Report: <strong>{{$campaign->name}}</strong></h3></div>
            <div class="pull-right">
                <div class="btn-group">
                    <a href="{{ url('campaigns')}}" class="btn btn-primary"><i class="fa fa-undo"></i> Back</a>
                    <button class="btn btn-default print"><i class="fa fa-print "></i> <span class="hidden-xs"> Print</span></button>
                    <a href="{{url('campaigns/export/'.$campaign->id.'/xls')}}" class="btn btn-default"><i
                                class="fa fa-cloud-download"
                                title="Export Campaign Report to XLS"
                                data-toggle="tooltip"></i> <span class="hidden-xs">XLS</span></a>
                    <a href="{{url('campaigns/download-preview/'.$campaign->id)}}" class="btn btn-default"><i
                                class="fa fa-cloud-download"
                                title="Export Campaign Report to XLS"
                                data-toggle="tooltip"></i> <span class="hidden-xs">PDF</span> </a>
                    <a href="{{route('campaigns.edit',$campaign->id)}}" class="btn btn-default"><i
                                class="fa fa-pencil"
                                title="Edit Campaign"
                                data-toggle="tooltip"></i> <span class="hidden-xs">Edit </span></a>
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
                            </div>
                            <h4 class="widget-user-username"><strong>Campaign Report</strong> {{$campaign->name}}</h4>
                            {{--<h5 class="widget-user-desc">{{$campaign->sponsor->url}}</h5>--}}
                            <h4><i class="fa fa-calendar"></i> {{Carbon::parse($campaign->starts_at)->format('m-d-y')}}

                                &nbsp; &nbsp; &nbsp;
                                &nbsp;<i class="fa fa-calendar"></i> {{Carbon::parse($campaign->ends_at)->format('m-d-y')}}
                            </h4>

                        </div>
                        <div class="widget-user-image">
                            @if($campaign->sponsor->logo)
                                <img class="img-thumbnail" style="background-color: #efefef;"
                                     src="{{ image($campaign->sponsor->logo->url) }}"
                                     alt="Sponsor Logo">
                            @else
                                <h3>{{$campaign->sponsor->name}}</h3>
                            @endif
                        </div>
                        <div class="box-body pad">
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

                            <div class="row pad with-border" style="padding-top:50px">
                                <div class="col-md-12">
                                    <div class="table-responsive">
                                        <table class="table no-margin table-striped table-condensed table-responsive">
                                            <thead>
                                            <tr>
                                                <th>Start</th>
                                                <th>End</th>
                                                <th>Client</th>
                                                <th>Click-Through URL</th>
                                                <th>Platform</th>
                                                <th>Type
                                                <th>Entity</th>
                                                <th class="text-center">Clicks</th>
                                                <th class="text-center">Impressions</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @foreach($campaign->advertisements as $ad)
                                                @if($ad && $ad->ad && $ad->ad->category)
                                                    <tr class="small" class="tippy" title="AD ID: {{$ad->ad->id}}">
                                                        <td>
                                                            <span class="badge"><i class="fa fa-calendar-o"></i> {{Carbon::parse($ad->ad['starts_at'])->format('m-d')}}</span>
                                                        </td>
                                                        <td>
                                                            <span class="badge"><i class="fa fa-calendar-o"></i> {{Carbon::parse($ad->ad['ends_at'])->format('m-d')}}</span>
                                                        </td>
                                                        <td>{{($ad->sponsor)? $ad->sponsor->name : "N/A"}}</td>
                                                        <td>{{($ad->sponsor)? str_limit($ad->sponsor->url,30) :""}}</td>
                                                        <td>{{$ad->ad->platform->name}}</td>
                                                        <td>{{ mb_substr($ad->ad['morphable_type'], 4 ,strlen($ad->ad['morphable_type'])) }}</td>
                                                        <td>@if($ad->ad->morphable_type =="App\Post"){{ucfirst($ad->ad->type_id)}}
                                                            | {{$ad->ad->title}}@else{{str_limit($ad->ad->name,40)}}@endif</td>
                                                        @if($ad->ad->category->platform->slug == "website")
                                                            <td class="text-center">{{$ad->ad->click}}</td>
                                                            <td class="text-center">{{$ad->ad->impression}}</td>
                                                            <td>$</td>
                                                        @else
                                                            <td class="text-muted text-center">N/A</td>
                                                            <td class="text-muted text-center">N/A</td>
                                                            <td>N/A</td>
                                                        @endif

                                                    </tr>
                                                @endif
                                            @endforeach

                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <td colspan="7" class="text-right"><strong>Totals:</strong></td>
                                                    <td class="text-center">{{$total_clicks}}</td>
                                                    <td class="text-center">{{$total_impressions}}</td>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="row pad with-border">
                                <hr class="hidden-print">
                                <div class="container-fluid">
                                    {{--@foreach($ads as $ad)--}}
                                        {{--@if($ad->ad)--}}
                                            {{--@if(!in_array($ad->ad->platform->slug,['mobile','ipad']) && file_exists(url("uploads/ion-television-ad-preview-".$ad->ad->id.".png")))--}}
                                            {{--<div class="col-xs-6 col-lg-4 col-center" style="page-break-before: always; page-break-after:avoid; page-break-inside:avoid">--}}
                                                {{--<div class="panel">--}}
                                                    {{--<div class="panel-heading">--}}
                                                        {{--<div class="text-center">--}}
                                                            {{--<h4>{{$ad->ad->platform->name}}</h4>--}}
                                                            {{--<h5>@if($ad->ad->morphable_type =="App\Post"){{ucfirst($ad->ad->advertisedItem->type_id)}}--}}
                                                                {{--| {{$ad->ad->advertisedItem->title}}@else{{str_limit($ad->ad->advertisedItem->name,50)}}@endif</h5>--}}
                                                        {{--</div>--}}
                                                    {{--</div>--}}
                                                    {{--<div class="panel-body text-center">--}}
                                                        {{--@if(file_exists(public_path("uploads/ion-television-ad-preview-".$ad->ad->id.".png")))--}}
                                                            {{--<div class="col-center" style="max-height:1750px; overflow:hidden">--}}
                                                            {{--<a href="{{ url("uploads/ion-television-ad-preview-".$ad->ad->id.".png") }}"--}}
                                                               {{--rel="adpreviews"--}}
                                                               {{--class="colorbox text-muted small"--}}
                                                               {{--title="Click to view Full Sized"--}}
                                                               {{--data-toggle="tooltip"> <img class="img-responsive"--}}
                                                                                           {{--src="{{ url("uploads/ion-television-ad-preview-".$ad->ad->id.".png") }}"/>--}}
                                                            {{--</a>--}}
                                                            {{--</div>--}}
                                                        {{--@else--}}
                                                            {{--<div class="pad text-center">--}}
                                                                {{--<h4 class="text-muted">Preview Not Available</h4>--}}
                                                            {{--</div>--}}
                                                        {{--@endif--}}
                                                    {{--</div>--}}
                                                {{--</div>--}}
                                            {{--</div>--}}
                                            {{--@endif--}}
                                        {{--@endif--}}
                                    {{--@endforeach--}}
                                </div>
                            </div>
                            <br
                        </div>
                        <div class="box-footer hidden-print">
                            <div class="row">
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

                        </div>
                        <div class="row text-center visible-print">
                            <small>Copyright &copy; {{date('Y')}} ION Media Networks. All Rights Reserved.</small>
                        </div>
                    </div>
                </div>

            </div>
        </section>
    </div>

@stop
@section('footer_js')

    <script>
        $(document).ready(function () {

            $('.print').click(function () {
                var content = $('.content');
                content.append('<html><head><title>{{$campaign->name}}</title>' +
                        '<link rel="stylesheet" type="text/css" href="{{elixir('css/admin.css')}}">' +
                        '<link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">' +
                        '</head><body>');
                printHTML(content.html());
            });
        });

        function printHTML(htmlString) {
            var newIframe = document.createElement('iframe');
            newIframe.width = '1px';
            newIframe.height = '1px';
            newIframe.src = 'about:blank';

            // for IE wait for the IFrame to load so we can access contentWindow.document.body
            newIframe.onload = function () {
                var script_tag = newIframe.contentWindow.document.createElement("script");
                script_tag.type = "text/javascript";
                var script = newIframe.contentWindow.document.createTextNode('function Print(){ window.focus(); window.print(); }');
                script_tag.appendChild(script);

                newIframe.contentWindow.document.body.innerHTML = htmlString;
                newIframe.contentWindow.document.body.appendChild(script_tag);

                // for chrome, a timeout for loading large amounts of content
                setTimeout(function () {
                    newIframe.contentWindow.Print();
                    newIframe.contentWindow.document.body.removeChild(script_tag);
                    newIframe.parentElement.removeChild(newIframe);
                }, 200);
            };
            document.body.appendChild(newIframe);
        }
    </script>
@stop