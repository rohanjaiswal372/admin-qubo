@extends("app")
@section("header")
    <style>
        .highlight {
            background-color: yellow;
        }
    </style>
@endsection
@section("content")
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1 class="pull-left">Audience Relations: User Feedback</h1>
            <a href="#" class="export btn btn-primary pull-right"><i
                        class="fa fa-file-text-o"></i> Export CSV</a>
            <div class="pull-right">
                {!! Form::open(array('method'=>'GET', 'url' => 'audience-relations/feedbacks/search', 'id'=>'dates' ,'class'=>'form form-inline')) !!}
                <div class="input-group input-daterange">
                    <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                    {!!  Form::input('text', 'start_date','', ['class'=> 'form-control','id' => 'start_date']) !!}
                    <span class="input-group-addon">to</span>
                    {!! Form::input('text', 'end_date','', ['class'=> 'form-control','id' => 'end_date']) !!}
                    <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                </div>
                {{--<div class="input-group">--}}
                {{--<span class="input-group-addon">{!! Form::label('search', 'Search: ') !!}</span>--}}
                {{--{!! Form::text('search', Input::old('search'), ['class' => 'form-control', 'id'=> 'search','placeholder' => '']) !!}--}}
                {{--<span class="input-group-addon">{!! Form::label('num_results', '# of Results: ') !!}</span>--}}
                {{--{!! Form::select('num_results', ["5"=>5, "10"=> 10, "25"=> 25, "50"=> 50, "100"=>100], "25", ['class' => 'form-control', 'id'=> 'num_results']) !!}--}}
                {{--</div>--}}
                {!! Form::close() !!}
            </div>
        </section>
        <hr class="clearfix"/>
        <section class="content">
            <div class="box box-default">
                <div class="box-body">
                    @include('feedbacks.results',['items' => $items])
                </div>
            </div>
        </section>
    </div><!-- /.content-wrapper -->
@endsection
@section('footer_js')
    <script>
        function get_results(page) {
            start_date = $("#start_date").val();
            end_date = $("#end_date").val();
            var num_results = $("#num_results").val();
            $.ajax({
                method: "get",
                url: "/audience-relations/feedbacks/search",
                data: {
                    start_date: start_date,
                    end_date: end_date,
                    num_results: num_results,
                    page: page
                }
            }).done(function (data) {
                $(".content").html(data.html);
                load_functions();
                $('table.tablesorter').DataTable({
                    searchHighlight: true,
                    processing: true,
                    stateSave: false,
                    pageLength: 15
                    ,
                    "lengthMenu": [[10, 12, 15, 25, 50, -1], [10, 12, 15, 25, 50, "All"]],
                    paging: true,
                    colReorder: true,
                    "responsive": true
                });

            });
        }
        function export_results() {
            start_date = $("#start_date").val();
            end_date = $("#end_date").val();
            window.location = "/audience-relations/feedbacks/export/xls/" + start_date + "/" + end_date;
        }

        function load_functions() {
            $('.input-daterange input').each(function () {
                $(this).datepicker({
                    todayHighlight: true,
                    format: "yyyy-mm-dd"
                }).on("change", function () {
                    get_results();

                });
            });

            $('#start_date').on('change', function () {
                start_date = $(this).val();
                end_date = moment(start_date).add(7, 'days').format('YYYY-MM-DD');
                $("#end_date").val(end_date);

            });
            $('.export').on('click', function () {
                export_results();

            });
        }
        $(document).ready(function () {

            var start_date = moment(new Date).subtract(7, "days").format('YYYY-MM-DD');
            var end_date = moment(start_date).add(7, "days").format('YYYY-MM-DD');
            $("#start_date").val(start_date);
            $("#end_date").val(end_date);

            load_functions();
        });
    </script>
@endsection

