@extends("app")
@section("content")

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1 class="pull-left"><i class="fa fa-exclamation-triangle"></i> @if($expired) Expired @endif Rescan Alerts</h1>
            <div class="pull-right">
                <div class="btn-group">
                    <a href="{{ route('rescan-alerts.create') }}"
                       class="btn btn-success"><i class="fa fa-pencil-square"></i> Create New</a>

                    @if($expired)
                        <a href="{{url("rescan-alerts") }}" class="btn btn-default">
                           <i class="fa fa-eye"></i>&nbsp;Scheduled Rescan Alerts</a>
                    @else
                        <a href="{{ url("rescan-alerts/expired/list") }}"
                           class="btn btn-default text-muted"
                          > <i class="fa fa-eye-slash"></i>&nbsp;Expired Rescan Alerts</a>
                    @endif
                </div>
            </div>
        </section>

        <hr class="clearfix"/>
        <section class="content">
            <div class="box box-primary pad">
                <div class="box-body">
                    <table class="tablesorter table no-margin table-striped table-bordered table-hover table-condensed">
                        <thead>
                        <tr>
                            <th>Active</th>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Running</th>
                            <th>Slug</th>
                            <th>Options</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($items as $item)
                            <tr class="@if(!$item->active) text-muted @endif">
                                <td class="text-center">@if($item->active)
                                        <i class="fa fa-circle text-success"></i> @else
                                        <i class="fa fa-ban text-danger"></i> @endif</td>
                                <td>{{ $item->id }}</td>
                                <td>{{ $item->title }}
                                    <a href="{{ url('rescan-alerts/preview/'.$item->id) }}"
                                       class="pull-right btn btn-xs btn-default"
                                       target="_blank"><i class="fa fa-eye"></i> View</a>
                                </td>
                                <td>
                                   <span class="badge"><i class="fa fa-calendar"></i> {{  Carbon::parse($item->starts_at)->toDayDateTimeString()}}</span> to
                                    <span class="badge"><i class="fa fa-calendar"></i> {{Carbon::parse($item->ends_at)->toDayDateTimeString()}}</span>
                                </td>
                                <td>{{ $item->path }}</td>
                                <td>
                                    <a href="{{ route('rescan-alerts.edit', $item->id) }}"
                                       title="Edit: {{ $item->title }}"
                                       data-toggle="tooltip"
                                       style="float:left;margin-right:5px;"><i class="fa fa-pencil-square fa-2x"></i></a>

                                    {!! Form::open(['id' =>'delete-form-'.$item->id,'method' => 'DELETE','route' => ['rescan-alerts.destroy', $item->id] , 'style'=>'float:left' ]) !!}
                                    <a href="javascript:void(0);"
                                       title="Delete: {{ $item->title }}"
                                       data-toggle="tooltip"
                                       onClick="if(confirm('Are you sure you want to delete this Gleam Rescan Alert?')){ $('#delete-form-{{$item->id}}').submit()}else{ return false;}"><i
                                                class="fa fa-trash text-danger fa-2x"></i></a>
                                    {!! Form::close() !!}

                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </section><!-- /.content -->
    </div><!-- /.content-wrapper -->

@stop