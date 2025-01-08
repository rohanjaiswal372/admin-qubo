@extends("app")
@section("content")

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h3 class="box-title">CREATE NEW POD</h3>
        </section>
        <!-- Main content -->
        <section class="content">
            <div class="row">
                {{-------------------------------------------------------------------------------------------}}
                <div class="col-md-6">
                    <div class="box box-primary pad">
                        <div class="box-header with-border">
                            <h3 class="box-title">POD INFO</h3>
                            <div class="box-tools pull-right">
                                <button type="button" class="btn btn-box-tool" data-widget="collapse">
                                    <i class="fa fa-minus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="box-body">
                            {{ HTML::ul($errors->all()) }}
                            <div class="row">

                            {!! Form::open(array('route' => array('pods.store'), 'method' => 'POST', 'files' => true)) !!}

                                <div class="form-group col-md-6" title="This is used internally only." data-toggle="tooltip">
                                    {!! Form::label('name', 'Name (Internal): ') !!}
                                    {!! Form::text('name', Input::old('name'), ['class' => 'form-control', 'placeholder' => 'All New Shows']) !!}
                                </div>
                                <div class="form-group col-md-6" title="This will dipslay within the gray bar below the graphic." data-toggle="tooltip">
                                    {!! Form::label('title', 'Pod Title: ') !!}
                                    {!! Form::text('title', Input::old('title'), ['class' => 'form-control', 'placeholder' => 'All New Shows']) !!}
                                </div>

                                <div class="form-group col-md-6" title="Where should the box take the user when clicked?" data-toggle="tooltip">
                                    {!! Form::label('hyperlink', 'Pod Link: ') !!}
                                    {!! Form::text('hyperlink', Input::old('hyperlink'), ['class' => 'form-control', 'placeholder' => 'http://www.iontelevision.com']) !!}
                                </div>

                                <div class="form-group col-md-6" title="Check to open a new window when the box is clicked." data-toggle="tooltip">
                                    {!! Form::label('hyperlink_target', 'Open link in a new window:') !!}
                                    {!! Form::checkbox('hyperlink_target', Input::old('hyperlink_target')) !!}

                                </div>

                            </div>
                        </div>
                    </div>
                </div>
                {{-------------------------------------------------------------------------------------------}}
                <div class="col-md-6">
                    <div class="box box-primary pad">
                        <div class="box-header with-border">
                            <h3 class="box-title">HOVER EFFECT</h3>
                            <small> If you would like this pod to have a hover effect please fill in at least the
                                headline.</small>
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

                            </div>
                            <div class="form-group" title="This is the secondary tagline below the primary headline." data-toggle="tooltip">
                                {!! Form::label('tagline', 'Hover Tagline: ') !!}
                                {!! Form::text('tagline', Input::old('tagline'), ['class' => 'form-control', 'placeholder' => 'follow this amazing story']) !!}
                            </div>
                        </div>
                    </div>
                </div>
                {{-------------------------------------------------------------------------------------------}}
                <div class="col-md-6">
                    <div class="box box-primary pad">
                        <div class="box-header with-border">
                            <h3 class="box-title">IMAGES</h3>
                            <div class="box-tools pull-right">
                                <button type="button" class="btn btn-box-tool" data-widget="collapse">
                                    <i class="fa fa-minus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="box-body">

                            @if( !is_null($item) )
                                @if (count( $item->images) > 0 )
                                    <table class="table no-margin table-striped table-condensed table-hover">
                                        <thead>
                                        <tr>
                                            <th>Image</th>
                                            <th>Action</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @if(count($item->images))
                                            @foreach( $item->images as $image )

                                                <tr>
                                                    <td>
                                                        <h4>{{ucfirst($image->type_id)}}</h4>
                                                        <img src="{{image($image->url)}}"
                                                             class="img-responsive"/>
                                                    </td>
                                                    <td><a href="{{ URL::to('/image/remove/'.$image->id) }}"
                                                           onclick="return confirm('Are you sure? This will remove the image from our system.');"><i class="fa fa-trash fa-2x text-danger"></i></a>
                                                </tr>
                                            @endforeach
                                        @endif
                                        </tbody>
                                    </table>
                                    <hr/>
                                @endif
                            @endif

                            <div class="form-group">
                                {!! Form::label('image', 'Image: ') !!}
                                {!! Form::file('image') !!}
                            </div>
                            <div class="form-group">
                                {!! Form::label('hover-image', 'Hover Image: ') !!}
                                {!! Form::file('hover-image') !!}
                            </div>
                        </div>
                    </div>
                </div>
                {{-------------------------------------------------------------------------------------------}}
                <div class="col-md-6">
                    <div class="box box-primary pad">
                        <div class="box-header with-border">
                            <h3 class="box-title">SHOW</h3>
                            <small> If this pod needs to be associated with a show, please select the show here.</small>
                            <div class="box-tools pull-right">
                                <button type="button" class="btn btn-box-tool" data-widget="collapse">
                                    <i class="fa fa-minus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="box-body">

                            <div class="form-group" title="If this slide is to showcase a show, please select it here." data-toggle="tooltip">
                                {!! Form::label('show_id', 'Show: ') !!}
                                {!! Form::select('show_id', ["Select One"] + $selects['Shows'], Input::old('show_id')) !!}
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


