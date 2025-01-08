@extends("app")
@section('header')
    <style>
        .colorpicker {
            background: rgba(255, 255, 255, 0.70);
        }
    </style>
@stop
@section("content")
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="pull-left">
                <h3><strong>New Show</h3>
            </div>
            <div class="pull-right">
                <div class="btn-group">
                    <a href="{{ URL::previous() }}" class="btn btn-primary"><i class="fa fa-undo"></i> Back</a>
                </div>
            </div>
            <div class="clearfix"></div>
        </section>
        <!-- Main content -->
        <section class="content">

            {!! Form::model(array('route' => array('shows.store'), 'method' => 'POST')) !!}
            <div class="row-fluid">
                <div class="col-md-6">
                    <div class="box box-primary pad">
                        <div class="box-header with-border">
                            <h3 class="box-title">Show Info:</h3>
                            <div class="box-tools pull-right">

                            </div>
                        </div>

                        <div class="box-body">
                            <div class="form-group col-md-6">
                                {!! Form::label('name', 'Name: ') !!}
                                {!! Form::text('name', Input::old('name'), ['class' => 'form-control', 'required'=>'required']) !!}
                            </div>
                            <div class="form-group col-md-6">
                                {!! Form::label('short_name', 'Short Name: ') !!}
                                {!! Form::text('short_name', Input::old('short_name'), ['class' => 'form-control', 'placeholder' => '']) !!}
                            </div>
                            <div class="form-group col-md-3">
                                {!! Form::label('code', 'Code (3-Letter): ') !!}
                                {!! Form::text('code', Input::old('code'), ['class' => 'form-control', 'maxlength' => '4']) !!}
                            </div>
                            <div class="form-group col-md-3">
                                {!! Form::label('active', 'Active: ') !!}
                                {!! Form::checkbox('active', Input::old('active'), ['class' => 'form-control']) !!}
                                <p class="help-hint">Check to make active.</p>
                            </div>
							
							
							<div class="form-group col-md-12">
                                {!! Form::label('broadview_handle', 'Broadview Handle: ') !!}
                                {!! Form::select('broadview_handle',  [""=>"Select One"] + \PromoTool::getBroadviewHandles() , Input::old('broadview_handle'), ['class' => 'form-control', 'placeholder' => '']) !!}
                            </div>
							
							
                            <div class="form-group col-md-3">
                                {!! Form::label('new', 'New: ') !!}
                                {!! Form::checkbox('new', Input::old('new'), ['class' => 'form-control']) !!}
                            </div>

                            <div class="form-group col-md-3">
                                {!! Form::label('holiday', 'Active: ') !!}
                                {!! Form::checkbox('holiday', Input::old('holiday'), ['class' => 'form-control']) !!}
                                <p class="help-hint">Check if this is a Holiday only show.</p>
                            </div>
                            <div class="form-group col-md-12">
                                {!! Form::label('description', 'Description: ') !!}
                                {!! Form::textarea('description', Input::old('description'), ['class' => 'form-control ckeditor', 'placeholder' => '']) !!}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="box box-primary pad">
                        <div class="box-header with-border">
                            <h3 class="box-title">Show Colors:</h3>
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
                <div class="col-md-6">
                    <div class="box box-primary pad">
                        <div class="box-header with-border">
                            <h3 class="box-title">Carousel Info:</h3>
                            <div class="box-tools pull-right">
                            </div>
                        </div>
                        <div class="box-body">
                            <div class="form-group col-md-6">
                                {!! Form::label('headline', 'Carousel headline: ') !!}
                                {!! Form::text('headline', Input::old('headline'), ['class' => 'form-control', 'placeholder' => '']) !!}
                            </div>
                            <div class="form-group col-md-6">
                                {!! Form::label('subtitle', 'Subtitle: ') !!}
                                {!! Form::text('subtitle', Input::old('subtitle'), ['class' => 'form-control', 'placeholder' => '']) !!}
                            </div>
                            <div class="form-group col-md-3">
                                {!! Form::label('scope', 'Scope: ') !!}
                                {!! Form::text('scope', Input::old('scope'), ['class' => 'form-control', 'placeholder' => '']) !!}
                            </div>
                            <div class="form-group col-md-3">
                                {!! Form::label('sort_order', 'Sort Order: ') !!}
                                {!! Form::text('sort_order', Input::old('sort_order'), ['class' => 'form-control', 'placeholder' => '']) !!}
                            </div>
                            <div class="form-group col-md-3">
                                {!! Form::label('position', 'Logo Position: ') !!}
                                {!! Form::select('position',  ['left'=> 'left', 'left-tall'=>'left-tall','right' => 'right','right-tall'=>'right-tall'], ['class' => 'form-control select2', 'placeholder' => '']) !!}
                            </div>
                            <div class="form-group col-md-3">
                                {!! Form::label('color', 'Menu color (example: #efefef)') !!}
                                {!! Form::text('color', Input::old('color'), ['class' => 'form-control colorpicker', 'placeholder' => '']) !!}
                            </div>

                        </div>

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

