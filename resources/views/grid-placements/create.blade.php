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
                            <h3 class="box-title">Create A New Grid Placement</h3>
                            <div class="box-tools pull-right">
                            </div>
                        </div>
                        <div class="box-body">

                            {!! Form::open(array('route' => array('grid-placements.store'), 'method' => 'POST')) !!}


                            <div class="form-group">
                                {!! Form::label('schedule[morphable_type]', 'Type: ') !!}
                                {!! Form::select('schedule[morphable_type]',["page"=>"Page","show"=>"Show","movie"=>"Movie"], Input::old('schedule[morphable_type]'), ['class' => 'form-control morphable_type_selector', 'placeholder' => 'Page Title']) !!}
                            </div>

                            <div class="form-group" id="morphable-id-selector-wrapper"></div>

                            <div class="form-group" style="width: 800px">
                                <table id="new-grid" class="table table-striped">
                                    <tr>
                                        <th>Position</th>
                                        <th>Grid</th>
                                        <th>Starts At</th>
                                    </tr>
                                    <tr class="new-slide-row">
                                        <td>{!! Form::select('schedule[sort_order]', array_combine(range(0,4),range(1,5)), Input::old('sort_order')) !!}</td>
                                        <td>

                                            <select name="schedule[grid_id]" class="pod_selector form-control select2"
                                                    data-live-search="true">
                                                <option value="" image_url="">Select a Grid</option>
                                                @foreach( $grids as $grid)
                                                    <option value="{{$grid->id}}"
                                                            image_url="/images/grid-layouts/{{ $grid->layout->path }}.jpg}">{{$grid->title}}</option>
                                                @endforeach
                                            </select>
                                            <br/>
                                            <img class="grid_image" style="width:200px;display:none;" src=""/>
                                        </td>
                                        <td>{!! Form::text('schedule[starts_at]', '', ['class' => 'form-control datepicker', 'placeholder' => 'Click to set the start date']) !!}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            @include('templates.partials.savebar')

        </section><!-- /.content -->
    </div><!-- /.content-wrapper -->
@stop
@section('footer_js')
    <script>
        $(document).ready(function () {
            $("body").on("change", ".morphable_type_selector", function () {
                getMorphableIdSelector($(this).val());
            });
            getMorphableIdSelector($(".morphable_type_selector").val());
        });
        function getMorphableIdSelector(type_id, item_id) {
            $("#morphable-id-selector-wrapper").html("Loading");
            $.get("/grid-placements/morphable-id-selector/" + type_id + "/" + item_id, function (response) {
                $("#morphable-id-selector-wrapper").html(response);
                $(".select2").select2();
            });
        }
    </script>
@stop
