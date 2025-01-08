@extends("app")
@section("header")
    <style>
        .fc-event {
            font-size: 1.3em;
            font-weight: 600px;
        }

        .Mobile .fc-content:after {
            content: "\f10b";
            font-family: FontAwesome;
            padding-left: 5px;
        }

        .Website .fc-content:after {
            content: "\f0ac";
            font-family: FontAwesome;
            padding-left: 5px;
        }

        .products-list > .item {
            padding: 0px;
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
                    Expired&nbsp;
                @endif
                Campaigns</h1>
            <div class="pull-right">
                <div class="btn-group pull-right">
                    <a href="{{ URL::previous() }}" class="btn btn-primary"><i class="fa fa-undo"></i> Back</a>
                    @if(!stristr(Request::path(),Auth::user()->username))
                        <a href="{{ route('campaigns.show',Auth::user()->username) }}"
                           class="btn btn-default"><i class="fa fa-user"></i>&nbsp;My Campaigns</a>
                    @else
                        <a href="{{ route('campaigns.index') }}" class="btn btn-info"><i class="fa fa-users"></i>&nbsp;All
                            Campaigns</a>
                    @endif

                    @if($expired)
                        <a href="{{ route("campaigns.index") }}" class="btn btn-default"><i class="fa fa-eye"></i>&nbsp;Active</a>
                    @else
                        <a href="{{ url("campaigns/expired/list") }}"
                           class="btn btn-default"><i class="fa fa-eye-slash"></i>&nbsp;Expired</a>
                    @endif
                    <a href="{{ route('campaigns.create') }}" class="btn btn-success"><i class="fa fa-plus"></i>&nbsp;New</a>
                    <a href="#" class="export btn btn-primary"><i class="fa fa-file-text-o tippy"
                                                                  title="Export to CSV"
                                                                  ></i></a>
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
                        <table class="tablesorter table no-margin table-bordered table-hover table-condensed"
                               @if(!$expired)
                               data-scroll-y="70vh"
                               data-scroll-collapse="true"
                               data-paging="false"
                               data-col-reorder="true"
                               @endif >
                            <thead>
                            <tr>
                                {{--<th style="width:8px;">ID</th>--}}
                                <th><i class="fa fa-calendar"></i> Starts</th>
                                <th><i class="fa fa-calendar"></i> Ends</th>
                                <th>Status</th>
                                <th>Program</th>
                                <th>Logo</th>
                                <th>Client</th>
                                <th>Contact</th>
                                <th># of Items</th>
                                <th>Report</th>

                                <th><i class="fa fa-paperclip"></i></th>
                                <th>Options</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($items as $item)
                                <tr class="@if($item->approved) bg-success @elseif($item->canceled) bg-danger @elseif ($item->pendingCreatives) bg-warning @else bg-info @endif ">
                                    {{--<td class="small">{{$item->id}}</td>--}}
                                    <td>
                                        <button class="btn btn-sm btn-block">
                                            <i class="fa fa-calendar"></i> {{ Carbon::parse(date('m/d/Y g:i A', strtotime($item->starts_at)))->format('m/d/y') }}
                                            @if(Carbon::now()->gte(Carbon::parse(date('m/d/Y g:i A', strtotime($item->starts_at)))) && !$item->expired )
                                                @if($item->approved)
                                                <span class="label label-success ">LIVE</span>
                                                    @elseif($item->canceled)
                                                    <span class="label label-danger">CANCELED <i class="fa fa-ban"></i></span>
                                                    @else
                                                    <span class="label label-danger">NEEDS APPROVAL <i class="fa fa-exclamation-triangle"></i></span>
                                                    @endif
                                            @endif
                                        </button>
                                    </td>
                                    <td>
                                        <button class="btn btn-sm btn-block">
                                            <i class="fa fa-calendar"></i> {{ Carbon::parse(date('m/d/Y g:i A', strtotime($item->ends_at)))->format('m/d/y') }}
                                        </button>
                                    </td>
                                    <td class="text-center">
                                        @if($item->status )
                                            @if($item->status->status_id =='approved')
                                                <span class="label label-success"><i class="fa fa-check"></i>
                                                    @elseif($item->status->status_id =='canceled')
                                                        <span class="label label-danger"><i class="fa fa-ban"></i>
                                                            @elseif($item->status->status_id =='pending-client-approval')
                                                                <span class="label label-info"><i class="fa fa-clock-o"></i>
                                                                    @else
                                                                        <span class="label label-warning"><i class="fa fa-user-circle"></i>
                                                                            @endif
                                                                            @if($item->approver)
                                                                                <small>Approved By: <strong>{{$item->approver->fullName}}</strong></small>
                                                                            @else
                                                                                {{$item->status->statusType->name}}
                                                                            @endif
                                        </span>
                                            @endif
                                    </td>
                                    <td class="small">{{$item->name}}</td>
                                    @if($item->sponsor)
                                        <td class="bg-active text-center" @if($item->sponsor->color) style="background-color:{{$item->sponsor->color}}" @endif>
                                            @if($item->sponsor->logo)
                                                <img style="max-height: 60px; width: auto; max-width:150px"
                                                     src="{{ $item->sponsor->logo->url }}"/>
                                            @endif
                                        </td>
                                        <td class="small">
                                                <a href="{{ route('sponsors.edit', $item->sponsor->id) }}">
                                                    <h5 class="no-margin">{{$item->sponsor->name}}</h5>
                                                    <h7 class="no-margin text-muted">{{str_limit($item->sponsor->url,50)}}</h7>
                                                </a>
                                        </td>
                                    @else
                                        <td colspan="2">
                                            <small class="text-danger">No Sponsor Attached</small>
                                        </td>
                                    @endif
                                    <td>
                                        <a href="{{route('campaigns.show',$item->owner->username)}}">{{$item->owner->fullName}}</a>
                                    </td>
                                    <td class="text-center"><span class="badge">{{$item->advertisements->count()}}</span></td>
                                    <td class="text-center">
                                        @if($item->advertisements->count())
                                            <a href="{{url('campaigns/report/'.$item->id)}}"><i
                                                        class="fa  fa-clipboard fa-2x"
                                                        title="View Campaign Report"></i></a>
                                        @else
                                            N/A
                                        @endif
                                    </td>

                                    <td>@if(!$item->images->isEmpty())<i class="fa fa-paperclip fa-lg fa-fw tippy" title="Campaign Has attachments"></i>@endif
                                    </td>
                                    <td>
                                        <a href="{{ route('campaigns.edit', $item->id) }}"
                                           title='Edit: {{ $item->name }} <i class=" fa fa-pencil-square"></i>' ><i class="fa fa-pencil-square text-info fa-2x"></i></a>
                                        <a href="{{ url('campaigns/remove/'.$item->id) }}"
                                           title='Remove: {{ $item->name }} <i class=" fa fa-trash text-danger fa-2x"></i>'
                                                   class="tippy"
                                           onClick="return confirm('Are you sure you want to remove this campaign?');"><i
                                                    class="fa fa-trash fa-2x text-danger "></i></a>
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

            // Javascript to enable link to tab
//            var url = document.location.toString();
//            if (url.match('#')) {
//                $('.nav-tabs a[href=#' + url.split('#')[1] + ']').tab('show');
//            }

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
                    url: "/campaigns/show/",
                    data: {start_date: start_date, end_date: end_date},

                    success: function (data) {

                        var results = JSON.parse(data);
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
                        @if($item->status && $item->sponsor)
                    {
                        id: '{{$item->id}}',
                        title: decodeHtmlEntity('{{$item->sponsor->name.' ('.$item->status->statusType->name.')'}}'),
                        start: '{{Carbon::parse($item->starts_at)->toIso8601String()}}',
                        end: '{{Carbon::parse($item->ends_at)->toIso8601String()}}',
                        color: '{{($item->status->status_id == 'approved')? '#00a65a' :(($item->status->status_id == 'canceled')? '#dd4b39': '#f39c12') }}',
                        className: ['{{preg_replace("/[^A-Za-z0-9]/", '',$item->sponsor->name) }}'],

                        editable: 'true',
                        url: '{{ route('campaigns.edit', $item->id) }}',
                        displayEventTime: true
                    },
                    @endif
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

            });

            $('.export').on('click', function () {
                export_results();

            });
        });

        function export_results() {

            var start_date = moment().subtract(14,'days').format('M-D-YYYY');
            var end_date = moment().format('M-D-YYYY');
            window.location = "/campaigns/export/null/xls/" + start_date + "/" + end_date;
        }

        function updateDates(id, start_date, end_date) {
            // console.log("id:" + id + " start:" + start_date + " End:" + end_date);
            $.ajax({
                method: "POST",
                url: "/campaigns/dates/" + id,
                data: {start_date: start_date, end_date: end_date},

            }).success(function (data) {
                location.reload();
            });
        }
    </script>
@stop


