@extends("app")
@section("content")
    <div class="content-wrapper">
        <section class="content-header">
            <h1 class="pull-left">{{str_plural(ucfirst($class))}} Tags</h1>
            <div class="pull-right"></div>
        </section>
        <hr class="clearfix"/>
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="box box-primary pad">
                        <div class="box-header with-border">
                            <div class="row">
                                <div class="col-md-12">
                                    <h3 class="box-title pull-left">Type here to add new </h3>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-md-2">
                                    {!! Form::label('objects', 'Select a '.ucfirst($class)) !!}
                                    {!! Form::select('objects', $objects->pluck('title','id'),Input::old('objects'), ['class' => 'select2 object form-control']) !!}
                                </div>
                            </div>
                            <div class="row">
                                <div class="input-group col-md-2">
                                    <input name="tags" class="select2 form-control tags">
                                     <span class="input-group-btn">
                                        <button class="btn btn-info tag-submit"><i class="fa fa-plus"></i> Add Tag
                                        </button>
                                         </span>
                                </div>
                            </div>

                        </div>
                        <div class="box-body">
                            @include('tags.partials.list')
                        </div>
        </section><!-- /.content -->
    </div><!-- /.content-wrapper -->
@stop
@section('footer_js')
    <script>
        $(document).ready(function () {

            load_actions();
        });

        function load_actions() {
            $('.tag-submit').click(function () {
                var tag = $('.tags').val();
                var objectid = $('.object').val();
                if (tag != " " || tag != null || tag != "") {
                    $.get('/tags/create/' + objectid + '/' + tag + "/{{$class}}", function (response) {
                        $('.box-body').html(response);
                        load_actions();
                    });
                }
            });
            $('.remove').click(function (e) {
                e.preventDefault();
                var tagid = $(this).data('id');
                $.get('/tags/delete/' + tagid + "/{{$class}}", function (response) {
                    $('.box-body').html(response);
                    load_actions();
                });
            });
        }
    </script>
@stop