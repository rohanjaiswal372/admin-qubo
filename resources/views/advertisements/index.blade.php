@extends("app")
@section("header")
    <style>
        .fc-event {
            font-size: 1.3em;
            font-weight: 600px;
        }
        .Mobile .fc-content:after{
            content: "\f10b";
            font-family: FontAwesome;
            padding-left:5px;
        }
        .Website .fc-content:after{
            content: "\f0ac";
            font-family: FontAwesome;
            padding-left:5px;
        }
    </style>
    @stop
    @section("content")

            <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1 class="pull-left">
                @if($expired)
                    Expired
                @endif
                Advertisements</h1>
            <div class="pull-right">
                <div class="btn-group pull-right">
                    <a href="{{ URL::previous() }}" class="btn btn-primary"><i
                                class="fa fa-undo"></i> Back</a>
                    <a href="{{ route('advertisements.create') }}" class="btn btn-success"><i class="fa fa-plus"></i>&nbsp;New
                        Ad</a>
                    @if($expired)
                        <a href="{{ URL::to("advertisements") }}" class="btn"
                           style="background:darkgrey;color:white;"><i
                                    class="fa fa-eye"></i>&nbsp;Scheduled Ads</a>
                    @else
                        <a href="{{ URL::to("advertisements/expired/list") }}" class="btn"
                           style="background:darkgrey;color:white;"><i class="fa fa-eye-slash"></i>&nbsp;Expired Ads</a>
                    @endif


                    <a class="btn btn-default" id="daterange">
                        <i class="fa fa-calendar"></i>
                        <span></span> <b class="caret"></b>
                    </a>
                    <a href="#" class="export btn btn-primary"><i
                                class="fa fa-file-text-o"
                                title="Export Ads  list to CSV"
                                data-toggle="tooltip"></i></a>


                </div>

            </div>
        </section>

        <hr class="clearfix"/>

        <section class="content">
            <ul class="nav nav-tabs" role="tablist">
                <li role="presentation" class="active"><a href="#table-tab" aria-controls="table-tab" role="tab"
                                                          data-toggle="tab"><i class="fa fa-list"></i> List</a></li>
                <li role="presentation"><a href="#calendar-tab" aria-controls="calendar-tab" role="tab"
                                           data-toggle="tab"><i class="fa fa-calendar"></i> Calendar</a></li>

            </ul>

            <div class="tab-content">
                <div role="tabpanel" class="box tab-pane fade in active" id="table-tab">
                    <div class="box-body">
                        <table class="datatable table no-margin table-bordered table-hover table-condensed" data-scroll-y="70vh" data-scroll-collapse="true" data-paging="false" data-col-reorder="true">
                            <thead>
                            <tr>
                                <th>Platform</th>
                                <th>Campaign</th>
                                <th>Sponsoring</th>
                                <th>Sponsor/URL</th>
                                <th>Image</th>
                                <th>Type</th>
                                <th>Status</th>
                                <th><i class="fa fa-calendar"></i> Dates</th>
                                <th>Options</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($items as $item)
                                <tr class="@if(!$item->active) danger @endif" data-href="{{ route('advertisements.edit', $item->id) }}">
                                    <td>
                                            @if($item->category->platform->name == "Website")<i class="fa fa-globe"></i>
                                            @elseif($item->category->platform->name == "Mobile App")<i class="fa fa-mobile"></i>
                                            @elseif($item->category->platform->name == "iPad App")<i class="fa fa-tablet"></i>
                                            @endif
                                        {{ $item->category->platform->name }}
                                    </td>
                                    <td>@if(!is_null($item->campaign))<small><a href="{{ route('campaigns.edit',$item->campaign->campaign['id']) }}" >{{ ($item->campaign)? $item->campaign->campaign['name'] : ""}} </a></small>@endif</td>
                                    <td class="small">
                                        {{ $item->category->name.": " }}
                                        @if($item->morphable && $item->morphable->name)

                                                {{ $item->morphable->name }}

                                        @elseif($item->morphable && $item->morphable->title)

                                                {{ $item->morphable->title }}

                                        @elseif(get_class($item->morphable) == "App\Program")
                                            @if($item->morphable && $item->morphable->show)
                                                @if($item->morphable->show->type_id == "show")
                                                  Show {{ $item->morphable->show->name.", EP ".$item->morphable->episode->episode_number." ".$item->morphable->episode->name }}
                                                    <a href="{{$item->morphable->show->url}}" class="btn btn-default btn-xs"><i class="fa fa-eye"></i></a>
                                                @elseif($item->morphable->show->type_id == "movie")
                                                    Movie: {{ $item->morphable->show->name }}
                                                @endif
                                                ({!! $item->morphable->date()." ".$item->morphable->time() !!})
                                            @else
                                                Expired
                                            @endif
                                        @elseif(!$item->morphable)
                                            <span class="text text-danger">This Ad has not been associated with any item</span>
                                        @endif

                                        @if(!is_null($item->web_preview_url))
                                            <a href="{{$item->web_preview_url}}" class="@if(strtolower($item->platform) == 'mobile app' || strtolower($item->platform) == 'ipad app') colorbox @endif pull-right btn btn-xs btn-default" target="_blank"><i class="fa fa-eye"></i> View</a>
                                        @endif

                                    </td>

                                    <td>@if($item->sponsor)<a data-toggle="tooltip" title="Edit Sponsor"
                                           href="{{ route('sponsors.edit', $item->sponsor->id) }}">{{ $item->sponsor->name }}<br><small class="text-muted">{{$item->sponsor->url}}</small></a>
                                            @else
                                            <span class="text-danger">There is no sponsor affiliated with this ad</span>
                                        @endif
                                    </td>
                                    <td class="text-center bg-primary" @if($item->sponsor->color)style="background-color:{{$item->sponsor->color}}" @endif ><a
                                                href="{{ route('advertisements.edit', $item->id) }}"
                                                title="Edit: {{ $item->sponsor->name }}"
                                                data-toggle="tooltip"> @if($item->image['url'])<img
                                                    src="{{ config('filesystems.disks.rackspace.public_url_ssl')."/".$item->image['url'] }}"
                                                    style="max-height: 60px; width: auto;"/>
                                            @elseif($item->video)
                                                <img
                                                        src="{{$item->video->thumbnail() }}"
                                                        style="max-width: 100px; height: auto;"/>
                                            @endif</a></td>
                                    <td class="text-center small">{{ ($item->type) ? $item->type->name : "Default" }}</td>
                                    <td class="text-center"> @if($item->active)
                                            <i class="fa fa-circle text-success"></i>
                                        @else
                                            <i class="fa fa-ban fa-2x text-danger"></i>
                                        @endif
                                    </td>
                                    <td @if($item->running && !$expired) class="success" @endif>
                                        <small>
                                        @if( is_null($item->starts_at) )
                                            N/A
                                        @else
                                            {{ Carbon::parse(date('m/d/Y g:i A', strtotime($item->starts_at)))->format('m/d/y') }}
                                            - {{ Carbon::parse(date('m/d/Y g:i A', strtotime($item->ends_at)))->format('m/d/y') }}
                                        @endif
                                        </small>
                                    </td>
                                    <td>
                                        <div class="btn group">
                                            <a href="{{ route('advertisements.edit', $item->id) }}"
                                               title="Edit: {{ $item->id }}" data-toggle="tooltip"><i
                                                        class="fa fa-pencil-square fa-2x"></i></a>
                                            <a href="{{ URL::to('advertisements/duplicate', $item->id) }}"
                                               title="Duplicate: {{ $item->id }}" data-toggle="tooltip"><i
                                                        class="fa fa-files-o fa-2x text-info"></i></a>
                                            <a href="{{ URL::to('advertisements/remove/'.$item->id) }}"
                                               title="Remove: {{ $item->id }}"
                                               data-toggle="tooltip"
                                               onClick="return confirm('Are you sure you want to remove this Ad?');"><i
                                                        class="fa fa-trash fa-2x text-danger"></i></a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div><!-- end Box -->
                <div role="tabpanel" class="box tab-pane fade" id="calendar-tab">
                    <div class="box-header">
                        <label class="toggleWebsite btn btn-primary active"> <i class="fa fa-globe"></i> Website</label>
                        <label class="toggleMobile btn btn-primary active"> <i class="fa fa-mobile"></i> Mobile</label>
                        <div class="btn-group" data-toggle="buttons">

                            @foreach($sponsors as $sponsor)

                                <label class="btn btn-default active toggle-ad"
                                       data-toggle="{{ preg_replace("/[^A-Za-z0-9]/", '', $sponsor)}}">{{$sponsor}}
                                    <input type="checkbox" autocomplete="off" checked>
                                </label>

                            @endforeach
                        </div>
                        <a class="toggleAll btn btn-primary"><i class="fa fa-eye-slash"></i> Toggle All</a>
                    </div>
                    <div class="box-body">
                        <div class="calendar" id="calendar">
                        </div>

                    </div>
                </div>
            </div><!-- tab content -->
        </section><!-- /.content -->
    </div><!-- /.content-wrapper -->

@stop

@section('footer_js')
    <script>
        $(document).ready(function () {
            <?php $faker = Faker\Factory::create(); ?>
             $('.toggleWebsite').on('click', function (e) {
                $(this).toggleClass('btn-primary');
                e.preventDefault();
                $('.Website').toggle();
            });
            $('.toggleMobile').on('click', function (e) {
                $(this).toggleClass('btn-primary');
                e.preventDefault();
                $('.Mobile').toggle();
            });
            $('.toggleAll').on('click', function () {

                $(this).toggleClass('btn-primary');
                $(".fc-event-container a").toggle();
            });

            $('.toggle-ad').on('click', function (e) {

                var toggleClass = $(this).data('toggle');

                $("." + toggleClass).toggle();

            });

            $('table.datatable').DataTable({
                searchHighlight: true,  processing: true, stateSave:false, pageLength:15
                ,"lengthMenu": [ [10,12,15, 25, 50, -1], [10,12,15, 25, 50, "All"] ],  paging: true,  colReorder:true,
                "order": [[1, "desc"]],
                "responsive": true,
                "drawCallback": function (settings) {
                    var api = this.api(), data;
                    var rows = api.rows({page: 'current'}).nodes();
                    var last = null;


                    api.column(1, {page: 'current'}).data().each(function (group, i) {

                        if (last !== group && group != "") {
                            $(rows).eq(i).before(
                                    '<tr style="background: #cacaca; line-height:0.7"><td colspan="9"><strong>Campaign: '+ group + '</strong></td></tr>'
                            );
                            last = group;
                        }
                    })

                    api.column(1).visible(false);
                }
            });

            // Javascript to enable link to tab
            var url = document.location.toString();
            if (url.match('#')) {
                $('.nav-tabs a[href=#' + url.split('#')[1] + ']').tab('show');
            }

            $('.nav-tabs a').on('shown.bs.tab', function (e) {
                if (history.pushState) {
                    history.pushState(null, null, e.target.hash);
                } else {
                    window.location.hash = e.target.hash; //Polyfill for old browsers
                }
            });

            $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
                $('#calendar').fullCalendar('render');
            });
            var decodeHtmlEntity = function (str) {
                return str.replace(/&#(\d+);/g, function (match, dec) {
                    return String.fromCharCode(dec);
                });
            };

            function setDateRange(start_date, end_date) {
                $('#daterange span').html(start_date.format('MMMM D, YYYY') + ' - ' + end_date.format('MMMM D, YYYY'));

            }

            setDateRange(moment(), moment().add('1', 'year'));

            $('#daterange').daterangepicker({
                ranges: {
                    'This Year': [moment(), moment().add('1', 'years')],
                    'Next Year': [moment().add('1', 'years'), moment().add('2', 'years')],
                    'Last Year': [moment().subtract('1', 'year'), moment()]
                }
            }, setDateRange);

            $('#daterange').on('apply.daterangepicker', function (ev, picker) {

                var start_date = picker.startDate.format('YYYY-M-D');
                var end_date = picker.endDate.format('YYYY-M-D');
                $.ajax({
                    type: 'GET',
                    url: "/advertisements/show/",
                    data: {start_date: start_date, end_date: end_date},

                    success: function (data) {

                        var results = JSON.parse(data);
                        //console.log(results.results);
                        $('.content').html(results.results);
                        $('#calendar').fullCalendar('render');
                    }

                });
            });

            $('#calendar').fullCalendar({
                header: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'month,basicWeek, basicDay'
                },
                eventLimit: 10,
                events: [
                        @foreach($items as $item)
                    {
                        id: '{{$item->id}}',
                        title: decodeHtmlEntity('{{$item->sponsor->name}} @if(!$item->active) | (disabled)@endif'),
                        start: '{{Carbon::parse($item->starts_at)->toIso8601String()}}',
                        end: '{{Carbon::parse($item->ends_at)->toIso8601String()}}',
                        color: '{{($item->sponsor->color)? $item->sponsor->color : $faker->hexcolor }}',
                        className: ['{{$item->category->platform->name }}', '{{preg_replace("/[^A-Za-z0-9]/", '',$item->sponsor->name) }}'],
                        @if(!$item->active)textColor: '#ff0000', @endif
                    editable: 'true',
                        url: '{{ route('advertisements.edit', $item->id) }}',
                        displayEventTime: true
                    },
                    @endforeach
                ],
                eventDrop: function (event, delta, revertFunc, jsEvent, ui, view) {

                    var now = new Date();
                    var start_date = event.start.format();
                    var end_date = event.end.format();
                    var id = event.id;
                    if (moment(start_date).isBefore(now)) {
                        alert("Start Date cannot be set before today. ");
                        revertFunc();
                    }
                    else {
                        updateDates(id, start_date, end_date);
                    }

                },
                eventResize: function (event, delta, revertFunc, jsEvent, ui, view) {
                    var start_date = event.start.format();
                    var end_date = event.end.format();
                    var id = event.id;
                    if (!confirm("Change the ads duration?")) {
                        revertFunc();
                    }
                    else {
                        updateDates(id, start_date, end_date);
                    }

                },
                eventAfterRender: function(event,element,view){


                        $.each(event,function(e) {
                            //$(e).append("<i class='fa-mobile></i>");
                            console.log(event.className);

                        });

                    $.each('.Mobile',function(e){

                        $(this).html('test');
                    });

                }
            });

            $('.export').on('click', function () {
                export_results();

            });
        });

        function export_results() {
            var daterange = $("#daterange span").text();
            var dates = daterange.split("-");

            var start_date = moment(dates[0]).format('M-D-YYYY');
            var end_date = moment(dates[1]).format('M-D-YYYY');
            window.location = "/advertisements/export/csv/" + start_date + "/" + end_date;
        }

        function updateDates(id, start_date, end_date) {
            // console.log("id:" + id + " start:" + start_date + " End:" + end_date);
            $.ajax({
                method: "POST",
                url: "/advertisements/dates/" + id,
                data: {start_date: start_date, end_date: end_date},

            }).success(function (data) {
                location.reload();
            });
        }
    </script>
@stop


