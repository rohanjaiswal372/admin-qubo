@extends("app")
@section("content")

        <!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>Grid Placements</h1>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary pad">
                    <div class="box-header with-border">
                        <h3 class="box-title">Edit Placement: {{ ($item->title) ? $item->title : $item->name}}</h3>
                        <div class="box-tools pull-right">
                        </div>
                    </div>
                    <div class="box-body">

                        <h4>Schedule a new Grid</h4>

                        {!! Form::open(array('route' => array('grid-placements.store'), 'method' => 'POST')) !!}

                        <p>The grid position is used to place 2 or more grids on the same page.</p>
                        <div class="form-group col-md-8">
                            <table id="new-grid" class="table table-striped">
                                <tr>
                                    <th>Position</th>
                                    <th>Grid</th>
                                    <th>Starts At</th>
                                </tr>
                                <tr class="new-slide-row">
                                    <td>{!! Form::select('schedule[sort_order]', array_combine(range(0,4),range(1,5)), Input::old('sort_order')) !!}</td>
                                    <td>
                                        <input type="hidden" name="schedule[morphable_id]" value="{{ $item->id }}"/>
                                        <input type="hidden"
                                               name="schedule[morphable_type]"
                                               value="{{ strtolower(str_replace("App\\","",get_class($item))) }}"/>

                                        <select name="schedule[grid_id]"
                                                class="pod_selector form-control select2"
                                                data-live-search="true">
                                            <option value="" image_url="">Select a Grid</option>
                                            @foreach( $grids as $grid)
                                                <option value="{{$grid->id}}"
                                                        image_url="/images/grid-layouts/{{ $grid->layout->path }}.jpg}">{{$grid->title}}</option>
                                            @endforeach
                                        </select> <br/> <img class="grid_image"
                                                             style="width:200px;display:none;"
                                                             src=""/>

                                    </td>
                                    <td>{!! Form::text('schedule[starts_at]', '', ['class' => 'form-control datepicker', 'placeholder' => 'Click to set the start date']) !!}</td>
                                </tr>
                            </table>
                            {!! Form::submit('Save', ['class' => 'btn btn-primary','style'=>'margin-left:10px;']) !!}
                        </div>

                        {!! Form::close() !!}

                    </div>
                </div>
            </div>

            <div class="col-md-12">
                <div class="box box-primary pad">
                    <div class="box-header with-border">
                        <h3 class="box-title">Grid Schedule</h3>
                        <div class="box-tools pull-right">
                        </div>
                    </div>
                    <div class="box-body">

                        @if(count($indexes))
                            @foreach($indexes as $index)
                                <h4>Grid: {{ $index+1 }}</h4>
                                <table class="table no-margin table-condensed table-stripped table-responsive col-md-8">
                                    <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Name</th>
                                        <th>Layout</th>
                                        <th>Options</th>
                                    </tr>
                                    </thead>
                                    <tbody>

                                    @foreach($dates[$index] as $date)
                                       <? $grid = $item->grid($index, $date); ?>
                                        <tr>
                                            <td>
                                                {{ Carbon::parse($date)->format('d/m/Y') }}
                                            </td>
                                            <td>
                                                {{ $grid->title }}
                                            </td>
                                            <td>
                                                {{ $grid->layout->title }}<br/>
                                                <img src="/images/grid-layouts/{{ $grid->layout->path }}.jpg"/>
                                            </td>
                                            <td>
                                                <a class="btn btn-primary"
                                                   href="{{ route('pods.show', $grid->id) }}"><i class="fa fa-pencil"></i></a>
                                                <a class="btn btn-primary"
                                                   href="{{ URL::to("grid-placements/remove/".$grid->schedule_id) }}"><i
                                                            class="fa fa-times"></i></a>
                                            </td>
                                        </tr>
                                    @endforeach

                                    </tbody>
                                </table>
                            @endforeach
                        @else
                            No Grid has been scheduled
                        @endif
                        {!! Form::close() !!}
                    </div>
    </section><!-- /.content -->
</div><!-- /.content-wrapper -->

@stop


