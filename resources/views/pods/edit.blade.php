@extends("app")
@section("content")

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h3 class="box-title pull-left">Editing Pod: {{ $item->name }}</h3>
            <div class="pull-right">
                <div class="btn-group">
                    <a href="{{ url('pods') }}" class="btn btn-primary"><i class="fa fa-list"></i> All Pods</a>
                    <a href="{{ url('pods/create') }}" class="btn btn-success"><i class="fa fa-plus"></i> New Pod</a>
                </div>
            </div>
            <div class="clearfix"></div>
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="row">
                {{-------------------------------------------------------------------------------------------}}
                <div class="col-md-4">
                    <div class="box box-primary pad">
                        <div class="box-header with-border">
                            <h3 class="box-title"><i class="fa fa-info-circle text-info"></i> POD INFO</h3>
                            <div class="box-tools pull-right">
                                <button type="button" class="btn btn-box-tool" data-widget="collapse">
                                    <i class="fa fa-minus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="box-body">
                            {{ HTML::ul($errors->all()) }}
                            <div class="row">
                                @if( is_null($item) )
                                    {!! Form::open(array('route' => array('pods.store'), 'method' => 'PATCH', 'files' => TRUE)) !!}
                                @else
                                    {!! Form::model($item, array('route' => array('pods.update', $item->id), 'method' => 'PUT', 'files' => TRUE)) !!}
                                @endif

                                <div class="form-group col-md-6"
                                     title="This is used internally only."
                                     data-toggle="tooltip">
                                    {!! Form::label('name', 'Name (Internal): ') !!}
                                    {!! Form::text('name', Input::old('name'), ['class' => 'form-control', 'placeholder' => 'All New Shows']) !!}
                                </div>
                                <div class="form-group col-md-6"
                                     title="This will dipslay within the gray bar below the graphic."
                                     data-toggle="tooltip">
                                    {!! Form::label('title', 'Pod Title: ') !!}
                                    {!! Form::text('title', Input::old('title'), ['class' => 'form-control', 'placeholder' => 'All New Shows']) !!}
                                </div>


                                <div class="form-group col-md-6"
                                     title="This will dipslay within the gray bar below the graphic."
                                     data-toggle="tooltip">
                                    {!! Form::label('path', 'Pod Path: ') !!}
                                    {!! Form::text('path', Input::old('path'), ['class' => 'form-control', 'placeholder' => 'Path']) !!}
                                </div>


                                <div class="form-group col-md-6"
                                     title="Where should the box take the user when clicked?"
                                     data-toggle="tooltip">
                                    {!! Form::label('hyperlink', 'Pod Link: ') !!}
                                    {!! Form::text('hyperlink', Input::old('hyperlink'), ['class' => 'form-control', 'placeholder' => 'http://www.iontelevision.com']) !!}
                                </div>

                                <div class="form-group col-md-6"
                                     title="Check to open a new window when the box is clicked."
                                     data-toggle="tooltip">
                                    {!! Form::label('hyperlink_target', 'New window:') !!}
                                    {!! Form::checkbox('hyperlink_target', Input::old('hyperlink_target')) !!}

                                </div>

                            </div>
                        </div>
                    </div>
                </div>
                {{-------------------------------------------------------------------------------------------}}
                <div class="col-md-4">
                    <div class="box box-primary pad">
                        <div class="box-header with-border">
                            <h3 class="box-title"><i class="fa fa-square-o text-info"></i> HOVER EFFECT</h3>
                            <small> If you would like this pod to have a hover effect please fill in at least the
                                headline.
                            </small>
                            <div class="box-tools pull-right">
                                <button type="button" class="btn btn-box-tool" data-widget="collapse">
                                    <i class="fa fa-minus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="box-body">
                            <div class="form-group" title="This will be the primary headline when the user hovers over the
                                    box." data-toggle="tooltip">
                                {!! Form::label('headline', 'Hover Headline: ') !!}
                                {!! Form::text('headline', Input::old('headline'), ['class' => 'form-control', 'placeholder' => 'All New Shows']) !!}
                                <br>
                            </div>
                            <div class="form-group"
                                 title="This is the secondary tagline below the primary headline."
                                 data-toggle="tooltip">
                                {!! Form::label('tagline', 'Hover Tagline: ') !!}
                                {!! Form::text('tagline', Input::old('tagline'), ['class' => 'form-control', 'placeholder' => 'follow this amazing story']) !!}
                            </div>
                        </div>
                    </div>
                </div>

                {{-- CAMPAIGNS BOX --}}
                @include('campaigns.partials.campaigns-selector',compact('campaigns','item'),['col'=>'col-md-4'])
                {{-- ~~~~~~~~~~~~~~~~~~~~~~~ --}}
                {{-------------------------------------------------------------------------------------------}}
                <div class="col-md-6">
                    <div class="box box-primary pad">
                        <div class="box-header with-border">
                            <h3 class="box-title"><i class="fa fa-picture-o text-info"></i> IMAGES</h3>
                            <div class="box-tools pull-right">
                                <button type="button" class="btn btn-box-tool" data-widget="collapse">
                                    <i class="fa fa-minus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="box-body">

                            @if( !is_null($item) )
                                @if (count( $item->images) > 0 )
                                    @include('templates.partials.images',compact('item'))
                                    <hr/>
                                @endif
                            @endif

                            <div class="form-group">
                                <label class="btn btn-warning btn-file btn-lg"
                                       title="Pod image without sponsorship or logo for default pods"
                                       data-toggle="tooltip"><span>
                                   <i class="fa fa-upload"></i> New Pod Img (360x240) </span><input type="file"
                                                                                                    id="image"
                                                                                                    name="image" class="hide">
                                </label>
                            </div>

                        </div>
                    </div>
                </div>
                {{-------------------------------------------------------------------------------------------}}
                <div class="col-md-6">
                    <div class="box box-primary pad">
                        <div class="box-header with-border">
                            <h3 class="box-title"><i class="fa fa-film text-info"></i> SHOW</h3>
                            <small> If this pod needs to be associated with a show, please select the show here.</small>
                            <div class="box-tools pull-right">
                                <button type="button" class="btn btn-box-tool" data-widget="collapse">
                                    <i class="fa fa-minus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="box-body">

                            <div class="form-group"
                                 title="If this slide is to showcase a show, please select it here."
                                 data-toggle="tooltip">
                                {!! Form::label('show_id', 'Show: ') !!}
                                {!! Form::select('show_id', ["Select One"] + $selects['Shows'], Input::old('show_id'), ['class' => 'form-control select2']) !!}
                            </div>

                            <div class="form-group" title="Check if you want to ignore the tagline and display the next air
                                    date." data-toggle="tooltip">
                                {!! Form::label('pull_next_air', 'Pull Next Air Date: ') !!}
                                {!! Form::checkbox('pull_next_air', Input::old('pull_next_air')) !!}
                            </div>
                        </div>
                    </div>
                </div>
                {{-------------------------------------------------------------------------------------------}}
            </div>
            @include('templates.partials.savebar')
        </section><!-- /.content -->
    </div><!-- /.content-wrapper -->

@stop
@section('footer_js')
    <script src="{{ asset("/js/posts.js") }}"></script>
@stop


