@extends("app")
@section("content")
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="pull-left">
                <h3>Cast Member - Show:<span id="show_name">{{$show->name}}</span>
                    @if(!$show->active)
                        :
                        <span class="text-danger"> Inactive <i class="fa fa-ban"></i></span>
                    @endif
                    @if($show->logo)
                        @if(!strstr($show->logo->url, ":"))
                            <img class="img img-thumbnail" style="max-width:150px;background-color:{{($show->color or "#fff")}}"
                                 src="{{image($show->logo->url) }}"/>
                        @else
                            <img class="img img-thumbnail" style="background-color:{{$show->color}}"
                                 src="{{URL::to('proxy.php?type=image&url='.$show->logo->url) }}"/>
                        @endif
                    @endif
                </h3>

            </div>
            <div class="pull-right">
                <a href="{{ route('shows.edit', $show->id ) }}" class="btn btn-warning"><i class="fa fa-undo"></i> Back</a>
                <a href="{{ URL::to("shows/casts/create/".$show->id) }}"
                   class="pull-right btn btn-info"><i class="fa fa-pencil-square"></i> Create New</a>
            </div>
            </section>

            <!-- Main content -->
            <section class="content">
                <div class="row">
                    <div class="col-md-12">
                        <div class="box box-primary pad">
                            <div class="box-header with-border">
                                <div class="box-tools pull-right">
                                </div>
                            </div>
                            <div class="box-body">
                                <table class="tablesorter table no-margin table-striped table-hover table-bordered">
                                    <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Status</th>
                                        <th>Sort Order</th>
                                        <th>Picture</th>
                                        <th>Name</th>
                                        <th>Real Name</th>
                                        <th>Options</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($show->casts as $cast)
                                        <tr>
                                            <td>{{ $cast->id }}</td>
                                            <td class="text-center">@if($cast->active) <i class="fa fa-circle text-success"></i> @else <i class="fa fa-ban text-danger"></i> @endif</td>
                                            <td>{{ $cast->sort_order }}</td>
                                            <td>
                                                @if($cast->image)
                                                    <div class="col-md-2">
                                                        <h5>{{ucfirst($cast->image->type_id)}}</h5>
                                                    <img src="{{ $cast->image ? image($cast->image->url) : "https://placehold.it/320x240" }}"
                                                         class="img img-responsive"/>
                                                    </div>
                                            @endif
                                            @if($cast->pod_image)
                                                        <div class="col-md-2">
                                                            <h5>{{ucfirst($cast->pod_image->type_id)}}</h5>
                                                <img src="{{ $cast->pod_image ? image($cast->pod_image->url) : "https://placehold.it/320x240" }}"
                                                     class="img img-responsive"/>
                                                </div>
                                            @endif
                                            </td>
                                            <td><h3>{{ $cast->name }}</h3></td>
                                            <td>{{ $cast->real_name }}</td>
                                            <td>
                                                <a href="{{ route('casts.edit', $cast->id) }}"
                                                   title="Edit"
                                                   data-toggle="tooltip"><i class="fa fa-pencil-square fa-2x"></i></a>
                                                <a href="{{ URL::to('shows/casts/delete/'. $cast->id) }}" title="Delete" data-toggle="tooltip"  onclick="return confirm('Are you sure you want to remove this?');"><i class="fa fa-trash-o text-danger fa-2x"></i></a>
                                            </td>
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


