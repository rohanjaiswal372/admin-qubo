@extends("app")
@section("content")

        <!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1><i class="fa fa-file-o"></i> Pages</h1>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary pad">
                    <div class="box-header with-border">
                        <h3 class="box-title">Create A New Page</h3>
                        <div class="box-tools pull-right">
                            <a href="{{ URL::previous() }}" class="btn btn-danger"><i class="fa fa-undo"></i> Back</a>
                        </div>
                    </div>
                    <div class="box-body">

                        {{ HTML::ul($errors->all()) }}

                        {!! Form::open(array('route' => array('pages.store'), 'method' => 'POST')) !!}
                        <div class="form-group">
                            {!! Form::label('title', 'Title: ') !!}
                            {!! Form::text('title', Input::old('title'), ['class' => 'form-control', 'placeholder' => 'Page Title']) !!}
                        </div>

                        <div class="form-group">
                            {!! Form::label('path', 'Path: ') !!}
                            {!! Form::text('path', Input::old('path'), ['class' => 'form-control', 'placeholder' => 'Page Path']) !!}
                        </div>
                        <div class="form-group">
                            <div class="checkbox">
                                <label title="" data-toggle="tooltip">
                                    {!! Form::checkbox('active','active', ('active' ) ? "0":"" ) !!}
                                    Active </label>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="checkbox">
                                <label title="" data-toggle="tooltip">
                                    {!! Form::checkbox('searchable','searchable', ('searchable' ) ? "0":"" ) !!}
                                    Searchable </label>
                            </div>
                        </div>

                        <div class="form-group">
                            {!! Form::label('content', 'Content: ') !!}
                            {!! Form::textarea('content', Input::old('content'), ['class' => 'form-control editor']) !!}
                        </div>
                    </div>

                </div>

                <div class="box box-primary pad">
                    <div class="box-header with-border">
                        <h3 class="box-title">Page Colors:</h3>
                        <div class="box-tools pull-right">

                        </div>
                    </div>
                    <div class="box-body">
                        <div class="form-group">
                            @foreach($color_types as $color)
                                <div class="col-md-3 text-center"
                                     style="min-height:150px; background-color:{{$color->code}}">
                                    <div class="box box-default">
                                        <div class="box-header with-border">
                                            <h5>{{$color->description}}</h5>
                                            <div class="box-tools pull-right">

                                            </div>
                                        </div>
                                        <div class="box-body">
                                            <input type="hidden"
                                                   name="colors[{{$color->id}}][type_id]"
                                                   value="{{$color->id}}"/> <input type="text"
                                                                                   name="colors[{{$color->id}}][code]"
                                                                                   class="colors form-control colorpicker"
                                                                                   value=""/>
                                        </div>
                                    </div>
                                </div>
                            @endforeach

                        </div>
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
            $('.colors').on('changeColor', function (e) {
                $(this).parent().css("backgroundColor", e.color.toHex());
            });
            $('.colors').keyup(function (e) {
                var txt = $(this).val();
                if (!/#/.test(txt)) {
                    $(this).val('#' + txt);
                }
            });
        });
    </script>
@stop


