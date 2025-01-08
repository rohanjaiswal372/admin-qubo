@extends("app")
@section("header")
    <style>
        .highlight {
            background-color: yellow;
        }
    </style>

@section("content")
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1 class="pull-left">Feedbacks</h1>
            <div class="pull-right">

                {!! Form::open(array('method'=>'GET', 'url' => 'audience-relations/feedbacks/search', 'id'=>'dates' ,'class'=>'form form-inline')) !!}
                <div class="input-group input-daterange">
                    <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                    {!!  Form::input('text', 'start_date','', ['class'=> 'form-control','id' => 'start_date']) !!}
                    <span class="input-group-addon">to</span>
                    {!! Form::input('text', 'end_date','', ['class'=> 'form-control','id' => 'end_date']) !!}
                    <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                </div>
                <div class="input-group">
                    <span class="input-group-addon">{!! Form::label('search', 'Search: ') !!}</span>
                    {!! Form::text('search', Input::old('search'), ['class' => 'form-control', 'id'=> 'search','placeholder' => '']) !!}

                    <span class="input-group-addon">{!! Form::label('num_results', '# of Results: ') !!}</span>
                    {!! Form::select('num_results', ["5"=>5, "10"=> 10, "25"=> 25, "50"=> 50, "100"=>100], "25", ['class' => 'form-control', 'id'=> 'num_results']) !!}
                </div>
                {!! Form::close() !!}
                <a href="#" class="export btn btn-primary pull-right"><i
                            class="fa fa-file-text-o"></i> Export CSV</a>
            </div>
        </section>

        <hr class="clearfix"/>

        <section class="content">
            <table class="table table-striped table-hover table-bordered">
                <thead>
                <tr>
                    <th>Date Sent</th>
                    <th>Subject</th>
                    <th>User Info</th>

                    <th>Market & Provider</th>
                    <th>Newsletter</th>
                    <th>Message</th>
                    <th>options</th>
                </tr>
                </thead>
                <tbody>
                @foreach($items as $item)
                    <tr>
                        <td>{{ $item->created_at->format('M d, Y') }}</td>
                        <td>
                            @if( isset($item->subject->name) )
                                {{ $item->subject->name }}
                            @else
                                N/A
                            @endif
                        </td>
                        <td><strong>Full Name:</strong> {{ $item->firstname.' '.$item->lastname }}<br>
                            <span class="email"><strong>Email:</strong> {{ $item->email }}</span>
                            <br><strong>Phone:</strong> {{ $item->phone }}</td>
                        <td>{{ $item->market }} - {{ $item->provider }}</td>


                        <td>
                            @if( $item->newsletter )
                                Yes
                            @else
                                No
                            @endif
                        </td>
                        <td width="45%">{{ $item->message }}</td>
                        <td>
                            <a href="{{ url('/audience-relations/feedbacks/edit', $item->id) }}" title="Edit"
                               data-toggle="tooltip"><i class="fa fa-pencil-square fa-2x"></i></a>
                            <a href="{{ url('/audience-relations/feedbacks/remove/'.$item->id) }}"
                               title="Remove: {{ $item->id }}" data-toggle="tooltip"
                               onClick="return confirm('Are you sure you want to remove this Source?');"><i
                                        class="fa fa-times fa-2x"></i></a></td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            <p><?php echo $items->render(); ?></p>

        </section><!-- /.content -->
    </div><!-- /.content-wrapper -->

@stop
@section('footer_js')
    <script>

        function get_results(page) {
            start_date = $("#start_date").val();
            end_date = $("#end_date").val();
            search = $("#search").val();
            var num_results = $("#num_results").val();
            $.ajax({
                method: "get",
                url: "/audience-relations/feedbacks/search",
                data: {
                    start_date: start_date,
                    end_date: end_date,
                    search: search,
                    num_results: num_results,
                    page: page
                },

            }).success(function (data) {
                $(".content").html(data.html);
            });
        }

        function export_results() {
            start_date = $("#start_date").val();
            end_date = $("#end_date").val();
            search = $("#search").val();
            window.location = "/audience-relations/feedbacks/export/csv/"+start_date+"/"+end_date;
        }

        $(document).ready(function () {

            var start_date = moment(new Date).subtract(7 ,"days").format('MM-DD-YYYY');
            var end_date = moment(start_date).add(7, "days").format('MM-DD-YYYY');
            $("#start_date").val(start_date);
            $("#end_date").val(end_date);
            var search;

            $('.input-daterange input').each(function () {
                $(this).datepicker({
                    todayHighlight: true,
                    format: "yyyy-mm-dd"
                }).on("change", function () {
                    get_results();

                });

            });
            $("#num_results, #search").change(function () {
                get_results();
            });
            $("#search").keyup(function () {
                get_results();
            });

            $('#start_date').on('change', function () {
               start_date = $(this).val();
                end_date = moment(start_date).add(7, 'days').format('MM-DD-YYYY');
                $("#end_date").val(end_date);

            });
            $('.export').on('click', function () {
                export_results();

            });

            $(document).on('click', '.pagination li a', function (e) {
                e.preventDefault();
                var page_number = $(this).html();
                get_results(page_number);
            });
        });

    </script>
@stop

