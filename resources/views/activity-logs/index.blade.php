@extends("app")
@section("content")

    <style>
        .sf-dump {
            z-index: 0 !important;
        }
    </style>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1 class="pull-left" style="margin-bottom:20px;">Activity Logs</h1>
            {{--<div class="pull-right col-lg-2">--}}
                {{--<div class="form-group">--}}
                    {{--<div class="input-group search">--}}
                        {{--<div class="input-group-addon">--}}
                            {{--<i class="fa fa-search"></i>--}}
                        {{--</div>--}}
                        {{--<input class="search form-control pull-right" placeholder="search logs"/>--}}
                    {{--</div>--}}
                {{--</div>--}}
            {{--</div>--}}
            <div class="pull-right col-lg-2">
                <div class="form-group">
                    <div class="input-group date">
                        <div class="input-group-addon">
                            <i class="fa fa-calendar"></i>
                        </div>
                        <input value="{{ $date->format('m/d/Y')}}"
                               class="form-control pull-right"
                               id="datepicker"
                               type="text">
                    </div>
                    <!-- /.input group -->
                </div>

            </div>
        </section>

        <hr class="clearfix"/>
        <section class="content">
            <div class="box box-primary pad">
                <div class="box-body">

                    <!-- -->
                    <div class="col-md-12" id="activityList">
                        <!-- The time line -->
                        <ul class="timeline list">
                        @if($grouped_logs->count())
                            @foreach($grouped_logs as $date => $activities)
                                <!-- timeline time label -->
                                    <li class="time-label">
										  <span class="bg-blue">
										  {{ Carbon::parse($date)->format("D, M j") }}
										  </span>
                                    </li>
                                    @foreach($activities as $activity)
                                        @if($activity->user)
                                            <li>
                                                @if($activity->action == "create")
                                                    <i class="fa fa-plus bg-green"></i>
                                                @elseif($activity->action == "update")
                                                    <i class="fa fa-pencil bg-blue"></i>
                                                @elseif($activity->action == "delete")
                                                    <i class="fa fa-trash bg-red"></i>
                                                @endif
                                                <div class="timeline-item" style="background:#ccc">
                                                    <span class="time"><i class="fa fa-clock-o"></i> {{$activity->created_at->diffForHumans()}}</span>
                                                    <h3 class="timeline-header">
                                                        <a  href="{{route('users.edit',$activity->user->username)}}" class="activityLog_username">{{$activity->user->full_name}}</a>
                                                        has <strong class="activityLog_action">{{$activity->action."d"}}</strong> {{$object_labels[$activity->morphable_type]}}
                                                        @if(in_array($activity->action,["update","create"]) && $activity->morphable)
                                                         <a href="{{$activity->url}}"><i class="fa text-info fa-pencil-square"></i></a>
                                                        @endif
                                                    </h3>
                                                    <div class="timeline-body">
                                                        <div class="row">
                                                            @if(in_array($activity->action,["update","delete"]))

                                                                <div class="col-md-6">
                                                                    <h4>Original Content</h4>
                                                                    @if(count($activity->before))
                                                                        <table class="table no-margin table-hover table-condensed">
                                                                            @foreach($activity->before as $property => $value)
                                                                                @if(!in_array($property,["updated_at","created_at"]))
                                                                                    <tr>
                                                                                        <th class="col-lg-2">{{ strtoupper(str_replace("_"," ",$property))}}</th>
                                                                                        <td>{!! dump($value) !!}</td>
                                                                                    </tr>
                                                                                @endif
                                                                            @endforeach
                                                                        </table>
                                                                    @else
                                                                        <center>No data to display</center>
                                                                    @endif
                                                                </div>
                                                            @endif
                                                            @if(in_array($activity->action,["update","create"]))
                                                                <div class="col-md-6">
                                                                    <h4>Final Content</h4>
                                                                    @if(count($activity->before))
                                                                        <table class="table no-margin  table-hover table-condensed">
                                                                            @foreach($activity->after as $property => $value)
                                                                                @if(!in_array($property,["updated_at","created_at"]))
                                                                                    <tr>
                                                                                        <th class="col-lg-2">{{ strtoupper(str_replace("_"," ",$property))}}</th>
                                                                                        <td>{!! dump($value) !!}</td>
                                                                                    </tr>
                                                                                @endif
                                                                            @endforeach
                                                                        </table>
                                                                    @else
                                                                        <center>No data to display</center>
                                                                    @endif
                                                                </div>
                                                                <div class="col-md-6">
                                                                    @if($activity->morphable_type == 'App\Video' && $activity->morphable)
                                                                            <a href="{{$activity->url}}">
                                                                            <h4>
                                                                                <strong>Title:</strong> {!!  $activity->morphable->brightcove()->name !!}
                                                                            </h4>
                                                                                <img class="img-responsive"
                                                                                     src="{{$activity->morphable->still()}}"/>
                                                                            </a>

                                                                        @elseif($activity->morphable_type == 'App\Image' && $activity->morphable)
                                                                            <h4>{{$activity->morphable['type_id']}}</h4>
                                                                            <a href="{{$activity->url}}">
                                                                                    <img class="img-responsive col-md-4"
                                                                                         src="{{image($activity->morphable->url)}}"/>
                                                                            </a>
                                                                            @endif
                                                                </div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    {{--
                                                    <div class="timeline-footer">
                                                      <a class="btn btn-primary btn-xs">Read more</a>
                                                      <a class="btn btn-danger btn-xs">Delete</a>
                                                    </div>
                                                    --}}

                                                </div>
                                            </li>
                                        @endif
                                    @endforeach
                                @endforeach
                            @else

                                <center><h3>No activity logs available</h3></center>

                        @endif
                        <!-- -->

                    </div>
                </div>
        </section>
    </div>
@endsection

@section('footer_js')
    <script>
        $(document).ready(function () {

            //Date picker
            $('#datepicker').datepicker({
                autoclose: true
            }).change(function (data) {
                var date_parts = $("#datepicker").val().split("/");
                window.location = "/activity-logs/" + date_parts[2] + "-" + date_parts[0] + "-" + date_parts[1];
            });
        });
    </script>

@endsection