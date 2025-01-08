<section class="content">
    <ul class="nav nav-tabs" role="tablist">
        <li role="presentation" class="active"><a href="#table-tab" aria-controls="table-tab" role="tab"
                                                  data-toggle="tab"><i class="fa fa-list"></i> List</a></li>
        <li role="presentation"><a href="#calendar-tab" aria-controls="calendar-tab" role="tab"
                                   data-toggle="tab"><i class="fa fa-calendar"></i> Calendar</a></li>

    </ul>

    <div class="tab-content">
        <div role=tabpanel" class="box tab-pane fade in active" id="table-tab">
            <div class="box-body">
                <table class="tablesorter table no-margin table-striped table-bordered table-hover table-condensed">
                    <thead>
                    <tr>
                        <th>Platform</th>
                        <th>Sponsoring</th>
                        <th>Sponsor</th>
                        <th>Color</th>
                        <th>Image</th>
                        <th>Type</th>
                        <th>Status</th>
                        <th>Running</th>
                        <th>Options</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($items as $item)
                        <tr>
                            <td>
                                {{ $item->category->platform->name }}
                            </td>
                            <td>
                                {{ $item->category->name.": " }}
                                @if($item->morphable && $item->morphable->name)
                                    {{ $item->morphable->name }}
                                @elseif($item->morphable && $item->morphable->title)
                                    {{ $item->morphable->title }}
                                @elseif(get_class($item->morphable) == "App\Program")
                                    @if($item->morphable && $item->morphable->show)
                                        @if($item->morphable->show->type_id == "show")
                                            Show {{ $item->morphable->show->name.", EP ".$item->morphable->episode->episode_number." ".$item->morphable->episode->name }}
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
                            </td>
                            <td><a data-toggle="tooltip" title="Edit Sponsor"
                                   href="{{ route('sponsors.edit', $item->sponsor->id) }}">{{ $item->sponsor->name }}</a>
                            </td>
                            <td class="text-center">
                                <div class="label"
                                     style="background-color:{{$item->sponsor->color}}; min-width:50%;">
                                    &nbsp;</div>
                            </td>
                            <td class="text-center bg-primary"><a
                                        href="{{ route('advertisements.edit', $item->id) }}"
                                        title="Edit: {{ $item->sponsor->name }}"
                                        data-toggle="tooltip"> @if($item->image['url'])<img
                                            src="{{ config('filesystems.disks.rackspace.public_url').$item->image['url'] }}"
                                            style="max-width: 100px; height: auto;"/> @endif</a></td>
                            <td>{{ ($item->type) ? $item->type->name : "Default" }}</td>
                            <td> @if($item->active)
                                    <i class="fa fa-circle text-success"></i>
                                @else
                                    <i class="fa fa-ban text-danger"></i>
                                @endif
                            </td>
                            <td>
                                @if( is_null($item->starts_at) )
                                    N/A
                                @else
                                    {{ date('m/d/Y g:i A', strtotime($item->starts_at)) }}
                                    - {{ date('m/d/Y g:i A', strtotime($item->ends_at)) }}
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('advertisements.edit', $item->id) }}"
                                   title="Edit: {{ $item->id }}" data-toggle="tooltip"><i
                                            class="fa fa-pencil-square fa-2x"></i></a>
                                <a href="{{ URL::to('advertisements/remove/'.$item->id) }}"
                                   title="Remove: {{ $item->id }}"
                                   data-toggle="tooltip"
                                   onClick="return confirm('Are you sure you want to remove this Ad?');"><i
                                            class="fa fa-times fa-2x"></i></a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div><!-- end Box -->
        <div role=tabpanel" class="box tab-pane fade" id="calendar-tab">
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
<script>


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
    $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
        $('#calendar').fullCalendar('render');
    });
    var decodeHtmlEntity = function (str) {
        return str.replace(/&#(\d+);/g, function (match, dec) {
            return String.fromCharCode(dec);
        });
    };

    $('#calendar').fullCalendar({
        gotoDate : '{{Carbon::parse($items[0]->starts_at)->toIso8601String()}}',
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
                color: '{{($item->sponsor->color)? $item->sponsor->color : "#aaa" }}',
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

        }
    });

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