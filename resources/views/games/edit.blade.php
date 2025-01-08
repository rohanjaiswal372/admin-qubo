@extends("app")
@section("content")

        <!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1 class="pull-left">Games</h1>
        <div class="box-tools pull-right">
            <a href="{{ URL::previous() }}" class="btn btn-danger"><i class="fa fa-undo"></i> Back</a>
            <a href="{{ route('games.create') }}" class="btn btn-success"><i class="fa fa-plus"></i> Create New</a>
        </div>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary pad">
                    <div class="box-header with-border">
                        <h3 class="box-title">Editing {{$item->title}} <a class="btn btn-info"
                                                                          target="_blank"
                                                                          href="{{$item->url}}"><i class="fa fa-binoculars"></i> Preview Game</a>
                            @if($item->scope == "spilgames")
                                <a class="btn btn-primary"
                                   target="_blank"
                                   href="http://publishers.spilgames.com/en/game/{{$item->path}},{{$item->embed_id}}"><i class="fa fa-binoculars"></i> View on Publisher site</a>
                                @endif
                        </h3>

                    </div>

                    <div class="box-body">

                        {{ HTML::ul($errors->all()) }}

                        {!! Form::model($item, array('route' => array('games.update', $item->id), 'method' => 'PUT', 'files' => TRUE)) !!}
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group col-md-4">
                                    {!! Form::label('title', 'Title: ') !!}
                                    {!! Form::text('title', Input::old('title'), ['class' => 'form-control', 'placeholder' => 'Page Title']) !!}
                                </div>

                                <div class="form-group col-md-4">
                                    {!! Form::label('path', 'Path: ') !!}
                                    {!! Form::text('path', Input::old('path'), ['class' => 'form-control', 'placeholder' => 'Page Path']) !!}
                                </div>
                                <div class="form-group col-md-4">
                                    {!! Form::label('embed_id', 'Embed ID: ') !!}
                                    {!! Form::text('embed_id', Input::old('embed_id'), ['class' => 'form-control', 'placeholder' => 'external embded id']) !!}
                                </div>
                                <div class="form-group col-md-4">
                                    {!! Form::label('scope', 'Scope(ex:Qubo): ') !!}
                                    {!! Form::text('scope', Input::old('scope'), ['class' => 'form-control', 'placeholder' => 'Qubo']) !!}
                                </div>
                                <div class="form-group col-md-4">
                                    {!! Form::label('active', 'Active: ') !!}
                                    {!! Form::checkbox('active', Input::old('active'), ['class' => 'form-control']) !!}
                                    <p class="help-hint">Check to make active.</p>
                                </div>

                                <div class="form-group col-md-4">
                                    {!! Form::label('sort_order', 'Sort Order: ') !!}
                                    {!! Form::text('sort_order', Input::old('sort_order'), ['class' => 'form-control', 'placeholder' => 'Sort Order']) !!}
                                </div>

                                <div class="form-group col-md-12">
                                    {!! Form::label('tags[]', 'Tags: ') !!}
                                    <select name="tags[]" class="select2 form-control tags" multiple>
                                            @if($item->tags)
                                                @foreach($item->tags as $tag)
                                                    <option selected="selected" value="{{$tag->name}}">{{$tag->name}}</option>
                                                @endforeach
                                            @endif

                                        @if($game_tags)
                                                    @foreach($game_tags as $tag)
                                                        <option value="{{$tag->name}}">{{$tag->name}}</option>
                                            @endforeach
                                            @endif

                                    </select>

                                </div>

                                <div class="form-group col-md-12">
                                    {!! Form::label('description', 'Description: ') !!}
                                    {!! Form::textarea('description', Input::old('description'), ['class' => 'form-control']) !!}
                                </div>
                            </div>

                            <div class="col-md-6">

                                <div class="form-group col-md-12">

                                    @if ($item->images)
                                        @foreach( $item->images as $image )
                                            <div class="row">
                                                <div class="col-md-10">
                                                    <img src="{{ image($image->url) }}" class="img img-responsive"/>
                                                </div>
                                                <div class=col-md-2">
                                                    <a href="{{ URL::to('/image/remove/'.$image->id) }}"
                                                       onclick="return confirm('Are you sure? This will remove the image from our system.');"><i
                                                                class="fa fa-2x fa-trash-o text-danger"></i></a>
                                                </div>
                                            </div>
                                        @endforeach
                                    @endif
                                </div>
                                <div class="form-group">
                                    {!! Form::label('image', 'Additional Game Images: ') !!}
                                    {!! Form::file('image') !!}
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
    <script src="{{ asset("/js/games.js") }}" type="text/javascript"></script>
@stop



