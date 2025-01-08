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
                            <h3 class="box-title">Editing {{$item->title}}</h3>
                            <div class="box-tools pull-right">
                                <a href="{{ URL::previous() }}" class="btn btn-danger"><i class="fa fa-undo"></i> Back</a>
                                <a href="{{ URL::to('/pages/remove/'.$item->id) }}" class="btn btn-danger" onClick="return confirm('Are you sure you want to remove this Page?');"> Delete<i class="fa fa-trash-o"></i></a>
                            </div>
                        </div>

                        <div class="box-body">

                            {{ HTML::ul($errors->all()) }}

                            {!! Form::model($item, array('route' => array('pages.update', $item->id), 'method' => 'PUT')) !!}
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
                                        {!! Form::checkbox('active','active', ('active' ) ? "1":"" ) !!}
                                        Active </label>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="checkbox">
                                    <label title="" data-toggle="tooltip">
                                        {!! Form::checkbox('searchable','searchable', ('searchable' ) ? "1":"" ) !!}
                                        Searchable </label>
                                </div>
                            </div>
                            <div class="form-group">
                                {!! Form::label('content', 'Content: ') !!}
                                {!! Form::textarea('content', Input::old('content'), ['class' => 'form-control ckeditor']) !!}
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <div class="row">
            <div class="col-md-12">
                <div class="box box-primary pad">
                    <div class="box-header with-border">
                        <h3 class="box-title">Page Colors:</h3>
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse">
                                <i class="fa fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="box-body">
                        <div class="form-group col-md-12">
                            @if($item->colors)
                                @foreach($item->colors as $color)
                                    <div class="col-md-3 text-center"
                                         style="min-height:100px; background-color:{{$color->code}}">
                                        <div class="box box-default">
                                            <div class="box-body">
                                                <h5>{{$color->type->description}}</h5>
                                                <input type="hidden"
                                                       name="colors[{{$color->type->id}}][id]"
                                                       value="{{$color->id}}"/> <input type="hidden"
                                                                                       name="colors[{{$color->type->id}}][type_id]"
                                                                                       value="{{$color->type_id}}"/>
                                                <input type="text"
                                                       name="colors[{{$color->type->id}}][code]"
                                                       class="colors form-control colorpicker"
                                                       value="{{$color->code}}"/>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @endif

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
        CKEDITOR.config.allowedContent = true;
    </script>
@endsection
