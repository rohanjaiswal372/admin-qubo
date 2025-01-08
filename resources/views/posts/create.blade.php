@extends("app")
@section("content")

        <!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="pull-left">
            <h3>New Post: </h3>
        </div>
        <div class="pull-right">
            <div class="btn-group">
                <a href="{{ URL::to('posts') }}" class="btn btn-primary"><i class="fa fa-undo"></i> All Posts</a>
            </div>
        </div>
        <div class="clearfix"></div>
    </section>

    <!-- Main content -->
    <section class="content">
        {{ HTML::ul($errors->all()) }}
        {!! Form::open(array('route' => array('posts.store'), 'method' => 'POST','files' => TRUE, 'id' => 'postform')) !!}
        <div class="row">
            <div class="col-md-6">
                <div class="box box-primary pad">
                    <div class="box-body">
                        <div class="form-group col-md-6">
                            {!! Form::label('active', 'Active (Push Live): ') !!}
                            {!! Form::checkbox('active', Input::old('active'), false, ['class' => 'form-control']) !!}
                            <p class="help-hint">Check to make active.</p>
                        </div>
                        <div class="form-group col-md-6">

                            {!! Form::label('activates_at', 'Start Date: ') !!}
                            <small> Date that post will go live on the site.</small>

                            <div class="input-group">
                                <div class="input-group-addon">
                                    <i class="fa fa-calendar"></i>
                                </div>
                                {!! Form::text('activates_at',NULL, ['class' => 'form-control datepicker', 'placeholder' => 'Start Date']) !!}
                            </div>

                        </div>
                        <div class="form-group col-md-6">
                            {!! Form::label('author', 'Author: ') !!}
                            {!! Form::text('author', Input::old('author'), ['class' => 'form-control', 'placeholder' => 'Author']) !!}
                        </div>
                        <div class="form-group col-md-6">
                            {!! Form::label('created_at', 'Posted On: ') !!}
                            <div class="input-group">
                                <div class="input-group-addon">
                                    <i class="fa fa-calendar"></i>
                                </div>
                                {!! Form::text('created_at', Carbon::parse()->format('m/d/Y g:i a'), ['class' => 'form-control datetimepicker', 'placeholder' => 'Posted On']) !!}
                            </div>
                        </div>

                    </div>
                </div>

                <div class="box box-primary pad">
                    <div class="box-header">
                        <h3 class="box-title"><i class="fa fa-video-camera"></i> Video:</h3>
                    </div>
                    <div class="box-body">

                        <div class="form-group">
                            {!! Form::label('brightcove_id', 'Brightcove Video ID: ') !!}
                            {!! Form::input('number','brightcove_id', NULL, ['class' => 'form-control', 'placeholder' => '44122312342321']) !!}
                            <p class="help-block">Copy the Brightcove Video ID found in the Medial Control panel on
                                Brightcove.</p>
                        </div>
                    </div>
                </div>

            </div>

            <div class="col-md-6">
                <div class="box box-primary pad">
                    <div class="box-header">
                        <div class="box-title"><i class="fa fa-info-circle"></i> Post Info:</div>
                    </div>
                    <div class="box-body">
                        <div class="form-group">
                            {!! Form::label('type_id', 'Type of Post: ') !!}
                            {!! Form::select('type_id',  [""=>"Select One"] +  $post_type_selector , NULL , ['class'=>'type_id_selector form-control','required' => 'required'] ) !!}
                        </div>
                        <div class="form-group">
                            {!! Form::label('title', 'Title: ') !!}
                            {!! Form::text('title', Input::old('title'), ['class' => 'form-control', 'placeholder' => 'Page Title', 'required' => 'required']) !!}
                        </div>

                        <div class="form-group">
                            {!! Form::label('pod_tagline', 'POD Tagline: ') !!}
                            {!! Form::text('pod_tagline', Input::old('pod_tagline'), ['class' => 'form-control', 'placeholder' => 'POD Tagline']) !!}
                        </div>

                        <div class="form-group">
                            {!! Form::label('path', 'Path: ') !!}
                            {!! Form::text('path', Input::old('path'), ['class' => 'form-control', 'placeholder' => 'Page Path']) !!}
                        </div>

                    </div>
                </div>

            </div>

        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary pad">
                    <div class="box-header">
                        <h3 class="box-title"><i class="fa fa-file-text-o"></i> Content:</h3>
                    </div>
                    <div class="box-body">
                        <div class="form-group">
                            {!! Form::label('content', 'Content: ') !!}
                            {!! Form::textarea('content', Input::old('content'), ['class' => 'form-control editor']) !!}
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="box box-primary pad">
                    <div class="box-header">
                        <h3 class="box-title"><i class="fa fa-paragraph"></i> Summary:</h3>
                    </div>
                    <div class="box-body">

                        <div class="form-group">
                            {!! Form::label('summary', 'Summary: ') !!}
                            {!! Form::textarea('summary', Input::old('summary'), ['class' => 'form-control editor']) !!}
                            <p class="help-hint">Keep the summary short.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary pad">
                    <div class="box-header">
                        <h3 class="box-title"><i class="fa fa-picture-o"></i> Images:</h3>
                    </div>
                    <div class="box-body">

                        <div class="form-group">
                            {!! Form::label('image', 'Primary Blog Image (Large): ') !!}
                            {!! Form::file('image') !!}
                            <p class="help-block">This is the primary image that will be displayed when a user is
                                reading the blog article.</p>
                        </div>

                        <div class="form-group">
                            {!! Form::label('image_secondary', 'Secondary Blog Image (Listing/Thumbnail): ') !!}
                            {!! Form::file('image_secondary') !!}
                            <p class="help-block">This image is used when listing the blog article on the main blog
                                page.</p>
                        </div>
                        <div class="form-group">
                            {!! Form::label('image_general', 'General use images within blog: ') !!}
                            {!! Form::file('image_general') !!}
                            <p class="help-block">You can upload images to use within the blog content
                                itself.</p>
                        </div>


                    </div>
                </div>
            </div>
        </div>
        @include('templates.partials.savebar')
    </section><!-- /.content -->
</div><!-- /.content-wrapper -->
@stop


