@extends("app")
@section('header')
    <style>
        .content-box h4 {
            color: white;
        }
    </style>
@stop
@section("content")

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="pull-left">
                <h3><i class="fa fa-th"></i> Grid Cells For {{ $grid->title }}</h3>
                <br clear="all"/>
                <p><strong>Layout:</strong> {{ $grid->layout->title }}</p>
            </div>
            <div class="pull-right">
                <a href="{{ route('grids.create') }}" class="btn btn-primary"><i class="fa fa-pencil-square"></i> Create
                    New</a>
            </div>
        </section>
        <hr class="clearfix"/>
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="box box-primary pad">
                        <div class="box-header with-border">
                            <div class="box-tools pull-right">

                            </div>
                        </div>

                        <div class="box-body">

                            <h3>Grid Display</h3>
                            @include('partials.grids.layout', ['grid' => $grid])

                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="box box-primary pad">
                        <div class="box-header with-border">
                            <h3 class="box-title">SCHEDULE PODS</h3>
                            <div class="box-tools pull-right">
                                <a id="add-more-slides"
                                   href="javascript:void(0)"
                                   style="float:right;margin-bottom:5px;"><i class="fa fa-plus text-success"></i> Add
                                    more</a>
                            </div>
                        </div>
                        <div class="box-body">
                            <div id="new-slide" style="display:none;">
                                <table>
                                    <tbody>
                                    <tr class="new-slide-row">
                                        <td>{!! Form::select('sort_order[]', array_combine(range(1,$grid->layout->number_of_pods),range(1,$grid->layout->number_of_pods)), Input::old('sort_order')) !!}</td>
                                        <td>
                                            <select name="cell_id[]"
                                                    class="pod_selector form-control"
                                                    data-live-search="true">
                                                <option value="" image_url="">Select a Pod</option>
                                                @foreach( $all_pods as $pod)
                                                    @if($pod->image && File::exists(image($pod->image->url)))
                                                        <option value="{{$pod->id}}"
                                                                image_url="@if(isset($pod->image)){{ image($pod->image->url) }}@endif">{{$pod->name}}</option>
                                                    @endif
                                                @endforeach
                                            </select> <br/> <img class="pod_image"
                                                                 style="width:200px;display:none;"
                                                                 src=""/>
                                        </td>
                                        <td>{!! Form::text('starts_at[]', '', ['class' => 'form-control datepicker', 'placeholder' => 'Click to set the start date']) !!}</td>
                                        <td valign="center">
                                            <a class="remove-new-slide"
                                               href="javascript:void(0)"><i class="fa fa-times-circle-o fa-2x"
                                                                            style="font-size:25px;"></i></a>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                            {!! Form::model($grid, array('route' => array('grids.add-pods', $grid->id), 'method' => 'PUT')) !!}

                            <table id="new-slides" class="table table-striped">
                                <tr>
                                    <th>Position</th>
                                    <th>Pod</th>
                                    <th>Starts At</th>
                                    <th>&nbsp;</th>
                                </tr>
                                <tr class="new-slide-row">
                                    <td>{!! Form::select('sort_order[]', array_combine(range(1,$grid->layout->number_of_pods),range(1,$grid->layout->number_of_pods)), Input::old('sort_order')) !!}</td>
                                    <td>
                                        <select name="cell_id[]"
                                                class="pod_selector form-control select2"
                                                data-live-search="true">
                                            <option value="" image_url="">Select a Pod</option>
                                            @foreach( $all_pods as $pod)
                                                    <option value="{{$pod->id}}"
                                                            image_url="@if(isset($pod->image)){{ image($pod->image->url) }}@endif">{{$pod->name}}</option>
                                            @endforeach
                                        </select> <br/> <img class="pod_image"
                                                             style="width:200px;display:none;"
                                                             src=""/>

                                    </td>
                                    <td>{!! Form::text('starts_at[]', '', ['class' => 'form-control datepicker', 'placeholder' => 'Click to set the start date']) !!}</td>
                                    <td valign="center">
                                        <a class="remove-new-slide"
                                           href="javascript:void(0)"><i class="fa fa-times-circle-o fa-2x"
                                                                        style="font-size:25px;"></i></a>
                                    </td>
                                </tr>
                            </table>
                            {!! Form::submit('Save', ['class' => 'btn btn-primary']) !!}
                        </div>
                        {!! Form::close() !!}
                        <hr/>
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="box box-primary pad">
                        <div class="box-header with-border">
                            <h3 class="box-title">Current Schedule</h3>
                            <div class="box-tools pull-right">
                                <a id="add-more-slides"
                                   href="javascript:void(0)"
                                   style="float:right;margin-bottom:5px;"><i class="fa fa-plus text-success"></i> Add
                                    more</a>
                            </div>
                        </div>
                        <div class="box-body">

                            @if(!empty($scheduled_pods))
                                @foreach ( $scheduled_pods as $date => $pods )

                                        <h3>{{ Carbon::parse($date)->format('M jS, Y') }}</h3>
                                        <table class="table table-striped table-hover">
                                            <thead>
                                            <tr>
                                                <?php $slot = 0; ?>
                                                @foreach ( $pods as $key => $pod )
                                                    @if($key <= $grid->layout->number_of_pods)
                                                        <?php $slot++; ?>
                                                        <th>Pod {{ $slot }}</th>
                                                    @endif
                                                @endforeach
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <tr>
                                                @foreach ( $pods as $key => $pod )

                                                    @if(!is_null($pod->image))
                                                    @if(File::exists(image($pod->image->url)) )


                                                    @if($key <= $grid->layout->number_of_pods)
                                                        <td>
                                                        {{ $pod->name}}
                                                            @if(isset($pod->image))
                                                                <?
                                                                list($width, $height, $type, $attr) = getimagesize(image($pod->image->url, true));
                                                                ?>
                                                                <img src="{{ image($pod->image->url) }}"
                                                                     alt="{{ $pod->title }}"
                                                                     style="width:{{ $width == 620 ? "400":"200"}}px;height: auto;">
                                                                <br/>
                                                            @endif
                                                            @if(isset($pod->id))

                                                                <a href="{{ URL::to('/pods/'.$pod->id."/edit") }}"><em
                                                                            class="fa fa-pencil"></em></a>
                                                                &nbsp;
                                                                <a href="{{ URL::to('/grids/remove-pod/'.$pod->schedule_id) }}"
                                                                   class="remove-link"><em class="fa fa-times"></em></a>
                                                                &nbsp;
                                                                &nbsp;
                                                            <!-- Schedule #{{ $pod->schedule_id}} -->

                                                            @endif
                                                        </td>
                                                    @endif
                                                    @endif
                                                    @endif
                                                @endforeach
                                            </tr>
                                            </tbody>
                                        </table>
                                @endforeach

                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </section><!-- /.content -->
    </div><!-- /.content-wrapper -->

@section("footer_js")
    <script>

        /*

        {{--{{  json_encode($all_pods->lists("name","image","id")->toArray()) }}--}}

        */

        $(document).ready(function () {
            $("#add-more-slides").click(function () {
                $('#new-slides tbody').append($('#new-slide').find("table tbody").html());
                $('.datepicker').datepicker();
                $("#new-slides .pod_selector").select2();
            });

            $("body").on("click", ".remove-new-slide", function () {
                $(this).parents(".new-slide-row").remove();
            });

            $("#new-slides .pod_selector").select2();

            $("body").on("change", ".pod_selector", function () {
                var image_url = $(this).children("option:selected").attr("image_url");
                $(this).siblings(".pod_image").attr("src", image_url).show();
            });

        });
    </script>
@stop



@stop


